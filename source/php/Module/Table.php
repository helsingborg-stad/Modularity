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
}
