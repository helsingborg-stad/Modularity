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

        $this->data['i18n'] = [
            'loadMore' => __('Load More', 'modularity'),
            'goToOriginalPost' => __('Go to original post', 'modularity'),
            'noMoreItems' => __('No more items to load.', 'modularity'),
        ];

        add_action('wp_ajax_mod_curator_get_feed', [$this, 'getFeed'], 10, 4);
        add_action('wp_ajax_nopriv_mod_curator_get_feed', [$this, 'getFeed'], 10, 4);

        add_action('wp_ajax_mod_curator_load_more', [$this, 'loadMorePosts']);
        add_action('wp_ajax_nopriv_mod_curator_load_more', [$this, 'loadMorePosts']);
    }

    public function loadMorePosts()
    {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            return false;
        }
        if (empty($_POST['posts'])) {
            return false;
        }
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mod-posts-load-more')) {
            wp_die('Nonce check failed');
            return false;
        }

        $posts = json_decode(stripslashes($_POST['posts']));
        if (!is_array($posts)) {
            wp_die();
        }

        $posts = self::parseSocialMediaPosts($posts);
        $i18n = $this->data['i18n'];

        $layout = empty($_POST['layout']) ? 'card' : $_POST['layout'];

        // Print the posts via the blade template
        echo render_blade_view(
            "partials/$layout",
            [
                'posts' => $posts,
                'i18n' => $i18n,
                'columnClasses' => $_POST['columnClasses']
            ],
            [
                plugin_dir_path(__FILE__) . 'views'
            ]
        );
        wp_die(); // Always die in functions echoing ajax content
    }
    public function script()
    {
        wp_register_script(
            'mod-curator-load-more',
            MODULARITY_URL . '/dist/' . \Modularity\Helper\CacheBust::name('js/mod-curator-load-more.js')
        );
        $strings = array_merge($this->data['i18n'], [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mod-posts-load-more')
        ]);
        wp_localize_script('mod-curator-load-more', 'curator', $strings);
        wp_enqueue_script('mod-curator-load-more');
    }
    public function data(): array
    {
        //Get module data
        $data['embedCode']     = $this->parseEmbedCode(get_field('embed_code', $this->ID));

        $data['i18n'] = $this->data['i18n'];

        $data['numberOfItems'] = get_field('number_of_posts', $this->ID) ?? 12;
        $data['ratio']         = get_field('ratio', $this->ID) ?? '4:3';
        $data['gutter']        = get_field('gutter', $this->ID) ?  'o-grid--no-gutter' : '';
        $data['layout']        = get_field('layout', $this->ID) ?? 'card';

        if ($data['layout'] === 'block') {
            $data['columnClasses'] = 'o-grid-12@xs o-grid-6@sm ';

            $columns = get_field('columns', $this->ID) ?? 4;
            $data['columnClasses'] .= ($columns == 3 ) ? 'o-grid-4@lg' : 'o-grid-3@lg';
        } else {
            $data['columnClasses'] = 'o-grid-12@xs o-grid-6@sm o-grid-4@md o-grid-3@lg';
        }

        $cached = isset($_GET['flush']) ? false : true;
        $feed = $this->getFeed($data['embedCode'], $data['numberOfItems'] + 1, 0, $cached);

        //Parse feed
        $data['showFeed'] = false;

        if (!empty($feed->posts)) {
            $data['posts']    = $feed->posts;
            $data['postCount'] = $feed->postCount;

            $data['showFeed'] = true;
        }
        //Parse posts array
        $data['posts'] = is_array($data['posts']) ? self::parseSocialMediaPosts($data['posts']) : [];

        //Could not fetch error message / embed code error message
        if (!$data['embedCode']) {
            $data['errorMessage'] = __("An invalid embed code was provided.", 'modularity');
        } else {
            $data['errorMessage'] = __("Could not get the feed at this moment, please try again later.", 'modularity');
        }

        //Send to view
        return $data;
    }
    /**
     * Parses the social media posts data to add additional properties and modify existing ones.
     *
     * @param array $posts The social media posts data to parse.
     *
     * @return array The parsed social media posts data.
     */
    public static function parseSocialMediaPosts(array $posts = []): array
    {
        if (is_array($posts) && !empty($posts)) {
            foreach ($posts as $key => $post) {
                if ('curator_io' === $post->user_screen_name || 'https://curator.io' === $post->url) {
                    unset($posts[$key]);
                    continue;
                }

                $post->full_text = $post->text;

                $post->user_readable_name = self::getUserName($post->user_screen_name);
                $post->text = wp_trim_words($post->text, 20, "...");

                // Prepare oembed
                if (in_array($post->network_name, ['YouTube', 'Vimeo'], true)) {
                    global $wp_embed;
                    $post->oembed = $wp_embed->shortcode([], $post->url);
                }
                // Format date
                $post->formatted_date = date_i18n('j M. Y', strtotime($post->source_created_at));
                // Set title
                if (!empty($post->data) && empty($post->title)) {
                    foreach ($post->data as $item) {
                        if ($item->name == 'title') {
                            $post->title = $item->value;
                        }
                    }
                }

                $posts[$key] = $post;
            }
        }
        return $posts;
    }

    /**
     * Parse embed javascript to get the embed code
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
    public static function getUserName(string $userName = ''): string
    {
        return ucwords(str_replace(['.', '-'], ' ', $userName));
    }
    /**
     * Retrieves social media feed data for the given embed code from Curator.io.
     *
     * @see https://curator.io/docs/api
     *
     * @param string $embedCode The embed code for the social media feed.
     * @param int $numberOfItems The number of social media posts to retrieve.
     * @param int $offset The offset of the first post to retrieve.
     * @param bool $cache Whether to use the WordPress transient cache for caching the feed response.
     *
     * @return object The social media feed data as an object.
     */
    public function getFeed(string $embedCode = '', int $numberOfItems = 13, int $offset = 0, bool $cache = true)
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            if (!empty($_POST['embed-code'])) {
                $embedCode     = $_POST['embed-code'];
                $numberOfItems = (int) $_POST['limit'] + 1;
                $offset        = $_POST['offset'];
            } else {
                wp_die('embed code not found');
            }
        }
        // $embedCode     = $this->parseEmbedCode(get_field('embed_code', $this->ID));
        $requestUrl = "https://api.curator.io/restricted/feeds/{$embedCode}/posts";

        $requestArgs = [
            'headers' => [
                'Content-Type: application/json',
            ],
            'body' => [
                'limit'        => $numberOfItems,
                'offset'       => $offset,
                'hasPoweredBy' => 1,
                'version'      => '4.0',
                'status'       => 1
            ]
        ];

        $transientKey = '_modularity_curator_social_media_feed_' . $embedCode;
        if (false === ($feed = get_transient($transientKey)) && true === $cache) {
            $response = wp_remote_retrieve_body(wp_remote_get($requestUrl, $requestArgs));
            $feed = set_transient($transientKey, $response, 5 * \MINUTE_IN_SECONDS);
        } else {
            $feed = wp_remote_retrieve_body(wp_remote_get($requestUrl, $requestArgs));
        }

        if (defined('DOING_AJAX') && DOING_AJAX) {
            echo $feed;
            wp_die();
        } else {
            return json_decode($feed);
        }
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
