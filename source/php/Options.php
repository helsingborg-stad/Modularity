<?php

namespace Modularity;

abstract class Options
{
    protected $screenHook = null;

    /**
     * Registers an options page
     * @param  string      $pageTitle  Page title
     * @param  string      $menuTitle  Menu title
     * @param  string      $capability Capability needed
     * @param  string      $menuSlug   Menu slug
     * @param  strin/array $function   Callback function for content
     * @param  string      $iconUrl    Menu icon
     * @param  integer     $position   Menu position
     * @return void
     */
    public function register($pageTitle, $menuTitle, $capability, $menuSlug, $parent = null, $iconUrl = null, $position = null)
    {
        add_action('admin_menu', function () use ($parent, $pageTitle, $menuTitle, $capability, $menuSlug, $iconUrl, $position) {
            // Add the menu page
            if (!$parent) {
                $this->screenHook = add_menu_page(
                    $pageTitle,
                    $menuTitle,
                    $capability,
                    $menuSlug,
                    array($this, 'optionPageTemplate'),
                    $iconUrl,
                    $position
                );
            } else {
                $this->screenHook = add_submenu_page(
                    $parent,
                    $pageTitle,
                    $menuTitle,
                    $capability,
                    $menuSlug,
                    array($this, 'optionPageTemplate')
                );
            }

            // Setup meta box support
            add_action('load-' . $this->screenHook, array($this, 'setupMetaBoxSupport'));

            // Hook to add the metaboxes
            add_action('add_meta_boxes_' . $this->screenHook, array($this, 'addMetaBoxes'));
        });
    }

    /**
     * This function should be used to add the desiered meta boxes
     * Override it in your options class
     */
    public function addMetaBoxes()
    {
        return true;
    }

    /**
     * Saves the options
     * @return void
     */
    public function save()
    {
        $options = (isset($_POST['modularity-options'])) ? $_POST['modularity-options'] : array();
        var_dump("GOD SAVE THE DATA", $options);
    }

    /**
     * Add metabox support to the options page
     * @return void
     */
    public function setupMetaBoxSupport()
    {
        do_action('add_meta_boxes_' . $this->screenHook, null);
        do_action('add_meta_boxes', $this->screenHook, null);

        add_screen_option('layout_columns', array('max' => 2, 'default' => 2));
    }

    /**
     * Renders the option page markup template
     * @return void
     */
    public function optionPageTemplate()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'modularity-options' && wp_verify_nonce($_POST['_wpnonce'], 'modularity-options')) {
            $this->save();
        }

        wp_enqueue_script('postbox');

        // Load template file
        require_once MODULARITY_TEMPLATE_PATH . 'options/modularity-options.php';
    }

    /**
     * Get input field name (modularitu-options[$name])
     * The $name will be the options key later on
     *
     * @param  string $name Desired field name
     * @return string       The full field name
     */
    protected function getFieldName($name)
    {
        return 'modularity-options[' . $name . ']';
    }
}
