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

    public function script()
    {
        if (!$this->hasModule()) {
            return;
        }

        wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js', array('jquery'), '1.10.11');

        add_action('wp_footer', function () {
            echo "<script>
                jQuery(document).ready(function ($) {
                    $('.datatable').DataTable({
                        dom: \"lf<'clearfix'>\" +
                               \"tr\" +
                               \"ip<'clearfix'>\",
                        oLanguage: {
                            sSearch: ''
                        }
                    });
                });
            </script>";
        });
    }
}
