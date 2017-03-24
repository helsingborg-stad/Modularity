<?php

namespace Modularity;

class Module
{
    /**
     * The modules slug (id)
     * @var boolean/string "false" if not set otherwise string id
     */
    public $moduleSlug = false;

    /**
     * Indicates wheather the module is deprecated or not
     * @var boolean
     */
    public $isDeprecated = false;

    /**
     * ACTION: Modularity/Module/<MODULE SLUG>/enqueue
     * Enqueue assets (css and/or js) to the add/edit pages of the given module
     * @return void
     */
    public function enqueue()
    {
        if ($this->isAddOrEditOfPostType()) {
            do_action('Modularity/Module/' . $this->moduleSlug . '/enqueue');
        }
    }

    /**
     * (PLACEHOLDER) Enqueue styles
     * @return void
     */
    public function style()
    {
    }

    /**
     * (PLACEHOLDER) Enqueue scripts
     * @return void
     */
    public function script()
    {
    }

    /**
     * Checks if a current page/post has module(s) of this type
     * @return boolean
     */
    protected function hasModule()
    {
        global $post;

        $postId = null;
        $modules = array();
        $archiveSlug = \Modularity\Helper\Wp::getArchiveSlug();

        if ($archiveSlug) {
            $postId = $archiveSlug;
        } elseif (isset($post->ID)) {
            $postId = $post->ID;
        } else {
            return apply_filters('Modularity/hasModule', false, null);
        }

        $modules = \Modularity\Editor::getPostModules($postId);
        $modules = json_encode($modules);

        return apply_filters('Modularity/hasModule', strpos($modules, '"post_type":"' . $this->moduleSlug . '"') == true, $archiveSlug);
    }

    /**
     * Check if current page is add new/edit post
     * @return boolean
     */
    protected function isAddOrEditOfPostType()
    {
        global $current_screen;

        return $current_screen->base == 'post'
                && $current_screen->id == $this->moduleSlug
                && (
                    $current_screen->action == 'add' || (isset($_GET['action']) && $_GET['action'] == 'edit')
                );
    }

    /**
     * Registers a Modularity module
     * @param  string $slug         Module id suffix (will be prefixed with constant MODULE_PREFIX)
     * @param  string $nameSingular Singular name of the module
     * @param  string $namePlural   Plural name of the module
     * @param  string $description  Description of the module
     * @param  array  $supports     Which core post type fileds this module supports
     * @return string               The prefixed module id/slug
     */
    protected function register($slug, $nameSingular, $namePlural, $description, $supports = array(), $icon = null, $plugin = null, $cache_ttl = 0, $hideTitle = false)
    {
        \Modularity\App::$moduleManager->registerModule(
            $slug,
            $nameSingular,
            $namePlural,
            $description,
            $supports,
            $icon,
            $plugin,
            $cache_ttl,
            $hideTitle,
            $this
        );
    }
}
