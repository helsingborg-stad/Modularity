<?php

namespace Modularity\Helper;

class Post
{
    /**
     * Gets the post template of the current editor page
     * @return string Template slug
     */
    public static function getPostTemplate($id = null)
    {
        if (self::isArchive()) {
            global $archive;
            return 'archive-' . get_post_type();
        }

        if (is_home()) {
            return 'archive-home';
        }

        global $post;
        if (!$post && isset($_GET['id'])) {
            $post = get_post($_GET['id']);
        }

        $checkPost = $post;

        if (is_numeric($id)) {
            $checkPost = get_post($id);
        }

        $template = get_page_template_slug($post->ID);

        if (!$template) {
            $template = self::detectCoreTemplate($post);
        }

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

        if (defined('DOING_AJAX') && DOING_AJAX) {
            $archive = !is_numeric($_POST['id']) ? $_POST['id'] : '';
        }

        return $archive != '' || is_archive() || is_search();
    }
}
