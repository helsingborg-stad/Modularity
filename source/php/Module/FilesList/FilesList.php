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
        $data['columnData'] = $this->prepareColumnData($files);
        $data['headings'] = $this->prepareHeadings(['File', 'Description']);
        $data['showFilters'] = !empty(get_field('show_filter', $this->ID));
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->post_type, $this->args));

        return $data;
    }


    private function prepareHeadings($headings) {
        foreach($headings as &$heading) {
            
            $heading = translate($heading, 'modularity');
        }
        return $headings;
    }

    private function prepareColumnData()
    {
        $files = get_field('file_list', $this->ID);
        $columnData = [];

        foreach ($files as $item) {
            $columnData['href'] = $item['file']['url'];

            $columnData[] = [
                'columns' => 
                [
                    $item['file']['title'],
                    $item['file']['description']
                ],
                'href' => $item['file']['url']
            ];
        }

        return $columnData;
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

