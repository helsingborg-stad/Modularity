<?php

namespace Modularity\Module\Map\TemplateController;

use Modularity\Module\Map\Map;

class EmbedController implements TemplateControllerInterface
{
    public function __construct(private Map $module)
    {
    }

    /**
     * Add data to the template.
     */
    public function addData(array $data, array $fields): array
    {
        //Get and sanitize url
        $map_url = $fields['map_url'];
        $map_url = str_replace('http://', 'https://', $map_url, $replaced); // Enforce ssl

        /**
         * If the scheme is not altered with str_replace, the url may only contain // without https:
         */
        if(0 === $replaced) {
            $parsedUrl = parse_url( $map_url );
            if(!isset($parsedUrl['scheme']) ) {
                $map_url = str_replace('//', 'https://', $map_url); // Ensure url scheme is literal
            }
        }

        $map_url = str_replace('disable_scroll=false', 'disable_scroll=true', $map_url); //Remove scroll arcgis

        //Create data array
        $data['map_url']            = $map_url;
        $data['map_description']    = !empty($fields['map_description']) ? $fields['map_description'] : '';
        
        $data['show_button']        = !empty($fields['show_button']) ? $fields['show_button'] : false;
        $data['button_label']       = !empty($fields['button_label']) ? $fields['button_label'] : false;
        $data['button_url']         = !empty($fields['button_url']) ? $fields['button_url'] : false;
        $data['more_info_button']   = !empty($fields['more_info_button']) ? $fields['more_info_button'] : false;
        $data['more_info']          = !empty($fields['more_info']) ? $fields['more_info'] : false;
        $data['more_info_title']    = !empty($fields['more_info_title']) ? $fields['more_info_title'] : false;

        $data['cardMapCss']         = ($data['more_info_button']) ? 'o-grid-12@xs o-grid-8@md' : 'o-grid-12@md';
        $data['cardMoreInfoCss']    = ($data['more_info_button']) ? 'o-grid-12@xs o-grid-4@md' : '';

        $data['uid']                = uniqid();
        $data['id']                 = $this->module->ID;

        $data['lang'] = [
            'knownLabels' => [
                'title' => __('We need your consent to continue', 'modularity'),
                'info' => sprintf(__('This part of the website shows content from %s. By continuing, <a href="%s"> you are accepting GDPR and privacy policy</a>.', 'modularity'), '{SUPPLIER_WEBSITE}', '{SUPPLIER_POLICY}'),
                'button' => __('I understand, continue.', 'modularity'),
            ],

            'unknownLabels' => [
                'title' => __('We need your consent to continue', 'modularity'),
                'info' => sprintf(__('This part of the website shows content from another website (%s). By continuing, you are accepting GDPR and privacy policy.', 'municipio'), '{SUPPLIER_WEBSITE}'),
                'button' => __('I understand, continue.', 'modularity'),
            ],
        ];

        return $data;
    }

    /**
     * Check if this controller can handle the given fields.
     */
    public function canHandle(array $fields): bool
    {
        return empty($fields['map_type']) && isset($fields['map_url']);
    }
}