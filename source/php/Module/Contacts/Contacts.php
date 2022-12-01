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
        $data = get_fields($this->ID);
        $data['ID'] = $this->ID;
        
        $data['uid'] = uniqid();

        //Display settings
        if (array_key_exists('display_settings', $data) && !empty($data['display_settings'])) {
            foreach ($data['display_settings'] as $fieldToHide) {
                $data[$fieldToHide] = true;
            }
        }

        $this->displaySettings = (array_key_exists('display_settings', $data)) ?
            $data['display_settings'] : '';

        $data['contacts'] = $this->prepareContacts($data['contacts']);

        if (!isset($data['columns'])) {
            $data['columns'] = 'o-grid-12@md';
        }

        $data['columns'] = apply_filters('Municipio/Controller/Archive/GridColumnClass', $data['columns']);//str_replace('grid-md-', 'o-grid-', $data['columns'] . '@md'); //TODO: Update grid values from ACF (?)

        $data['showImages'] = false;

        //Check if there is at least one contact with image, then add svg to others, else hide all
        if (empty($this->data['display_mode']) || $this->data['display_mode'] == 'card') {
            foreach ($data['contacts'] as $contact) {
                $data['showImages'] = $contact['image'] ? true : $data['showImages'];
            }
        }

        //Translation
        $data['OpeningHours'] = __('Opening hours', 'modularity');

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
                        'image'               => $this->hideField('thumbnail') ? null : $contact['image'],
                        'first_name'          => $contact['first_name'],
                        'last_name'           => $contact['last_name'],
                        'work_title'          => $this->hideField('work_title') ? null : $contact['work_title'],
                        'administration_unit' => $this->hideField('administration_unit') ? null : $contact['administration_unit'],
                        'email'               => $this->hideField('email') ? null : strtolower($contact['email']),
                        'phone'               => $this->hideField('phone') ? null : $contact['phone_numbers'],
                        'social_media'        => $this->hideField('social_media') ? null : $contact['social_media'],
                        'address'             => $this->hideField('address') ? null : strip_tags($contact['address'], '<br>'),
                        'visiting_address'    => $this->hideField('visiting_address') ? null : strip_tags($contact['visiting_address'], ['<br>', '<a>']),
                        'opening_hours'       => strip_tags($contact['opening_hours'], '<br>'),
                        'hasBody'             => $this->hasBody($contact),
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
                         'email'               => $this->hideField('email') ? null : strtolower($contact['user']['user_email']),
                         'phone'               => null,
                         'address'             => $this->hideField('address') ? null : strip_tags($contact['address'], '<br>'),
                         'visiting_address'    => null,
                         'opening_hours'       => null,
                         'other'               => $contact['user']['user_description'],
                         'hasBody'             => $this->hasBody($contact)
                     ), $contact, $contact['acf_fc_layout']);
                    break;
            }

            //Parse thumbnail id's
            $info['thumbnail'] = false;
            if (isset($info['image']) && !empty($info['image']) && isset($info['image']['id']) && is_numeric($info['image']['id'])) {
                $info['thumbnail'] = wp_get_attachment_image_src(
                    $info['image']['id'],
                    apply_filters(
                        'Modularity/image/contact',
                        municipio_to_aspect_ratio('1:1', array(550, 550)),
                        $this->args
                    )
                );
                $info['image']['inlineStyle']  = "background-image:url('" . $info['thumbnail'][0] . "');";
            }

            //Parse directly inputted url:s
            if (isset($info['image']) && filter_var($info['image'], FILTER_VALIDATE_URL) !== false) {
                $info['thumbnail'] = array($info['image'], 250, 250, false);
            }

            //Create full name
            $info['full_name'] = trim($info['first_name'] . ' ' . $info['last_name']);

            //Adds chosen user meta data or removes the field completely and make it unvisible.
            if (get_field('advaced_mode', $this->ID) == "1") {
                if (get_field('profile_image', $this->ID) == "1") {
                    $info['thumbnail'][0] = get_user_meta($contact['user']['ID'], "user_profile_picture", true);
                } else {
                    unset($info['thumbnail']);
                }

                if (get_field('other_user_info', $this->ID) == "1") {
                    $info['other'] = get_user_meta($contact['user']['ID'], "user_about", true);
                } else {
                    unset($info['other']);
                }

                if (get_field('work_title', $this->ID) == "1") {
                    $info['work_title'] = get_user_meta($contact['user']['ID'], "user_work_title", true);
                } else {
                    unset($info['work_title']);
                }

                if (get_field('email', $this->ID) != "1") {
                    unset($info['email']);
                }

                if (get_field('address', $this->ID) != "1") {
                    unset($info['address']);
                }

                if (get_field('phone', $this->ID) != "1") {
                    unset($info['phone']);
                }
            }

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

                case 'vertical': //This option is the option which is called "Horizontal cards" in the editor
                    $this->data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-card', $hasImages), $this->post_type, $this->args));
                    $this->data['equalItem'] = "data-equal-item";
                    $this->data['equalContainer'] = "data-equal-container";

                    $view = "cards";
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

    public function hideField($needle)
    {
        if (!$this->displaySettings) {
            return false;
        }

        $needle = 'hide_' . $needle;

        if (in_array($needle, $this->displaySettings)) {
            return true;
        }

        return false;
    }

    public function hasBody($contact)
    {
        $cases = array(
            $contact['email'] ?? '',
            $contact['phone'] ?? '',
            $contact['social_media'] ?? '',
            $contact['opening_hours'] ?? '',
            $contact['address'] ?? '',
            $contact['visiting_address'] ?? '',
            $contact['other'] ?? ''
        );

        foreach ($cases as $case) {
            if ($case) {
                return true;
            }
        }

        return false;
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
