<?php

namespace Modularity\Module\FilesList;

class FilesList extends \Modularity\Module
{
    public $slug = 'fileslist';
    public $supports = [];

    public function init()
    {
        $this->nameSingular = __("Files", 'modularity');
        $this->namePlural = __("Files", 'modularity');
        $this->description = __("Outputs a file archive.", 'modularity');
    }

    /**
     * Magic function for collecting template data.
     *
     * @return array Data for template.
     */
    public function data(): array
    {
        $data = [];
        $data['rows'] = $this->prepareFileData();
        $data['classes'] = implode(
            ' ',
            apply_filters(
                'Modularity/Module/Classes',
                array('c-card--default'),
                $this->post_type,
                $this->args
            )
        );
        $data['isFilterable'] = get_field('show_filter', $this->ID);
        $data['filterAboveCard'] = get_field('filter_above_card', $this->ID);
        $data['uID'] = uniqid();
        $data['ID'] = $this->ID;

        return $data;
    }

    /**
     * Prepare array of file data into rows of the list.
     *
     * @return array All file data.
     */
    private function prepareFileData()
    {
        $files = get_field('file_list', $this->ID);
        $rows = [];

        foreach ($files as $key => $item) {
            $rows[$key] = [
                'title' => $this->filenameToTitle($item['file']['title'] ?? ''),
                'href' => $item['file']['url'] ?? '',
                'description' => $item['file']['description'] ?? '',
                'type' => pathInfo($item['file']['url'], PATHINFO_EXTENSION),
                'filesize' => $this->formatBytes($item['file']['filesize']),
                'icon' => $this->getIconClass($item['file']['subtype'])
            ];
        }

        return $rows;
    }

    /**
     * Make filename more readable, when alternative not found.
     *
     * @param string $filename
     * @return string
     */
    private function filenameToTitle(string $filename): string
    {
        if ($filename == sanitize_title($filename)) {
            $filename = str_replace(['-', '_'], [' ', ' '], $filename);
        }

        return ucfirst($filename);
    }

    /**
     * Get icon class from type
     *
     * @return string
     */
    private function getIconClass($type): string
    {
        switch ($type) {
            case 'mp4':
            case 'mov':
            case 'wmv':
            case 'avi':
            case 'webm':
                return 'video_file';
            case 'mp3':
            case 'aac':
            case 'wav':
            case 'aiff':
            case 'flac':
                return 'audio_file';
            case 'pdf':
                return 'picture_as_pdf';
        }

        return 'insert_drive_file';
    }

    /**
     * Format bytes as KB, MB etc. representation of the size.
     *
     * @return string Largest suffix possible.
     */
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];
    
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
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
