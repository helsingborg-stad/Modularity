<?php
/*
Plugin Name:    Acf image select
Description:    Radio buttons as images.
Version:        1.0
Author:         Niclas Norin
*/
namespace AcfImageSelect;

class AcfImageSelect
{
	/*
	*  Construct
	*
	*  @description:
	*  @since: 3.6
	*  @created: 1/04/13
	*/
	function __construct()
	{
		add_action('acf/include_field_types', array($this, 'register_field_type_image_select'));
		add_action('admin_enqueue_scripts', array( $this, 'register_admin_styles' ));
	}

	/**
 	 * Register fields and backend.
 	 */
    function register_field_type_image_select() {
        include_once('image-select.php');
    }

	/**
 	 * Registers and enqueues admin-specific styles.
 	 */
 	public function register_admin_styles() {
 		wp_enqueue_style('acf-image-select', plugins_url( 'acf-image-select.css', __FILE__  ));
 	}
}

new \AcfImageSelect\AcfImageSelect();

?>