<?php

namespace Modularity\Module\Contacts;

class Contacts extends \Modularity\Module
{
    public $slug = 'contacts';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __('Contacts v2', 'modularity');
        $this->namePlural = __('Contacts v2', 'modularity');
        $this->description = __('Outputs one or more contacts', 'modularity');
    }

    public function data() : array
    {
        $data = get_fields($this->ID);
        $data['contacts'] = $this->prepareContacts($data['contacts']);
        if (!isset($data['columns'])) {
            $data['columns'] = 'grid-md-12';
        }

        return $data;
    }

    /**
     * Prepare the contact data
     * @param  array $contacts
     * @return array
     */
    public function prepareContacts($contacts)
    {

        $retContacts = array();

        foreach ($contacts as &$contact) {
            $info = array(
                'image' => null,
                'first_name' => null,
                'last_name' => null,
                'work_title' => null,
                'administration_unit' => null,
                'email' => null,
                'phone' => null,
                'address' => null,
                'visiting_address' => null,
                'opening_hours' => null
            );

            switch ($contact['acf_fc_layout']) {
                case 'custom':
                    $info = apply_filters('Modularity/mod-contacts/contact-info', array(
                        'image'               => $contact['image'],
                        'first_name'          => $contact['first_name'],
                        'last_name'           => $contact['last_name'],
                        'work_title'          => $contact['work_title'],
                        'administration_unit' => $contact['administration_unit'],
                        'email'               => strtolower($contact['email']),
                        'phone'               => $contact['phone_numbers'],
                        'address'             => $contact['address'],
                        'visiting_address'    => $contact['visiting_address'],
                        'opening_hours'       => $contact['opening_hours'],
                        'other'               => $contact['other']
                    ), $contact, $contact['acf_fc_layout']);
                    break;

                case 'user':
                    $info = apply_filters('Modularity/mod-contacts/contact-info', array(
                        'id'                  => $contact['user']['ID'],
                        'image'               => null,
                        'first_name'          => $contact['user']['user_firstname'],
                        'last_name'           => $contact['user']['user_lastname'],
                        'work_title'          => null,
                        'administration_unit' => null,
                        'email'               => strtolower($contact['user']['user_email']),
                        'phone'               => null,
                        'address'             => null,
                        'visiting_address'    => null,
                        'opening_hours'       => null
                    ), $contact, $contact['acf_fc_layout']);

                    break;
            }

            //Parse thumbnail id's
            $info['thumbnail'] = false;
            if (isset($info['image']) && !empty($info['image']) && is_numeric($info['image']['id'])) {
                $info['thumbnail'] = wp_get_attachment_image_src(
                    $info['image']['id'],
                    apply_filters(
                        'Modularity/image/contact',
                        municipio_to_aspect_ratio('1:1', array(400, 400)),
                        $this->args
                    )
                );
            }

            //Parse directly inputted url:s
            if (isset($info['image']) && filter_var($info['image'], FILTER_VALIDATE_URL) !== false) {
                $info['thumbnail'] = array($info['image'], 250, 250, false);
            }

            //Block numbers that are to short
            if (strlen($info['phone']) < 4) {
                $info['phone'] = "";
            }

            //Create full name
            $info['full_name'] = trim($info['first_name'] . ' ' . $info['last_name']);

            $retContacts[] = $info;
        }

        return $retContacts;
    }

    public function template()
    {

        //Reset
        $this->data['hasImages'] = false;
        $hasImages = "";

        // Multiple contacts template(s)
        if (isset($this->data['contacts']) && count($this->data['contacts']) > 0) {

            //Indicates wheter this list has images or not.
            foreach ($this->data['contacts'] as $item) {
                if (isset($item['thumbnail']) && is_array($item['thumbnail'])) {
                    if (!empty(array_filter($item['thumbnail']))) {
                        $hasImages = "has-image";
                        $this->data['hasImages'] = true;
                        break;
                    }
                }
            }

            //Display mode
            $displayMode = 'cards';

            if (isset($this->data['display_mode']) && !empty($this->data['display_mode'])) {
                $displayMode = $this->data['display_mode'];
            }

            switch ($displayMode) {
                case 'cards':
                    $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-card', $hasImages), $this->post_type, $this->args));
                    $this->data['equalItem'] = "data-equal-item";
                    $this->data['equalContainer'] = "data-equal-container";

                    $view = "cards";
                    break;

                case 'vertical':
                    $view = "cards";
                    $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-card', 'vertical-card', $hasImages), $this->post_type, $this->args));
                    $this->data['columns'] = 'grid-md-12';
                    break;

                case 'list':
                    $view = "list";
                    break;
            }

            return $view . '.blade.php';
        }

        // Single contact template
        $this->data['thumbnail']    = false;

        //Profile image
        if (isset($fields->picture) && !empty($fields->picture)) {
            $this->data['thumbnail'] = wp_get_attachment_image_src(
                $fields->picture->id,
                apply_filters(
                    'modularity/image/contact',
                    municipio_to_aspect_ratio('16:9', array(400, 400)),
                    $args
                )
            );
            $hasImages = "has-image";
            $this->data['hasImages'] = true;
        }

        //Add casses
        $this->data['classes']      = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-card', $hasImages), $this->post_type, $this->args));

        return 'contacts.blade.php';
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
