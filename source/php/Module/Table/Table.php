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
                            \"sEmptyTable\":     \"" . __('No data available in table', 'modularity') . "\",
                            \"sInfo\":           \"" . __('Showing _START_ to _END_ of _TOTAL_ entries', 'modularity') . "\",
                            \"sInfoEmpty\":      \"" . __('Showing 0 to 0 of 0 entries', 'modularity') . "\",
                            \"sInfoFiltered\":   \"(" . __('filtered from _MAX_ total entries', 'modularity') . ")\",
                            \"sInfoPostFix\":    \"\",
                            \"sInfoThousands\":  \",\",
                            \"sLengthMenu\":     \"" . __('Show _MENU_ entries', 'modularity') . "\",
                            \"sLoadingRecords\": \"" . __('Loading...', 'modularity') . "\",
                            \"sProcessing\":     \"" . __('Processing...', 'modularity') . "\",
                            \"sSearch\":         \"\",
                            \"sZeroRecords\":    \"" . __('No matching records found', 'modularity') . "\",
                            \"oPaginate\": {
                                \"sFirst\":    \"" . __('First', 'modularity') . "\",
                                \"sLast\":     \"" . __('Last', 'modularity') . "\",
                                \"sNext\":     \"" . __('Next', 'modularity') . "\",
                                \"sPrevious\": \"" . __('Previous', 'modularity') . "\"
                            },
                            \"oAria\": {
                                \"sSortAscending\":  \": " . __('activate to sort column ascending', 'modularity') . "\",
                                \"sSortDescending\": \": " . __('activate to sort column descending', 'modularity') . "\"
                            }
                        }
                    });
                });
            </script>";
        });
    }
}
