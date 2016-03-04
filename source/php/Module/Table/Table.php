<?php

namespace Modularity\Module\Table;

class Table extends \Modularity\Module
{
    public function __construct()
    {

        //Register acf module
        $this->register(
            'table',
            __("Table", 'modularity-plugin'),
            __("Tables", 'modularity-plugin'),
            __("Outputs a flexible table with options.", 'modularity-plugin'),
            array(), //supports
            null, //icon
            'acf-dynamic-table-field/acf-anagram_dynamic_table_field.php' //included plugin
        );

        //Register stylesheets
        add_action('Modularity/Module/mod-table/enqueue', array($this, 'modAssets'));
    }

    public function modAssets()
    {
        wp_register_style('mod-table', MODULARITY_URL . '/dist/css/Table/assets/table.min.css', array(), '1.1.1');
        wp_enqueue_style('mod-table');
    }
}
