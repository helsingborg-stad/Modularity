<?php

/*
Plugin Name: Post Type Select for Advanced Custom Fields
Plugin URI: https://github.com/Clark-Nikdel-Powell/Post-Type-Select-for-ACF
Description: Custom field definition for Advanced Custom Fields for a select box populated with post types.
Version: 1.1.0
Author: Glenn Welser
Author URI: https://clarknikdelpowell.com/agency/people/glenn
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Reference: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
load_plugin_textdomain('acf-posttype_select', false, dirname(plugin_basename(__FILE__)) . '/lang/');

// $version = 5 and can be ignored until ACF6 exists
function include_field_types_posttype_select($version)
{
    include_once('acf-posttype-select-v5.php');
}

add_action('acf/include_field_types', 'include_field_types_posttype_select');

// Include field type for ACF4
function register_fields_posttype_select()
{
    include_once('acf-posttype-select-v4.php');
}

add_action('acf/register_fields', 'register_fields_posttype_select');
