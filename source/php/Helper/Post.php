<?php

namespace Modularity\Helper;

class Post
{
    /**
     * Lists all meta-keys existing for the given posttype
     *
     * Attention: Since this method is using the database to get the
     * metadata keys there need to be posts in the posttype to get results
     *
     * @param  string $posttype The posttype
     * @return array            Meta keys as array
     */
    public static function getPosttypeMetaKeys($posttype)
    {
        global $wpdb;
        $metaKeys = $wpdb->get_results("
            SELECT DISTINCT {$wpdb->postmeta}.meta_key
            FROM {$wpdb->postmeta}
            LEFT JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
            WHERE
                {$wpdb->posts}.post_type = '$posttype'
                AND NOT LEFT({$wpdb->postmeta}.meta_key, 1) = '_'
        ");

        return $metaKeys;
    }

    /**
     * Gets the post template of the current editor page
     * @return string Template slug
     */
    public static function getPostTemplate($id = null, $trim = false)
    {
        if ($archive = self::isArchive()) {
            return $archive;
        }

        global $post;

        // If $post is empty try to fetc post from querystring
        if (!$post && isset($_GET['id']) && is_numeric($_GET['id'])) {
            $post = get_post($_GET['id']);

            if (!$post) {
                throw new \Error('The requested post was not found.');
            }
        }

        if (!$post) {
            return isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : $archive;
        }

        // If post is set, fetch the template
        $template = get_page_template_slug($post->ID);

        // If this is the front page and the template is set to page.php or page.blade.php default to just "page"
        if ($post->ID === (int)get_option('page_on_front') && in_array($template, array('page.php', 'page.blade.php'))) {
            return 'page';
        }

        if (!$template) {
            $template = self::detectCoreTemplate($post);
        }

        $template = $trim ? str_replace('.blade.php', '', $template) : $template;

        return $template;
    }

    /**
     * Detects core templates
     * @return string Template
     */
    public static function detectCoreTemplate($post)
    {
        if ((int)get_option('page_on_front') == (int)$post->ID) {
            return \Modularity\Helper\Wp::findCoreTemplates(array(
                'front-page',
                'page'
            ));
        }

        switch ($post->post_type) {
            case 'post':
                return 'single';
                break;

            case 'page':
                return 'page';
                break;

            default:
                return \Modularity\Helper\Wp::findCoreTemplates(array(
                    'single-' . $post->post_type,
                    'single',
                    'page'
                ));
                break;
        }

        return 'index';
    }

    /**
     * Verifies if the current page is an archive or search result page
     * @return boolean
     */
    public static function isArchive()
    {
        global $archive;
        global $post;

        if (defined('DOING_AJAX') && DOING_AJAX) {
            $archive = !is_numeric($_POST['id']) ? $_POST['id'] : '';
        }

        if (substr($archive, 0, 8) == 'archive-' || is_search()) {
            return $archive;
        }

        if (is_archive() && (is_object($post) && $post->post_type == 'post')) {
            return 'archive';
        } elseif (is_search() || (is_object($post) && is_post_type_archive($post->post_type))) {
            return 'archive-' . $post->post_type;
        } elseif (isset($_GET['id']) && $_GET['id'] == 'author') {
            return 'author';
        }

        return false;
    }

    /**
     * Prepare post object before sending to view
     * Appends useful variables for views (generic).
     * 
     * @param   object   $post    WP_Post object
     * 
     * @return  object   $post    Transformed WP_Post object
     */
    public static function preparePostObject($post) {
        $post = self::complementObject($post);
        $post = \Modularity\Helper\FormatObject::camelCase($post);
        return $post;
    }

    /**
     * Add post data on post object
     * 
     * @param   object   $postObject    The post object
     * @param   object   $appendFields  Data to append on object
     * 
     * @return  object   $postObject    The post object, with appended data
     */
    public static function complementObject(
        $postObject,
        $appendFields = array(
            'excerpt',
            'post_content_filtered',
            'post_title_filtered',
            'permalink',
            'terms'
        )
    ) {

        //Check that a post object is entered
        if (!is_a($postObject, 'WP_Post')) {
            return $postObject;
            throw new WP_Error("Complement object must recive a WP_Post class"); 
        }

        //More? Less? 
        $appendFields = apply_filters('Modularity/Helper/Post/complementPostObject', $appendFields);

        //Generate excerpt
        if (in_array('excerpt', $appendFields)) {
            if (empty($postObject->post_excerpt)) {

                //Create excerpt if not defined by editor
                $postObject->excerpt = wp_trim_words(
                    $postObject->post_content,
                    apply_filters('Modularity/Helper/Post/ExcerptLenght', 55),
                    apply_filters('Modularity/Helper/Post/MoreTag', "...")
                );

                //Create excerpt if not defined by editor
                $postObject->excerpt_short = wp_trim_words(
                    $postObject->post_content,
                    apply_filters('Modularity/Helper/Post/ExcerptLenghtShort', 20),
                    apply_filters('Modularity/Helper/Post/MoreTag', "...")
                );

                //No content in post
                if (empty($postObject->excerpt)) {
                    $postObject->excerpt = '<span class="undefined-content">' . __("Item is missing content", 'municipio') . "</span>";
                }
            } else {
                $postObject->excerpt_short = wp_trim_words(
                    $postObject->content,
                    apply_filters('Modularity/Helper/Post/ExcerptLenghtShort', 20),
                    apply_filters('Modularity/Helper/Post/MoreTag', "...")
                );
            }
        }

        //Get permalink
        if (in_array('permalink', $appendFields)) {
            $postObject->permalink              = get_permalink($postObject);
        }

        //Get filtered content
        if (in_array('post_content_filtered', $appendFields)) {
            //Parse lead
            $parts = explode("<!--more-->", $postObject->post_content);

            if(is_array($parts) && count($parts) > 1) {

                //Remove the now broken more block
                foreach ($parts as &$part) {
                    $part = str_replace('<!-- wp:more -->', '', $part);
                    $part = str_replace('<!-- /wp:more -->', '', $part);
                }

                $excerpt = self::createLeadElement(array_shift($parts));
                $content = self::removeEmptyPTag(implode(PHP_EOL, $parts));

            } else {
                $excerpt = "";
                $content = self::removeEmptyPTag($postObject->post_content);
            }

            //Replace builtin css classes to our own
            $postObject->post_content_filtered  = $excerpt . str_replace(
                [
                    'wp-caption',
                    'c-image-text',
                    'wp-image-',
                    'alignleft',
                    'alignright',
                    'alignnone',
                    'aligncenter',

                    //Old inline transition button
                    'btn-theme-first',
                    'btn-theme-second',
                    'btn-theme-third',
                    'btn-theme-fourth',
                    'btn-theme-fifth',

                    //Gutenberg columns
                    'wp-block-columns',

                    //Gutenberg block image
                    'wp-block-image',
                    '<figcaption>'
                ],
                [
                    'c-image',
                    'c-image__caption',
                    'c-image__image wp-image-',
                    'u-float--left@sm u-float--left@md u-float--left@lg u-float--left@xl u-margin__y--2 u-margin__right--2@sm u-margin__right--2@md u-margin__right--2@lg u-margin__right--2@xl u-width--100@xs', 
                    'u-float--right@sm u-float--right@md u-float--right@lg u-float--right@xl u-margin__y--2 u-margin__left--2@sm u-margin__left--2@md u-margin__left--2@lg u-margin__left--2@xl u-width--100@xs', 
                    '',
                    'u-margin__x--auto',

                    //Old inline transition button
                    'c-button c-button__filled c-button__filled--primary c-button--md',
                    'c-button c-button__filled c-button__filled--secondary c-button--md',
                    'c-button c-button__filled c-button__filled--secondary c-button--md',
                    'c-button c-button__filled c-button__filled--secondary c-button--md',
                    'c-button c-button__filled c-button__filled--secondary c-button--md',

                    //Gutenberg columns
                    'o-grid o-grid--no-margin@md',

                    //Gutenberg block image
                    'c-image',
                    '<figcaption class="c-image__caption">'
                ],
                apply_filters('the_content', $content)
            );
        }

        //Get filtered post title
        if (in_array('post_title_filtered', $appendFields)) {
            $postObject->post_title_filtered = apply_filters('the_title', $postObject->post_title); 
        }

        //Get post tumbnail image
        $postObject->thumbnail = self::getFeaturedImage($postObject->ID, [400, 225]); 

        //Append post terms
        if (in_array('terms', $appendFields)) {
            $postObject->terms            = self::getPostTerms($postObject->ID);
            $postObject->termsUnlinked    = self::getPostTerms($postObject->ID, false);
        }

        return $postObject;
    }

    /**
     * Remove empty ptags from string
     *
     * @param string $string    A string that may contain empty ptags
     * @return string           A string that not contain empty ptags
     */
    private static function removeEmptyPTag($string) {
        return preg_replace("/<p[^>]*>(?:\s|&nbsp;)*<\/p>/", '', $string);
    }

    /**
     * Get the post featured image
     *
     * @param integer   $postId         
     * @return array    $featuredImage  The post thumbnail image, with alt and title
     */
    public static function getFeaturedImage($postId, $size = 'full')
    {
        $featuredImageID = get_post_thumbnail_id($postId);

        $featuredImageSRC = \get_the_post_thumbnail_url(
            $postId,
            apply_filters('Modularity/Helper/Post/FeaturedImageSize', $size)
        );
        $featuredImageAlt   = get_post_meta($featuredImageID, '_wp_attachment_image_alt', true);
        $featuredImageTitle = get_the_title($featuredImageID);

        $featuredImage = [
            'src' => $featuredImageSRC ? $featuredImageSRC : null,
            'alt' => $featuredImageAlt ? $featuredImageAlt : null,
            'title' => $featuredImageTitle ? $featuredImageTitle : null
        ];

        return \apply_filters('Modularity/Helper/Post/FeaturedImage', $featuredImage);
    }

    /**
     * Get a list of terms to display on each inlay
     *
     * @param integer $postId           The post identifier
     * @param boolean $includeLink      If a link should be included or not
     * @return array                    A array of terms to display
     */
    protected static function getPostTerms($postId, $includeLink = false)
    {
        $taxonomies = get_field('archive_' . get_post_type($postId) . '_post_taxonomy_display', 'options');

        $termsList = [];

        if (is_array($taxonomies) && !empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $terms = wp_get_post_terms($postId, $taxonomy);

                if (!empty($terms)) {
                    foreach ($terms as $term) {

                        $item = [];

                        $item['label'] = strtolower($term->name);

                        if ($includeLink) {
                            $item['href'] = get_term_link($term->term_id);
                        }

                        $termsList[] = $item;
                    }
                }
            }
        }

        return \apply_filters('Modularity/Helper/Post/getPostTerms', $termsList, $postId);
    }

    /**
     * Get current page ID
     */
    public static function getPageID(): int
    {
        //Page for posttype archive mapping result
        if (is_post_type_archive()) {
            if ($pageId = get_option('page_for_' . get_post_type())) {
                return $pageId;
            }
        }

        //Get the queried page
        if (get_queried_object_id()) {
            return get_queried_object_id();
        }

        //Return page for frontpage (fallback)
        if ($frontPageId = get_option('page_on_front')) {
            return $frontPageId;
        }

        //Return page blog (fallback)
        if ($frontPageId = get_option('page_for_posts')) {
            return $frontPageId;
        }

        return 0;
    }
}
