<?php

namespace Modularity\Module\Latest;

class Latest extends \Modularity\Module
{
    public function __construct()
    {
        //Register
        $this->register(
            'latest',
            'Latest',
            'Latest',
            'Outputs posts in a given order',
            array(''),
            null,
            'acf-post-type-field/acf-posttype-select.php' //included plugin
        );

        //Filter select
        //add_filter('acf/load_field/name=filter_posts_by_category', array($this,'getTaxonomiesOptions'));
        //add_filter('acf/load_field/name=color', array($this,'getTaxonomiesOptions'));

        add_action('Modularity/Module/' . $this->moduleSlug . '/enqueue', array($this, 'enqueueScripts'));
        add_action('wp_ajax_get_taxonomy_types', array($this, 'getTaxonomyTypes'));
        add_action('wp_ajax_get_taxonomy_values', array($this, 'getTaxonomyValues'));
        add_action('wp_ajax_get_sortable_meta_keys', array($this, 'getSortableMetaKeys'));
    }

    public function getSortableMetaKeys()
    {
        if (!isset($_POST['posttype']) || empty($_POST['posttype'])) {
            echo '0';
            die();
        }

        $meta = \Modularity\Helper\Post::getPosttypeMetaKeys($_POST['posttype']);

        $response = array(
            'meta_keys' => $meta,
            'curr' => get_field('sorted_by', $_POST['post'])
        );

        echo json_encode($response);
        die();
    }

    public function getTaxonomiesOptions($field)
    {
        $field['choices'] = array();
        $choices = get_taxonomies();

        if (is_array($choices)) {
            foreach ($choices as $choice) {
                $terms = get_terms($choice);
                $field['choices'][ $choice ] = $choice;
            }
        }

        return $field;
    }

    public function getTaxonomyTypes()
    {
        if (!isset($_POST['posttype']) || empty($_POST['posttype'])) {
            echo '0';
            die();
        }

        $post = $_POST['post'];

        $result = array(
            'types' => get_object_taxonomies($_POST['posttype'], 'object'),
            'curr' => get_field('filter_posts_taxonomy_type', $post)
        );

        echo json_encode($result);
        die();
    }

    /**
     * AJAX CALLBACK
     * Gets a taxonomies available values
     * @return void
     */
    public function getTaxonomyValues()
    {
        if (!isset($_POST['tax']) || empty($_POST['tax'])) {
            echo '0';
            die();
        }

        $taxonomy = $_POST['tax'];
        $post = $_POST['post'];

        $result = array(
            'tax' => get_terms($taxonomy),
            'curr' => get_field('filter_posts_by_tag', $post)
        );

        echo json_encode($result);
        die();
    }

    public function enqueueScripts()
    {
        wp_enqueue_script('mod-latest-taxonomy', MODULARITY_URL . '/dist/js/Latest/assets/mod-latest-taxonomy.js', array(), '1.0.0', true);

        add_action('admin_head', function () {
            global $post;

            if (empty($post)) {
                return;
            }

            echo '<script>var modularity_current_post_id = ' . $post->ID . ';</script>';
        });
    }
}
