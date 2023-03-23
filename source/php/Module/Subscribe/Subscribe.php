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

        //Get module data
        $fields = get_fields($this->ID);

        $data['type']               = "ungpd"; 
        $data['content']            = "Curabitur blandit tempus porttitor. Cras mattis consectetur purus sit amet fermentum."; 
        $data['consentMessage']     = "I want to receive relevant information from this organization to my inbox. The information provided here will not be shared or sold. I can unsubscribe at any time."; 

        //Translations
        $data['lang'] = (object) [
            'email' => (object) [
                'label' => __('Your email adress', 'modularity'),
                'placeholder' => __('email@email.com', 'modularity'),
                'error' => __('Please enter a valid email address', 'modularity'),
            ],
            'submit' => (object) [
                'label' => __('Subscribe', 'modularity'),
            ]
        ];

        //Run service filter
        $method = 'handle' . ucfirst($data['type']) . "Data";
        if(method_exists($this, $method)) {
            $data = $this->{$method}($data, $fields); 
        }

        return $data;
    }

    private function handleUngpdData($data, $fields) {
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
