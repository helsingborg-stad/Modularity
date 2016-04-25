<?php

namespace Modularity;

class Widget extends \WP_Widget
{
    /**
     * Sets up widget names etc.
     */
    public function __construct()
    {
        $widgetOptions = array(
            'classname' => 'modularity-module',
            'description' => 'Inser a Modularity module',
        );

        parent::__construct('modularity-module', 'Modularity module', $widgetOptions);
    }

    /**
     * Outputs the widget content
     * @param  array $args     Widget arguments
     * @param  array $instance Widget instance
     * @return void
     */
    public function widget($args, $instance)
    {
        $display = new \Modularity\Display();
        $module = get_post($instance['module_id']);
        echo $display->outputModule($module, $args);
    }

    /**
     * Displays the widget form
     * @param  array $instance The widget instance
     * @return void
     */
    public function form($instance)
    {
        $moduleTypes = \Modularity\Module::$available;
        include MODULARITY_TEMPLATE_PATH . 'widget/form.php';
    }

    /**
     * Updates widget data
     * @param  array $newInstance The new widget instance
     * @param  array $oldInstance The old widget instance
     * @return array The instance to save
     */
    public function update($newInstance, $oldInstance)
    {
        return $newInstance;
    }
}
