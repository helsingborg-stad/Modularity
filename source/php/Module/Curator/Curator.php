<?php

namespace Modularity\Module\Curator;

class Curator extends \Modularity\Module
{
    public $slug = 'curator';
    public $supports = array();
    private $curl;

    public function init()
    {
        $this->nameSingular = __('Curator Social Media', 'modularity');
        $this->namePlural = __('Curator Social Media', 'modularity');
        $this->description = __("Output social media flow via curator.", 'modularity');

        $this->curl = new \Modularity\Helper\Curl(true, (60 * 60 * 12)); //Cache 12 hours
    }

    public function data(): array
    {
        //Get module data
        $embedCode      = $this->parseEmbedCode(get_field('embed_code', $this->ID));
        $numberOfItems  = get_field('number_of_posts', $this->ID) ?? 12;

        $requestUrl = "https://api.curator.io/restricted/feeds/{$embedCode}/posts";
        $requestArgs = [
            'limit'        => $numberOfItems,
            'hasPoweredBy' => true,
            'version'      => '4.0',
        ];

        $transientKey = '_modularity_curator_social_media_feed_' . $this->ID;

        if (false === ($feed = get_transient($transientKey))) {
            $response = wp_remote_retrieve_body(wp_remote_get($requestUrl, $requestArgs));
            $feed = set_transient($transientKey, $response, 15 * MINUTE_IN_SECONDS);
        }

        //Feed parser
        if ($feed = json_decode($feed)) {
            if (isset($feed->posts) && count($feed->posts)) {
                $data['posts']    = $feed->posts;
                $data['showFeed'] = true;
            } else {
                $data['showFeed'] = false;
            }
        } else {
            $data['showFeed'] = false;
        }

        //Parse array
        if (is_array($data['posts']) && !empty($data['posts'])) {
            foreach ($data['posts'] as &$post) {
                $post->user_readable_name = $this->getUserName($post->user_screen_name);
                $post->text = wp_trim_words($post->text, 20, "...");
            }
        }

        //Could not fetch error message / embed code error message
        if (!$embedCode) {
            $data['errorMessage'] = __("A invalid embed code was provided, please try enter it again.", 'modularity');
        } else {
            $data['errorMessage'] = __("Could not get the instagram feed at this moment, please try again later.", 'modularity');
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
