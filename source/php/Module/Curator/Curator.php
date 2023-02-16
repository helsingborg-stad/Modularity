<?php

namespace Modularity\Module\Curator;

class Curator extends \Modularity\Module
{
    public $slug = 'curator';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __('Curator Social Media', 'modularity');
        $this->namePlural = __('Curator Social Media', 'modularity');
        $this->description = __("Output social media flow via curator.", 'modularity');
    }

    public function script()
    {
        wp_register_script(
            'mod-curator-load-more',
            MODULARITY_URL . '/dist/' . \Modularity\Helper\CacheBust::name('js/mod-curator-load-more.js')
        );
        wp_enqueue_script('mod-curator-load-more');
    }
    public function data(): array
    {
        //Get module data
        $embedCode     = $this->parseEmbedCode(get_field('embed_code', $this->ID));
        $numberOfItems = get_field('number_of_posts', $this->ID) ?? 12;

        $data['layout'] = get_field('layout', $this->ID) ?? 'card';
        $data['ratio'] = get_field('ratio', $this->ID) ?? '1:1';

        $requestUrl = "https://api.curator.io/restricted/feeds/{$embedCode}/posts";
        $requestArgs = [
            'body' => [
                'limit'        => $numberOfItems,
                'hasPoweredBy' => true,
                'version'      => '4.0',
            ]
        ];

        // $transientKey = '_modularity_curator_social_media_feed_' . $this->ID;
        // if (false === ($feed = get_transient($transientKey))) {
        //     $response = wp_remote_retrieve_body(wp_remote_get($requestUrl, $requestArgs));
        //     $feed = set_transient($transientKey, $response, 12 * \HOUR_IN_SECONDS);
        // }
        // $feed = wp_remote_retrieve_body(wp_remote_get($requestUrl, $requestArgs));
        // $feed = set_transient($transientKey, json_decode($response), 12 * \HOUR_IN_SECONDS);

        // ! Development mode, no cache on response
        $feed = json_decode(wp_remote_retrieve_body(wp_remote_get($requestUrl, $requestArgs)));

        //Parse feed
        $data['showFeed'] = false;
        $data['limit']    = $numberOfItems;

        if (!empty($feed->posts)) {
            $data['posts']    = $feed->posts;
            $data['showFeed'] = true;
        }

        //Parse posts array
        if (is_array($data['posts']) && !empty($data['posts'])) {
            foreach ($data['posts'] as &$post) {
                $post->user_readable_name = $this->getUserName($post->user_screen_name);
                $post->text = wp_trim_words($post->text, 20, "...");
            }
        }

        //Could not fetch error message / embed code error message
        if (!$embedCode) {
            $data['errorMessage'] = __("An invalid embed code was provided, please try entering it again.", 'modularity');
        } else {
            $data['errorMessage'] = __("Could not get the feed at this moment, please try again later.", 'modularity');
        }

        //Send to view
        return $data;
    }

    /**
     * Parse embed javascript
     *
     * @param   string $embed   Embed javascript string
     *
     * @return  string $embed   Embed code
     */
    private function parseEmbedCode($embed)
    {

        if (preg_match('/published\/(.*?)\.js/i', $embed, $match) == 1) {
            return $match[1];
        }

        return false;
    }

    /**
     * Get username as readable from user string
     *
     * @param   string $userName
     * @return  string $userName
     */
    private function getUserName($userName)
    {
        return ucwords(str_replace(['.', '-'], ' ', $userName));
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
