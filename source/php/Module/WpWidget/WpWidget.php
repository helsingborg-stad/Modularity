<?php

namespace Modularity\Module\WpWidget;

class WpWidget extends \Modularity\Module
{
    public function __construct()
    {

        //Register widget
        $this->register(
            'wpwidget',
            __('Wordpress Widgets', 'modularity'),
            __('Wordpress Widgets', 'modularity'),
            __('Outputs a default widget in WordPress'),
            array('editor')
        );

        //Add options to list of selectable widgets
        add_filter('acf/load_field/name=mod_standard_widget_type', array($this, 'addWidgetOptionsList'));
        add_filter('acf/load_field/name=wp_widget_tag_cloud_taxonomy', array($this, 'tagCloudTaxonomies'));
    }

    /**
     * Displays the widget
     * @param  string $widget   The widget type
     * @param  array  $instance The widget instance
     * @return void
     */
    public static function displayWidget($widget, $instance = array())
    {
        if (array_key_exists($widget, \Modularity\Module\WpWidget\WpWidget::getWidgetIndexList()) && class_exists($widget)) {
            the_widget($widget, $instance);
        } else {
            echo "Error: Widget prohibited or class not found.";
        }
    }

    /**
     * Widget options list
     * @param array $field
     */
    public function addWidgetOptionsList($field)
    {
        $field['choices'] = $this->getWidgetIndexList();
        return $field;
    }

    /**
     * Add taxonomies to tag cloud widget form
     * @param  array $field
     * @return array
     */
    public function tagCloudTaxonomies($field)
    {
        $taxonomies = get_taxonomies(array('show_tagcloud' => true), 'object');

        foreach ($taxonomies as $key => $value) {
            $field['choices'][$key] = $value->labels->name;
        }

        return $field;
    }

    /**
     * Widget fields
     * @param  string $widget_class Widget class
     * @param  integer $post_id     Post id
     * @return arrat                Fields
     */
    public static function createSettingsArray($widget_class, $post_id)
    {
        switch ($widget_class) {
            case "WP_Widget_Archives":
                $settings = array(
                            'title' => get_the_title($post_id),
                            'count' => get_field('wp_widget_archive_count', $post_id),
                            'dropdown' => get_field('wp_widget_archive_dropdown', $post_id)
                        );
                        break;

            case "WP_Widget_Categories":
                $settings = array(
                            'title' => get_the_title($post_id),
                            'count' => get_field('wp_widget_cat_count', $post_id),
                            'hierarchical' => get_field('wp_widget_cat_hierarchical', $post_id),
                            'dropdown' => get_field('wp_widget_cat_dropdown', $post_id)
                        );
                        break;

            case "WP_Widget_Pages":
                $settings = array(
                            'title' => get_the_title($post_id),
                            'sortby' => get_field('wp_widget_pages_sort_by', $post_id),
                            'exclude' => get_field('wp_widget_pages_exclude', $post_id)
                        );
                        break;

            case "WP_Widget_Recent_Comments":
                $settings = array(
                            'title' => get_the_title($post_id),
                            'comments' => get_field('wp_widget_comments_comments', $post_id)
                        );
                        break;

            case "WP_Widget_Recent_Posts":
                $settings = array(
                            'title' => get_the_title($post_id),
                            'number' => get_field('wp_widget_posts_number', $post_id)
                        );
                        break;

            case "WP_Widget_RSS":
                $settings = array(
                            'title' => get_the_title($post_id),
                            'url' => get_field('wp_widget_rss_url', $post_id), //rss url atom/rss-xml,
                            'items' => get_field('wp_widget_rss_items', $post_id), //Max number of items to show
                            'show_summary' => get_field('wp_widget_rss_summary', $post_id),
                            'show_author' => get_field('wp_widget_rss_author', $post_id),
                            'show_date' => get_field('wp_widget_rss_date', $post_id)
                        );
                        break;
            case "WP_Widget_Tag_Cloud":
                $settings = array(
                            'title' => get_the_title($post_id),
                            'taxonomy' => get_field('wp_widget_tag_cloud_taxonomy', $post_id)
                        );
                        break;
            default:
                $settings = array(
                            'title' => get_the_title($post_id)
                        );
        }

        //Allow modifications of the settings
        return apply_filters('Modularity/Module/WidgetSettings', $settings, $widget_class, $post_id);
    }
    public static function getWidgetIndexList() {
        //Valid names
        $widgetIndexList = array(
            'WP_Widget_Archives' => __("Archives", 'modularity'),
            'WP_Widget_Calendar' => __("Calendar", 'modularity'),
            'WP_Widget_Categories' => __("Categories", 'modularity'),
            'WP_Widget_Meta' => __("Meta", 'modularity'),
            'WP_Widget_Pages' => __("Pages", 'modularity'),
            'WP_Widget_Recent_Comments' => __("Recent comments", 'modularity'),
            'WP_Widget_Recent_Posts' => __("Recent posts", 'modularity'),
            'WP_Widget_RSS' => __("RSS", 'modularity'),
            'WP_Widget_Search' => __("Search", 'modularity'),
            'WP_Widget_Tag_Cloud' => __("Tag cloud", 'modularity')
        );
        //Allow modifications of the list
        return apply_filters('Modularity/Module/WidgetIndexList', $widgetIndexList);
    }
}
