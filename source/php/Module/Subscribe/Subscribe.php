<?php

namespace Modularity\Module\Subscribe;

class Subscribe extends \Modularity\Module
{
    public $slug = 'subscribe';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full']
    );

    public function init()
    {
        $this->nameSingular = __('Email Subscribe', 'modularity');
        $this->namePlural = __('Email Subscribtions', 'modularity');
        $this->description = __('Outputs a simpele form to subscribe to a email list.', 'modularity');
    }

    public function data() : array
    {
        $data = []; 

        //Set id
        $data['uid'] = uniqid($this->slug);

        //Get module data
        $fields = get_fields($this->ID);

        $data['type']               = $fields['service'] ?? false; 
        $data['content']            = $fields['content'] ?? '';
        $data['consentMessage']     = $fields['consent_message'] ?? '';

        //Translations
        $data['lang'] = (object) [
            'email' => (object) [
                'label' => __('Your email adress', 'modularity'),
                'placeholder' => __('email@email.com', 'modularity'),
                'error' => __('Please enter a valid email address', 'modularity'),
            ],
            'submit' => (object) [
                'label' => __('Subscribe', 'modularity'),
            ],
            'submitted' => (object) [
                'title' => __('Subscribed', 'modularity'),
                'text'  => __('You are now subscribed to this email list.', 'modularity'),
            ],
            'incomplete' => (object) [
                'title' => __('Select a provider', 'modularity'),
                'text'  => __('No provider for this form is selected. Please select a provider available form the list.', 'modularity'),
            ],
            'error' => (object) [
                'title' => __('Could not subscribe', 'modularity'),
                'text'  => __('Sorry, we could not subscribe you to this list at the moment. Please try again later.', 'modularity'),
            ]
        ];

        //Run service filter
        $method = 'handle' . ucfirst($data['type']) . "Data";
        if(method_exists($this, $method)) {
            $data = $this->{$method}($data, $fields); 
        }

        return $data;
    }

    private function handleUngdpData($data, $fields) {
        $data['formID'] = $fields['settings_for_ungapped_service']['form_id'] ?? false; 
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
