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
            'WP_Widget_Links' => __("Links", 'modularity'),
            'WP_Widget_Meta' => __("Meta", 'modularity'),
            'WP_Widget_Pages' => __("Pages", 'modularity'),
            'WP_Widget_Recent_Comments' => __("Recent comments", 'modularity'),
            'WP_Widget_Recent_Posts' => __("Recent posts", 'modularity'),
            'WP_Widget_RSS' => __("RSS", 'modularity'),
            'WP_Widget_Search' => __("Search", 'modularity'),
            'WP_Widget_Tag_Cloud' => __("Tag cloud", 'modularity'),
            'WP_Nav_Menu_Widget' => __("Navigation menu", 'modularity')
        );

        //Add options to list of selectable widgets
        add_filter('acf/load_field/name=mod_standard_widget_type', array($this, 'addWidgetOptionsList'));
    }

    public static function displayWidget($widgetKey, $args, $instance = array() )
    {
        echo $widgetKey;
        if (array_key_exists($widgetKey, self::$widgetIndexList) && class_exists($widgetKey)) {
            $widgetInstance = new $widgetKey;
            $widgetInstance->widget($args, $instance);
        } else {
            echo "Error: Widget prohibited or class not found.";
        }
    }

    public function addWidgetOptionsList($field)
    {
        $field['choices'] = self::$widgetIndexList;
        return $field;
    }
}
