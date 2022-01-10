<?php

namespace Modularity\Module\Notice;

class Notice extends \Modularity\Module
{
    public $slug = 'notice';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Notice", 'modularity');
        $this->namePlural = __("Notice", 'modularity');
        $this->description = __("Outputs a notice", 'modularity');

        //Add full-width capabilty to blocks
        add_filter('Modularity/Block/Settings', array($this, 'blockSettings'), 10, 2);

        //Add full width data to view
        add_filter('Modularity/Block/Data', array($this, 'blockData'), 10, 3);
    }

    public function data() : array
    {
        $data = get_fields($this->ID);
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->post_type, $this->args));
        $data['notice_size'] = $this->getSize($data['notice_size']);
        $data['icon'] = $this->iconData($data['notice_type'], $data['notice_size']);

        return $data;
    }

    public function iconData($icon, $size)
    {
        $icons = [
            'info'      => 'info',
            'success'   => 'check_circle',
            'warning'   => 'warning',
            'danger'    => 'error'
        ];

        return [
            'name' => $icons[$icon],
            'size' => $this->getSize($size)
        ];
    }

    public function getSize($notice_size) : string
    {
        return preg_replace('/notice-/i', '', $notice_size);
    }

    /**
     * Add full width setting to frontend.
     *
     * @param [array] $viewData
     * @param [array] $block
     * @param [object] $module
     * @return array
     */
    public function blockData($viewData, $block, $module)
    {

        if ($block['name'] == "acf/notice" && $block['align'] == 'full' && !is_admin()) {
            $viewData['stretch'] = true;
        }

        return $viewData;
    }

    /**
     * Allow full-width alignment on hero blocks
     *
     * @param array $data
     * @param string $slug
     * @return array
     */
    public function blockSettings($data, $slug)
    {
        if (strpos($slug, 'notice') === 0 && isset($data['supports'])) {
            $data['supports']['align'] = ['full'];
        }
        return $data;
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
