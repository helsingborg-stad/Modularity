<?php

namespace Modularity;

abstract class Options
{
    /**
     * Will be set to menu_slug in the register function
     * @var string
     */
    protected $slug = null;

    /**
     * The hook name to use with the WP Load action (load-{$screenHook})
     * @var string
     */
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

            // Set the slug
            $this->slug = $menuSlug;

            // Setup meta box support
            add_action('load-' . $this->screenHook, array($this, 'setupMetaBoxSupport'));
            add_action('load-' . $this->screenHook, array($this, 'save'));

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
     * Validates post save
     * @return boolean
     */
    public function isValidPostSave()
    {
        return isset($_POST['action']) && $_POST['action'] == 'modularity-options' && wp_verify_nonce($_POST['_wpnonce'], 'modularity-options');
    }

    /**
     * Saves the options
     * @return void
     */
    public function save()
    {
        if (!$this->isValidPostSave()) {
            return;
        }

        // Get the options
        $options = (isset($_POST['modularity-options'])) ? $_POST['modularity-options'] : array();

        // Update the options
        update_option($this->slug, $options);

        // All done, send notice
        $this->notice(__('Options saved successfully', 'modularity'), ['updated']);
    }

    /**
     * Sends a notice to the user
     * @param  string $message The noticce message
     * @param  array  $class   List of DOM classes to use on the notice
     * @return void
     */
    protected function notice($message, $class = array())
    {
        add_action('admin_notices', function () use ($message, $class) {
            $class = implode(' ', $class);
            echo '<div class="notice ' . $class . '"><p>' . $message . '</p></div>';
        });
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
        wp_enqueue_script('postbox');

        global $options;
        $options = get_option($this->slug);

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
