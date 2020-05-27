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
}
