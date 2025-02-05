<?php

namespace Modularity\Module\Map;

use Modularity\Module\Map\Resolvers\TemplateResolver;
use Modularity\Module\Map\Resolvers\TemplateResolverInterface;
use Modularity\Module\Map\TemplateController\OpenStreetMapController;
use Modularity\Module\Map\TemplateController\EmbedController;
use Modularity\Module\Map\TemplateController\NullController;
use Modularity\Module\Map\TemplateController\TemplateControllerInterface;


class Map extends \Modularity\Module
{
    public $slug = 'map';
    public $supports = array();

    protected $template = 'default';

    private TemplateControllerInterface $templateController;
    private TemplateResolverInterface $templateResolver;


    public function init()
    {
        $this->nameSingular = __('Map', 'modularity');
        $this->namePlural = __('Maps', 'modularity');
        $this->description = __("Outputs an embedded map.", 'modularity');

        add_filter('acf/load_field/name=map_url', array($this,'sslNotice'));
        add_filter('acf/load_value/name=map_url', array($this,'filterMapUrl'), 10, 3);
        add_filter('acf/update_value/name=map_url', array($this,'filterMapUrl'), 10, 3);

        $this->templateResolver = new TemplateResolver(
            new OpenStreetMapController(),
            new EmbedController($this),
            new NullController()
        );
    }

    /**
     * This PHP function retrieves data based on certain conditions and returns either OpenStreetMap
     * template data or default template data.
     * 
     * @return array The `data()` function is returning either the result of the
     * `openStreetMapTemplateData()` function or the `defaultTemplateData()` function based on the
     * value of the `map_type` field in the `` array.
     */
    public function data() : array
    {
        $fields = $this->getFields();
        $data = array();
        $this->templateController = $this->templateResolver->resolve($fields);

        //Shared template data
        $data['height'] = !empty($fields['height']) ? $fields['height'] : '400';

        return $this->templateController->addData($data, $fields);  
    }

    /**
     * The function `sslNotice` adds a notice to a field if SSL is enabled or if a SSL proxy is being
     * used.
     * 
     * @param field The `sslNotice` function takes a parameter named ``, which seems to be an
     * array containing instructions for a map link. The function checks if SSL is enabled or if an SSL
     * proxy is being used, and if so, it modifies the instructions to include a notice about using
     * `https://`
     * 
     * @return The function `sslNotice` is returning the `` array with updated instructions if
     * the current connection is using SSL or an SSL proxy. The instructions will inform the user that
     * map links must start with `https://` for proper display.
     */
    public function sslNotice($field)
    {
        if (is_ssl() || $this->isUsingSSLProxy()) {
            $field['instructions'] = '<span style="color: #f00;">'.__("Your map link must start with http<strong>s</strong>://. Links without this prefix will not display.", 'modularity').'</span>';
        }

        return $field;
    }

    
    /**
     * The function `isUsingSSLProxy` checks if SSL proxy is being used based on the defined constant
     * `SSL_PROXY`.
     * 
     * @return The function `isUsingSSLProxy()` will return `true` if the constant `SSL_PROXY` is
     * defined and its value is `true`. Otherwise, it will return `false`.
     */
    private function isUsingSSLProxy()
    {
        if ((defined('SSL_PROXY') && SSL_PROXY === true)) {
            return true;
        }

        return false;
    }

    /**
     * Filter the map URL value.
     *
     * @param string $value The map URL value to be filtered.
     * @param int $post_id The ID of the post.
     * @param string $field The field name.
     * @return string The filtered map URL value.
     */
    public function filterMapUrl($value, $post_id, $field) 
    {
        $value = htmlspecialchars_decode($value);
        return $value;
    }

    /**
     * Returns the template file path for the Map module.
     *
     * @return string The template file path.
     */
    public function template() {
        $path = __DIR__ . "/views/" . $this->templateController->getTemplateName() . ".blade.php";

        if (file_exists($path)) {
            return $this->templateController->getTemplateName() . ".blade.php";
        }
        
        return 'notFound.blade.php';
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
