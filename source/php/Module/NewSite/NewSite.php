<?php

namespace Modularity\Module\NewSite;

class NewSite extends \Modularity\Module
{
    public $slug = 'newsite';
    public $supports = array();
    public $displaySettings = null;

    private $formResponse = null;

    public function init()
    {
        $this->nameSingular = __('New Site', 'modularity');
        $this->namePlural   = __('New Sites', 'modularity');
        $this->description  = __('Outputs form to create new sites.', 'modularity');

        //Handle new site form
        if($this->isFormSent()) {
            $newSiteForm = new NewSiteForm();
            
            if(is_wp_error($newSiteForm)) {
                $formErrors = $newSiteForm
            }
        }
    }

    /**
     * Data in view
     *
     * @return array
     */
    public function data() : array
    {
        return $data = [
            'lang' => $this->lang(),
            'isMultisite' => $this->isMultisite(),
            'form' => (object) [
                'action' => $this->formAction()
            ],
            'isFormSent' => $this->isFormSent(),
            'formErrors' => (object) $this->formErrors
        ];
    }

    private function formAction() : string {
        return '?newSiteSubmit=true'; 
    }

    /**
     * Check if form is sent.
     *
     * @return boolean
     */
    private function isFormSent() : bool {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['newSiteSubmit'])) {
            return true; 
        }
        return false; 
    }

    /**
     * String translations
     *
     * @return object
     */
    private function lang() : object {
        return (object) [
            'noms'          => __("This module requires that the site is configured as a multisite installation. Please enable WordPress Multisite feature to use this functionality.", 'modularity'),
            'formsent'      => __("Cool! Your site is being created. When it's done, you will be reciving a link in your inbox with an activation link.", 'modularity'), 
            'formsubmit'    => __("I'm done! Give me a site!", 'modularity'),
            'description'   => (object) [
                'sitename'  => __("The title of the page (visible in the sites tab).", 'modularity'),
                'siteslug'  => sprintf(__("Your domain will be: %s. The doimain should only contain letters A-Z and will be lowercased.", 'modularity'), $this->exampleurl('{{SLUG}}')),
            ]
        ];
    }

    /**
     * Simply check if is ms
     *
     * @return boolean
     */
    private function isMultisite() : bool {
        if(!function_exists('is_multisite')) {
            return false; 
        }
        return is_multisite(); 
    }

    /**
     * Create an url from name
     *
     * @param string $name
     * @return string
     */
    private function exampleurl($name = "name") : string {
        if(is_multisite()) {
            if(defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL === true) {
                return $name . '.' . network_home_url(); 
            }
            return network_home_url() . $name ."/"; 
        }
        return "{{Could not fetch url.}}"; 
    }

    /**
     * Return view name
     *
     * @return string
     */
    public function template() : string
    {
        return 'newsite.blade.php';
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
