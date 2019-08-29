<?php

if( function_exists('acf_add_local_field_group') ) {
    acf_add_local_field_group(array(
	'key' => 'group_5b2908801bd05',
	'title' => __('Sidebar & Module Restrictions', 'modularity'),
	'fields' => generateFields(),
	'location' => array(
	    array(
		array(
		    'param' => 'options_page',
		    'operator' => '==',
		    'value' => 'acf-options-restrictive-options',
		),
	    ),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
    ));

    acf_add_local_field_group(array(
	'key' => 'group_5b2908801bd08',
	'title' => __('Settings', 'modularity'),
	'fields' => array(
	    array(
		'key' => 'field_acf_module_areas',
		'label' => __('Template Areas', 'modularity'),
		'name' => 'acf_module_areas',
		'type' => 'true_false',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => array(
		    'width' => '',
		    'class' => '',
		    'id' => '',
		),
		'message' => __(
                    'Checking this will override the default module area settings.',
                    'modularity'
                ),
		'default_value' => 0,
		'ui' => 1,
		'ui_on_text' => '',
		'ui_off_text' => '',
	    ),
	    array(
		'key' => 'field_module_restrictions',
		'label' => __('Module Restrictions', 'modularity'),
		'name' => 'acf_module_restrictions',
		'type' => 'true_false',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => array(
		    'width' => '',
		    'class' => '',
		    'id' => '',
		),
		'message' => __(
                    'Checking this will filter the default module settings for each template.',
                    'modularity'
                ),
		'default_value' => 0,
		'ui' => 1,
		'ui_on_text' => '',
		'ui_off_text' => '',
	    )
	),
	'location' => array(
	    array(
		array(
		    'param' => 'options_page',
		    'operator' => '==',
		    'value' => 'acf-options-restrictive-options',
		),
	    ),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
        'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
    ));
}

/**
 * Get registered sidebars and returns an assoc
 *
 * @return array Sidebar Options
 */
function getSidebars()
{
    global $wp_registered_sidebars;
    $sidebar_options = array();

    foreach ( $wp_registered_sidebars as $sidebar ){
        $sidebar_options[$sidebar['id']] = $sidebar['name'];
    }
    return $sidebar_options;
}

/**
 * Get available templates of the theme
 *
 * @return array Available Templates
 */
function getTemplates()
{
    $coreTemplates = \Modularity\Helper\Wp::getCoreTemplates();
    $coreTemplates = apply_filters('Modularity/CoreTemplatesInTheme', $coreTemplates);
    $customTemplates = wp_get_theme()->get_page_templates();

    $templates = array_merge($coreTemplates, $customTemplates);

    return $templates;
}

/**
 * Generate or load ACF option fields for each available template
 *
 * @return array Option Fields corresponding to each template
 */
function generateFields() {
    $template_fields = array();
    $all_sidebars = getSidebars();
    $available_modules = \Modularity\ModuleManager::$enabled;

    foreach(getTemplates() as $id => $template) {
        $id = str_replace('.blade.php', '', $id);
        $accordion = array(
            'key' => 'field_' . $id,
            'label' => __($template, 'modularity'),
            'name' => '',
            'type' => 'accordion',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'open' => 0,
            'multi_expand' => 0,
            'endpoint' => 0,
        );
        array_push($template_fields, $accordion);

        $active_sidebars = array(
            'key' => 'field_' . $id . '-sidebars',
            'label' => __('Active Sidebars', 'modularity'),
            'name' => $id . '_active_sidebars',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_acf_module_areas',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => $all_sidebars,
            'default_value' => array(
            ),
            'allow_null' => 0,
            'multiple' => 1,
            'ui' => 1,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        );
        array_push($template_fields, $active_sidebars);

        $template_sidebars = get_field($id . '_active_sidebars', 'option');
        if($template_sidebars) {
            foreach ($template_sidebars as $sidebar) {
                $sidebar_modules = array(
	            'key' => 'field_' . $id . $sidebar,
		    'label' => __($all_sidebars[$sidebar], 'modularity'),
		    'name' => $id . '-' . $sidebar,
		    'type' => 'select',
		    'instructions' => '',
		    'required' => 0,
		    'conditional_logic' => array(
			array(
			    array(
				'field' => 'field_module_restrictions',
				'operator' => '==',
				'value' => '1',
			    ),
			),
		    ),
		    'wrapper' => array(
			'width' => '',
			'class' => '',
			'id' => '',
		    ),
		    'choices' => $available_modules,
		    'default_value' => array(
		    ),
		    'allow_null' => 0,
		    'multiple' => 1,
		    'ui' => 1,
		    'return_format' => 'array',
		    'ajax' => 0,
		    'placeholder' => '',
		);
		array_push($template_fields, $sidebar_modules);
	    }
	}
    }
    return $template_fields;
}
