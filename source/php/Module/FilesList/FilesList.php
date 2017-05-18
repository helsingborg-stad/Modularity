<?php

namespace Modularity\Module\FilesList;

class FilesList extends \Modularity\Module
{
    public $slug = 'fileslist';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Files", 'modularity');
        $this->namePlural = __("Files", 'modularity');
        $this->description = __("Outputs a file archive.", 'modularity');
    }

    public function data() : array
    {
        $data = array();
        $data['listId'] = 'files_' . uniqid();
        $data['files'] = $this->prepareFiles(get_field('file_list', $this->ID));
        $data['columns'] = get_field('columns', $this->ID);
        $data['showFilters'] = is_null(get_field('show_filter', $this->ID)) || get_field('show_filter', $this->ID) === true;
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->post_type, $this->args));

        return $data;
    }

    public function prepareFiles($files)
    {
        foreach ($files as &$item) {
            $item['columns'] = array();

            foreach ($item['fields'] as $column) {
                $item['columns'][$column['key']] = $column['value'];
            }

            unset($item['fields']);
        }

        return $files;
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

