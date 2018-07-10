<?php

namespace Modularity\Options;

class General extends \Modularity\Options
{
    public function __construct()
    {
        $this->register(
            $pageTitle = __('Modularity Options', 'modularity'),
            $menuTitle = __('Options', 'modularity'),
            $capability = 'administrator',
            $menuSlug = 'modularity-options',
            $iconUrl = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1NC44NDkiIGhlaWdodD0iNTQuODQ5IiB2aWV3Qm94PSIwIDAgNTQuODQ5IDU0Ljg0OSI+PHBhdGggZD0iTTU0LjQ5NyAzOS42MTRsLTEwLjM2My00LjQ5LTE0LjkxNyA1Ljk2OGMtLjUzNy4yMTQtMS4xNjUuMzItMS43OTMuMzItLjYyNyAwLTEuMjU0LS4xMDUtMS43OS0uMzJsLTE0LjkyLTUuOTY3TC4zNSAzOS42MTVjLS40Ny4yMDItLjQ2Ni41MjMuMDEuNzE1bDI2LjIgMTAuNDhjLjQ3Ny4xOSAxLjI1LjE5IDEuNzMgMGwyNi4xOTgtMTAuNDhjLjQ3Ni0uMTkuNDgtLjUxMy4wMS0uNzE2eiIvPjxwYXRoIGQ9Ik01NC40OTcgMjcuNTEybC0xMC4zNjQtNC40OS0xNC45MTYgNS45NjVjLS41MzYuMjE1LTEuMTY1LjMyLTEuNzkyLjMyYTQuODk4IDQuODk4IDAgMCAxLTEuNzkzLS4zMkwxMC43MTQgMjMuMDIuMzUgMjcuNTEzYy0uNDcuMjAzLS40NjYuNTIzLjAxLjcxNmwyNi4yIDEwLjQ3OGMuNDc3LjE5IDEuMjUuMTkgMS43MyAwbDI2LjE5OC0xMC40OGMuNDc2LS4xOS40OC0uNTEuMDEtLjcxNHoiLz48cGF0aCBkPSJNLjM2IDE2LjEyNWwxMy42NjMgNS40NjUgMTIuNTM3IDUuMDE1Yy40NzcuMTkgMS4yNS4xOSAxLjczIDBsMTIuNTQtNS4wMTYgMTMuNjU4LTUuNDY0Yy40NzctLjE5LjQ4LS41MS4wMS0uNzE2TDI4LjI3OCA0LjA0OGMtLjQ3Mi0uMjA0LTEuMjM3LS4yMDQtMS43MSAwTC4zNTIgMTUuNDFjLS40Ny4yMDQtLjQ2Ni41MjUuMDEuNzE1eiIvPjwvc3ZnPg==',
            $position = 1
        );

        // Add search page modules link to Moduliarty
        add_action('admin_menu', function () {
            add_submenu_page(
                'modularity',
                __('Search page modules', 'modularity'),
                __('Search page modules', 'modularity'),
                'edit_posts',
                'options.php?page=modularity-editor&id=search'
            );
        });

        acf_add_options_sub_page(array(
            'page_title'    => __('Restrictive Options', 'modularity'),
            'menu_title'    => __('Restrictive Options', 'modularity'),
            'parent_slug'   => 'modularity',
            'capability'    => 'administrator',
            'redirect'      => false,
            'icon_url'      => ''
        ));

        /**
         * Initialize ACF fields for restrictive options. This needs to be
         * called after the Add() action in the municipio template helper in
         * order to find custom templates. (Thereby the priority of 1000).
         */
        add_action(
            'init',
            function() {
                $acfPath = 'source/php/Options/AcfFields/acf-module-restrictions.php';
	        include MODULARITY_PATH . $acfPath;
	    },
	    1000
        );

	if(get_field('acf_module_restrictions', 'option')) {
	    add_filter(
		'Modularity/Editor/SidebarIncompability',
		array($this, 'sidebarIncompatibility'),
		20,
		2
	    );
	    add_action('edit_form_top', function () {
		\Modularity\ModuleManager::$enabled = $this->moduleRestriction();
	    });
	}
    }

    /**
     * Filters the sidebar incompatibility list based on the restrictive
     * options page.
     *
     * @return array Module Specification
     */
    public function sidebarIncompatibility($moduleSpecification, $modulePostType) {
        $template = \Modularity\Helper\Post::getPostTemplate(null, true);
        $template_sidebars = get_field($template . '_active_sidebars', 'option');

	if($template_sidebars) {
	    $sidebarIncompatibilities = array_flip($template_sidebars);

	    foreach($template_sidebars as $sidebar) {
		$modules = get_field($template . '-' . $sidebar, 'option');
		foreach($modules as $module) {
		    if($modulePostType == $module['label']) {
			unset($sidebarIncompatibilities[$sidebar]);
		    }
		}
	    }
	    $moduleSpecification['sidebar_incompability'] = array_keys($sidebarIncompatibilities);
	}
	return $moduleSpecification;
    }

    /**
     * Decides what modules are available for the current template
     * based on the restrictive options page (if enabled).
     *
     * @return array Enabled modules
     */
    public function moduleRestriction() {
	$enabledMods = array();
	$template = \Modularity\Helper\Post::getPostTemplate(null, true);
	$sidebars = get_field($template . '_active_sidebars', 'option');

	if(count($sidebars) > 0) {
	    foreach($sidebars as $sidebar) {
		$modules = get_field($template . '-' . $sidebar, 'option');
		if($modules) {
		    foreach($modules as $module) {
			array_push($enabledMods, $module['label']);
		    }
		}
	    }
	}
	return $enabledMods;
    }

    /**
     * Adds meta boxes to the general options page
     * @return void
     */
    public function addMetaBoxes()
    {
	// Publish
	add_meta_box(
	    'modularity-mb-publish',
	    __('Save options', 'modularity'),
	    function () {
		$templatePath = \Modularity\Helper\Wp::getTemplate('publish', 'options/partials');
		include $templatePath;
	    },
	    $this->screenHook,
	    'side'
	);

	// Core options
	add_meta_box(
	    'modularity-mb-core-options',
	    __('Core options', 'modularity'),
	    function () {
		$templatePath = \Modularity\Helper\Wp::getTemplate('core-options', 'options/partials');
		include $templatePath;
	    },
	    $this->screenHook,
	    'normal'
	);

	if (has_action('Modularity/Options/Module')) {
	    add_meta_box(
		'modularity-mb-module-options',
		__('Module options', 'modularity'),
		function () {
		    do_action('Modularity/Options/Module');
		},
		$this->screenHook,
		'normal'
	    );
	}

	// Modules
	add_meta_box(
	    'modularity-mb-post-types',
	    __('Post types', 'modularity'),
	    array($this, 'metaBoxPostTypes'),
	    $this->screenHook,
	    'normal'
	);

	// Templates and areas
	add_meta_box(
	    'modularity-mb-template-areas',
	    __('Template areas', 'modularity'),
	    array($this, 'metaBoxTemplateAreas'),
	    $this->screenHook,
	    'normal'
	);

	// Modules
	add_meta_box(
	    'modularity-mb-modules',
	    __('Modules', 'modularity'),
	    array($this, 'metaBoxModules'),
	    $this->screenHook,
	    'normal'
	);
    }

    /**
     * Template areas meta box
     * @return void
     */
    public function metaBoxTemplateAreas()
    {
	global $wp_registered_sidebars;
	global $modularityOptions;

	usort($wp_registered_sidebars, function ($a, $b) {
	    return $a['name'] > $b['name'];
	});

	$coreTemplates = \Modularity\Helper\Wp::getCoreTemplates();
	$coreTemplates = apply_filters('Modularity/CoreTemplatesInTheme', $coreTemplates);
	$customTemplates = get_page_templates();
	$templates = array_merge($coreTemplates, $customTemplates);

	include MODULARITY_TEMPLATE_PATH . 'options/partials/modularity-template-areas.php';
    }

    /**
     * Metabox content: Post types
     * @return void
     */
    public function metaBoxPostTypes()
    {
	global $modularityOptions;
	$enabled = isset($modularityOptions['enabled-post-types']) && is_array($modularityOptions['enabled-post-types']) ? $modularityOptions['enabled-post-types'] : array();

	$postTypes = array_filter(get_post_types(), function ($item) {
	    $disallowed = array_merge(
		array_keys(\Modularity\ModuleManager::$available),
		array(
		    'attachment',
		    'revision',
		    'nav_menu_item',
		    'custom_css',
		    'customize_changeset'
		)
	    );

	    if (in_array($item, $disallowed)) {
		return false;
	    }

	    if (substr($item, 0, 4) == 'acf-') {
		return false;
	    }

	    return true;
	});

	include MODULARITY_TEMPLATE_PATH . 'options/partials/modularity-post-types.php';
    }

    /**
     * Metabox content: Modules
     * @return void
     */
    public function metaBoxModules()
    {
	$available = \Modularity\ModuleManager::$available;

	uasort($available, function ($a, $b) {
	    return strcmp($a['labels']['name'], $b['labels']['name']);
	});

	global $modularityOptions;
	$enabled = isset($modularityOptions['enabled-modules']) && is_array($modularityOptions['enabled-modules']) ? $modularityOptions['enabled-modules'] : array();

	$templatePath = \Modularity\Helper\Wp::getTemplate('modules', 'options/partials');
	include $templatePath;
    }
}
