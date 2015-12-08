<?php

namespace Modularity\Module;

class Table extends \Modularity\Module
{
    public function __construct()
    {
        $this->register(
            'table',
            __("Table",'modularity-plugin'),
            __("Tables",'modularity-plugin'),
            __("Outputs a flexible table with options.",'modularity-plugin'),
            array(), //supports
            null, //icon 
            'acf-dynamic-table-field/acf-anagram_dynamic_table_field.php' //included plugin
        );
    }
    
    public function acfFields()
    {
	 	if( function_exists('acf_add_local_field_group') ):
			
			acf_add_local_field_group(array (
				'key' => 'group_5666a2a71d806',
				'title' => 'Table Editor',
				'fields' => array (
					array (
						'key' => 'field_5666a2ae23643',
						'label' => 'Table',
						'name' => 'mod_table',
						'type' => 'dynamic_table',
						'instructions' => 'Enter your table contents, and select appearance and functionality options below. ',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'tableclass' => 'modularity-table',
						'maxrows' => '',
						'disable_sort' => 0,
						'fixed_columns' => 0,
						'default_headers' => '',
						'default_header' => '',
						'readonly' => 0,
						'disabled' => 0,
						'sub_fields' => array (
						),
					),
					array (
						'key' => 'field_5666a3e0d2d29',
						'label' => 'Enable pagination',
						'name' => 'mod_table_pagination',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => 55,
							'class' => '',
							'id' => '',
						),
						'message' => 'Yes, use pagination on this table',
						'default_value' => 1,
					),
					array (
						'key' => 'field_5666a59673385',
						'label' => 'Number of posts/page',
						'name' => 'mod_table_pagination_count',
						'type' => 'number',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5666a3e0d2d29',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => 45,
							'class' => '',
							'id' => '',
						),
						'default_value' => 10,
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => 1,
						'max' => 500,
						'step' => '',
						'readonly' => 0,
						'disabled' => 0,
					),
					array (
						'key' => 'field_5666a459d2d2a',
						'label' => 'Enable search',
						'name' => 'mod_table_search',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => 55,
							'class' => '',
							'id' => '',
						),
						'message' => 'Yes, use search on this table',
						'default_value' => 1,
					),
					array (
						'key' => 'field_5666a63a48379',
						'label' => 'Search query',
						'name' => 'mod_table_search_query',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5666a459d2d2a',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array (
							'width' => 45,
							'class' => '',
							'id' => '',
						),
						'default_value' => 'Search in list',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
						'readonly' => 0,
						'disabled' => 0,
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'mod-table',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			));
			
		endif;   
		
	}
    
}
