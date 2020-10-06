<?php

namespace Modularity\Module\Table;

class Table extends \Modularity\Module
{
    public $slug = 'table';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Table", 'modularity');
        $this->namePlural = __("Tables", 'modularity');
        $this->description = __("Outputs a flexible table with options.", 'modularity');

        add_action('Modularity/Module/mod-table/enqueue', array($this, 'modAssets'));
        add_action('save_post', array($this, 'csvImport'), 999);

        //Remove html filter
        add_action('save_post', array($this, 'disableHTMLFiltering'), 5);
    }

    /**
     * Removes the filter of html & script data before save.
     * @var int
     */
    public function disableHTMLFiltering($postId) {
        
        //Bail early if not a script module save
        if(get_post_type($postId) !== "mod-" . $this->slug) {
            return; 
        }

        //Disable filter temporarirly
        add_filter('acf/allow_unfiltered_html', function($allow_unfiltered_html) {
            return true;
        });
    }

    public function data() : array
    {
        $data = get_fields($this->ID);
        $data['mod_table'] = self::unicodeConvert($data['mod_table']);
        $data['tableClasses'] = $this->getTableClasses($data);
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->post_type, $this->args));
        return $data;
    }

    public static function unicodeConvert($unicode)
    {
        $search = array('u00a5','u201e','u00b6','u2026','u00a4','u2013', 'u0152','u0160','u0161','ufffe','u20ac','u2026', 'u2020','	u201e','u201d','ufffe','u017d','u2122', 'u00c3', 'u00e2','u00e2u201au00ac', 'u00a9', 'u2030', 'u00c2u00b4', 'Äu02dc', 'u00db');
        $replace = array('å','ä','ö','Å','Ä','Ö', 'å','ä','ö','Å','Ä','Ö', 'å','ä','ö','Å','Ä','Ö', '', '”', '€','é','É', '´', "'", '€');

        return str_replace($search, $replace, $unicode);
    }

    public function getTableClasses($data)
    {
        $classes = '';

        if (isset($data['mod_table_classes']) && is_array($data['mod_table_classes'])) {
            $classes = $data['mod_table_classes'];

            if (isset($data['mod_table_size']) && !empty($data['mod_table_size'])) {
                $classes[] = $data['mod_table_size'];
            }

            $classes = array_unique($classes);
            $classes = implode(' ', $classes);
        }

        return $classes;
    }

    public function csvImport($post_id)
    {


        if (!isset($_POST['post_type']) || $_POST['post_type'] != $this->moduleSlug) {
            return;
        }

        if (get_field('mod_table_data_type', $post_id) != 'csv') {
            return;
        }

        ini_set('auto_detect_line_endings', true);


        $file = get_field('mod_table_csv_file', $post_id);
        $file = fopen($file['url'], 'r');

        $data = array();

        if (!$file) {
            wp_die(__('There was an error opening the selected .csv-file.'));
        }

        while (!feof($file)) {
            $row = fgetcsv($file, 0, ';');

            if (count($row) === 0) {
                continue;
            }

            foreach ($row as &$value) {
                $value = mb_convert_encoding($value, 'UTF-8', 'WWINDOWS-1255');
            }

            array_push($data, $row);
        }

        fclose($file);

        $data = array_filter($data);
        $data = json_encode($data);

        update_post_meta($post_id, 'mod_table', $data);
        update_post_meta($post_id, '_mod_table', 'field_5666a2ae23643');
    }

    public function modAssets()
    {
        wp_register_script('mod-table', MODULARITY_URL . '/dist/js/Table/assets/table.js', array(), '1.1.1', true);
        wp_enqueue_script('mod-table');

        wp_register_style('mod-table', MODULARITY_URL . '/dist/css/Table/assets/table.min.css', array(), '1.1.1');
        wp_enqueue_style('mod-table');
    }

    public function script()
    {
        if (!$this->hasModule()) {
            return;
        }

        wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js', array(), '1.10.11', true);
        wp_enqueue_script('datatables-init', MODULARITY_URL . '/dist/js/Table/assets/table-init.js', array(), '1.0.0', true);
        wp_localize_script('datatables-init', 'datatablesLang', array(
            'sEmptyTable' => __('No data available in table', 'modularity'),
            'sInfo' => __('Showing _START_ to _END_ of _TOTAL_ entries', 'modularity'),
            'sInfoEmpty' => __('Showing 0 to 0 of 0 entries', 'modularity'),
            'sInfoFiltered' => __('filtered from _MAX_ total entries', 'modularity'),
            'sLengthMenu' => __('Show _MENU_ entries', 'modularity'),
            'sLoadingRecords' => __('Loading...', 'modularity'),
            'sProcessing' => __('Processing...', 'modularity'),
            'sZeroRecords' => __('No matching records found', 'modularity'),
            'sFirst' => __('First', 'modularity'),
            'sLast' => __('Last', 'modularity'),
            'sNext' => __('Next', 'modularity'),
            'sPrevious' => __('Previous', 'modularity'),
            'sSortAscending' => __('activate to sort column ascending', 'modularity'),
						'sSortDescending' => __('activate to sort column descending', 'modularity'),
						'sSearchLabel' => __('Search', 'modularity')
        ));
    }

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
