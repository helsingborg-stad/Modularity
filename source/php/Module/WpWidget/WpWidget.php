<?php

namespace Modularity\Module\WpWidget;

class WpWidget extends \Modularity\Module
{

    private static $widgetIndexList;

    public function __construct()
    {

        //Register widget
        $this->register(
            'wpwidget',
            'Wordpress Widget',
            'Wordpress Widgets',
            'Outputs a default widget in WordPress',
            array('editor')
        );

        //Valid names
        self::$widgetIndexList = array(
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

        //Add options to list of selectable widgets
        add_filter('acf/load_field/name=mod_standard_widget_type', array($this, 'addWidgetOptionsList'));
    }

    public static function displayWidget($widget, $instance = array())
    {
        if (array_key_exists($widget, self::$widgetIndexList) && class_exists($widget)) {
            the_widget($widget, $instance);
        } else {
            echo "Error: Widget prohibited or class not found.";
        }
    }

    public function addWidgetOptionsList($field)
    {
        $field['choices'] = self::$widgetIndexList;
        return $field;
    }

    public static function createSettingsArray($widget_class, $post_id)
    {
        switch ($widget_class) {

            case "WP_Widget_Archives":
                return  array(
                            'title' => get_the_title($post_id),
                            'count' => '0', // Numeric boolean
                            'dropdown' => '' // Numeric boolean
                        );
                        break;

            case "WP_Widget_Calendar":
            case "WP_Widget_Meta":
            case "WP_Widget_Search":
                return  array(
                            'title' => get_the_title($post_id)
                        );
                        break;

            case "WP_Widget_Categories":
                return  array(
                            'title' => get_the_title($post_id),
                            'count' => '0', // Numeric boolean
                            'hierarchical' => '0', // Numeric boolean
                            'dropdown' => '' // Numeric boolean
                        );
                        break;

            case "WP_Widget_Pages":
                return  array(
                            'title' => get_the_title($post_id),
                            'sortby' => 'post_title',
                            'exclude' => null,
                        );
                        break;

            case "WP_Widget_Recent_Comments":
                return  array(
                            'title' => get_the_title($post_id),
                            'comments' => 5
                        );
                        break;

            case "WP_Widget_Recent_Posts":
                return  array(
                            'title' => get_the_title($post_id),
                            'comments' => 5
                        );
                        break;

            case "WP_Widget_RSS":
                return  array(
                            'title' => get_the_title($post_id),
                            'url' => 5, //rss url atom/rss-xml,
                            'items' => 4, //Max number of items to show
                            'show_summary' => true,
                            'show_author' => true,
                            'show_date' => true
                        );
                        break;
            case "WP_Widget_Tag_Cloud":
                return  array(
                            'title' => get_the_title($post_id),
                            'taxonomy' => 'post_tag'
                        );
                        break;
            default:
                return new WP_Error('missing_class', __("Missing a valid classname!", 'modularity'));
        }
    }
}
