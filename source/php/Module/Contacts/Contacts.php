<?php

namespace Modularity\Module\Contacts;

class Contacts extends \Modularity\Module
{
    public $slug = 'contacts';
    public $supports = array();
    public $displaySettings = null;

    public function init()
    {
        $this->nameSingular = __('Contacts v2', 'modularity');
        $this->namePlural = __('Contacts v2', 'modularity');
        $this->description = __('Outputs one or more contacts', 'modularity');
    }

    public function data() : array
    {
        $data = $this->getFields();
        $data['ID'] = $this->ID;

        if(!empty($data['contacts'])) {
            $data['contacts'] = $this->prepareContacts($data['contacts']);
        }
        
        if (!isset($data['columns'])) {
            $data['columns'] = 'o-grid-12@md';
        }

        //Translations
        $data['lang'] = (object) [
            'email' => __('Email', 'modularity'),
            'call' => __('Call', 'modularity'),
            'address' => __('Address', 'modularity'),
            'visiting_address' => __('Visiting address', 'modularity'),
            'opening_hours' => __('Opening hours', 'modularity')
        ];

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
                'social_media' => null,
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
                        'phone'               => $contact['phone_numbers'] ?? null,
                        'address'             => strip_tags($contact['address'] ?? '', '<br>'),
                        'visiting_address'    => strip_tags($contact['visiting_address'] ?? '', ['<br>', '<a>']),
                        'opening_hours'       => strip_tags($contact['opening_hours'], '<br>'),
                        'other'               => $contact['other']
                    ), $contact, $contact['acf_fc_layout']);
                    break;

                case 'user':
                    $info = apply_filters('Modularity/mod-contacts/contact-info', array(
                         'id'                  => !empty($contact['user']['ID']) ? $contact['user']['ID'] : '',
                         'image'               => null,
                         'first_name'          => $contact['user']['user_firstname'] ?? '',
                         'last_name'           => $contact['user']['user_lastname'] ?? '',
                         'work_title'          => null,
                         'administration_unit' => null,
                         'email'               => $contact['user']['user_email'] ?? '',
                         'phone'               => null,
                         'address'             => strip_tags($contact['address'] ?? '', '<br>'),
                         'visiting_address'    => null,
                         'opening_hours'       => null,
                         'other'               => $contact['user']['user_description'] ?? '',
                     ), $contact, $contact['acf_fc_layout']);
                    break;
            }

            //Parse thumbnail id's
            $info['thumbnail'] = false;
            if (isset($info['image']) && !empty($info['image']) && isset($info['image']['id']) && is_numeric($info['image']['id'])) {
                $info['thumbnail'] = wp_get_attachment_image_src(
                    $info['image']['id'],
                    [400, 400]
                );
            }

            //Create full name
            $info['full_name'] = trim($info['first_name'] . ' ' . $info['last_name']);

            //Create full title string
            $titlePropeties = ['administration_unit', 'work_title'];
            $fullTitle = array_filter(array_map(function($key) use ($info) {
                return $info[$key] ?: false;
            }, $titlePropeties), function($item) {return $item;});
            $info['full_title'] = is_array($fullTitle) ? implode(', ', $fullTitle) : '';

            //Contact returns
            $retContacts[] = $info;
        }

        return $retContacts;
    }

    public function template()
    {
        return 'cards.blade.php';
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
