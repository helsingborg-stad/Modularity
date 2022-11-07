<?php

namespace Modularity\Module\Script;

class Script extends \Modularity\Module
{
    public $slug = 'script';
    public $supports = array();
    public $isBlockCompatible = true;

    public function init()
    {
        $this->nameSingular = __("Script", 'modularity');
        $this->namePlural = __("Script", 'modularity');
        $this->description = __("Outputs unsanitized code to widget area.", 'modularity');

        //Remove html filter
        add_action('save_post', array($this, 'disableHTMLFiltering'), 5);
    }

    public function data() : array
    {
        $data = array();

        /* Parsing the embed code and extracting the scripts, iframes, links and styles. */
        $embed = get_field('embed_code', $this->ID);

        $data['embed'] = [];

        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $embed);

        $xpath = new \DOMXpath($doc);
        $allowedElements = $xpath->query('//script | //iframe | //link | //style | //div | //span');

        for ($i = 0; $i < $allowedElements->length; $i++) {
            $element = $allowedElements->item($i);

            $data['embed'][$i]['content'] =
            is_admin() ?
            '<pre>' . $doc->saveHTML(htmlspecialchars($element)) . '</pre>' :
            $doc->saveHTML($element);

            $data['embed'][$i]['src'] = null;
            $data['embed'][$i]['requiresAccept'] = 1;

            switch ($element->tagName) {
                case 'script':

                    $data['embed'][$i]['src'] = null;
                    $data['embed'][$i]['requiresAccept'] = 0;

                    $doc->saveHTML($element->setAttribute('defer', true));

                    $src = $element->getAttribute('src');
                    if (!empty($src)) {
                        $data['embed'][$i]['requiresAccept'] = 1;
                        $data['embed'][$i]['src'] = $src;
                    }
                    break;

                case 'iframe':
                    $src = $element->getAttribute('src');

                    $data['embed'][$i]['requiresAccept'] = 1;
                    $data['embed'][$i]['src'] = $src;

                    if (empty($src)) {
                        $data['embed'][$i]['requiresAccept'] = 0;
                        $data['embed'][$i]['src'] = null;
                    }
                    break;
                case 'link':
                    $href = $element->getAttribute('href');
                    
                    $data['embed'][$i]['requiresAccept'] = 1;
                    $data['embed'][$i]['src'] = $href;

                    if (empty($href)) {
                        $data['embed'][$i]['requiresAccept'] = 0;
                        $data['embed'][$i]['src'] = null;
                    }
                    break;
                case 'style':
                    $data['embed'][$i]['requiresAccept'] = 0;
                    $data['embed'][$i]['src'] = null;
                    break;

                case 'div':
                case 'span':
                    $data['embed'][$i]['requiresAccept'] = 0;
                    $data['embed'][$i]['src'] = null;
                    break;
                default:
                    // no action necessary
                    break;
            }
        }

        $data['scriptWrapWithClassName'] = get_field('script_wrap_with', $this->ID) ?? 'card';

        $placeholder = get_field('embedded_placeholder_image', $this->ID);
        $attachment = wp_get_attachment_image_src($placeholder['ID'], [1000, false]);

        $data['placeholder'] = [
            'url' => $attachment[0],
            'width' => $attachment[1],
            'height' => $attachment[2],
            'alt' => $placeholder['alt']
        ];

        $embededCardPadding = get_field('embeded_card_padding', $this->ID);
        $data['scriptPadding'] =
        (bool) $embededCardPadding ?
        "u-padding__y--{$embededCardPadding} u-padding__x--$embededCardPadding" :
        '';

        $data['lang'] = (object) [
            'knownLabels' => [
                'title' => __('We need your consent to continue', 'modularity'),
                'info' => sprintf(__('This part of the website shows content from %s. By continuing, <a href="%s"> you are accepting GDPR and privacy policy</a>.', 'modularity'), '{SUPPLIER_WEBSITE}', '{SUPPLIER_POLICY}'),
                'button' => __('I understand, continue.', 'modularity'),
            ],

            'unknownLabels' => [
                'title' => __('We need your consent to continue', 'modularity'),
                'info' => __('This part of the website shows content from another website. By continuing, you are accepting GDPR and privacy policy.', 'modularity'),
                'button' => __('I understand, continue.', 'modularity'),
            ],
        ];

        return $data;
    }
    /**
     * Removes the filter of html & script data before save.
     * @var int
     */
    public function disableHTMLFiltering($postId)
    {
        //Bail early if not a script module save
        if (get_post_type($postId) !== "mod-" . $this->slug) {
            return;
        }

        //Disable filter temporarirly
        add_filter('acf/allow_unfiltered_html', function ($allow_unfiltered_html) {
            return true;
        });
    }


    public function template()
    {
        return $this->data['scriptWrapWithClassName'] . '.blade.php';
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
