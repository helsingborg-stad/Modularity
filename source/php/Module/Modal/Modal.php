<?php

namespace Modularity\Module\Modal;

class Modal extends \Modularity\Module
{
    public $slug = 'modal';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Modal", 'modularity');
        $this->namePlural = __("Modals", 'modularity');
        $this->description = __("Outputs a button and the content of a selected post into a modal, accessible by clicking on the button.", 'modularity');

        // register custom wordpress post type:
        add_action('init', array($this, 'registerPostType'));
    }

    public function data(): array
    {
        //Get settings
        $fields = get_fields($this->ID);
        $data   = [];

        $data['icon']  = $fields['button']['material_icon'] ?? false;
        $data['label'] = $fields['button']['label'] ?? false;

        $data['modalId'] = $fields['modal_content']->ID ?? 0;
        $data['modalContentTitle'] = $fields['modal_content']->post_title ?? false;
        $data['modalContent'] = $fields['modal_content']->post_content ?? false;

        return $data;
    }

    public function registerPostType()
    {
        $args = [
            'supports'              => [ 'title', 'editor', 'revisions' ],
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'has_archive'           => false,
            'show_in_rest'          => true,
            'capability_type'       => 'page',
            'labels' => [
                'all_items'             => __('All Modals', 'modularity'),
                'add_new_item'          => __('Add New Modal', 'modularity'),
                'add_new'               => __('Add Modal', 'modularity'),
                'new_item'              => __('New Modal', 'modularity'),
                'edit_item'             => __('Edit Modal', 'modularity'),
                'update_item'           => __('Update Modal', 'modularity'),
                'view_item'             => __('View Modal', 'modularity'),
                'view_items'            => __('View Modals', 'modularity'),
                'search_items'          => __('Search For Modal', 'modularity'),
            ],
            ];
        register_post_type('mod-modal-content', $args);
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
