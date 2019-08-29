<?php

namespace Modularity\Module\Social;

class Feed
{
    public $args = array();

    protected $feedData = array();
    protected $markup = '';

    private $username;
    private $hashtag;

    //Instagram
    private $cache;
    private $baseUrl = 'https://instagram.com/';

    public function __construct($args = array())
    {

        $defaultArgs = array(
            'network'    => 'instagram',
            'type'       => 'user',
            'query'      => 'sweden',
            'length'     => 10,
            'max_height' => 300,
            'row_length' => false,
            'api_user'   => '',
            'api_secret' => '',
            'page_link'  => false,
            'link_url'   => '',
            'link_text'  => ''
        );



        $this->args = array_merge($defaultArgs, $args);

        /**
         * Get feed depending on args
         */
        switch ($this->args['network']) {
            case 'instagram':
                $this->feedData = $this->getInstagramUser($this->args['query']);
                break;

            case 'facebook':
                $this->feedData = $this->getFacebookUser();
                break;

            case 'twitter':
                if ($this->args['type'] == 'hashtag') {
                    $this->feedData = $this->getTwitterHashtag();
                } else {
                    $this->feedData = $this->getTwitterUser();
                }
                break;

            case 'pinterest':
                $this->feedData = $this->getPinterestUser();
                break;

            case 'googleplus':
                $this->feedData = $this->getGooglePlusFeed();
                break;
        }
    }

    public function getGooglePlusFeed()
    {
        $key = $this->args['api_user'];
        $getUser = urlencode($this->args['query']);

        $endpoint = 'https://www.googleapis.com/plus/v1/people/' . $getUser . '/activities/public?key=' . $key;

        $curl = new \Modularity\Helper\Curl();
        $response = $curl->request('GET', $endpoint);
        $response = json_decode($response);

        return array_slice($response->items, 0, $this->args['length']);
    }

    public function renderGooglePlus()
    {
        foreach ($this->feedData as $item) {
            $attachment = null;

            if (isset($item->object->attachments[0])) {
                $attachment = array(
                    'type'         => $item->object->attachments[0]->objectType,
                    'status_type'  => $item->object->attachments[0]->objectType,
                    'name'         => $item->object->attachments[0]->displayName,
                    'description'  => '',
                    'caption'      => $item->object->attachments[0]->content,
                    'link'         => $item->object->attachments[0]->url,
                    'full_picture' => $item->object->attachments[0]->image->url
                );
            }

            $this->addStory(
                strtotime($item->published),
                array(
                    'name' => $item->actor->displayName,
                    'picture' => $item->actor->image->url
                ),
                $item->object->content,
                $attachment
            );
        }
    }

    /**
     * Get Pinterest user's feed
     * @return object Feed
     */
    public function getPinterestuser()
    {
        $endpoint = 'https://api.pinterest.com/v3/pidgets/users/' . $this->args['query'] . '/pins/';

        $curl = new \Modularity\Helper\Curl();
        $response = $curl->request('GET', $endpoint);

        return json_decode($response)->data->pins;
    }

    /**
     * Get Twitter Access Token
     * @return string Access token
     */
    public function getTwitterAccessToken()
    {
        $consumer_key    = urlencode($this->args['api_user']);
        $consumer_secret = urlencode($this->args['api_secret']);

        // Concatenate key and secret and base64 encode
        $bearer_token = $consumer_key . ':' . $consumer_secret;
        $base64_bearer_token = base64_encode($bearer_token);

        // Request access token
        $endpoint = 'https://api.twitter.com/oauth2/token';

        // Request headers
        $headers = array(
            "POST /oauth2/token HTTP/1.1",
            "Host: api.twitter.com",
            "User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
            "Authorization: Basic " . $base64_bearer_token,
            "Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
        );

        // Postdata
        $data = array(
            'grant_type' => 'client_credentials'
        );

        // Curl and format response
        $curl = new \Modularity\Helper\Curl();
        $response = $curl->request('POST', $endpoint, $data, null, $headers);
        $response = json_decode($response);

        if (isset($response->errors)) {
            return $response;
        }

        return $response->access_token;
    }

    /**
     * Get Twitter hashtag
     * @return object Feed data
     */
    public function getTwitterHashtag()
    {
        $access_token = $this->getTwitterAccessToken();

        if (isset($access_token->errors[0])) {
            return $access_token->errors[0];
        }

        $endpoint = 'https://api.twitter.com/1.1/search/tweets.json';
        $data = array(
            'access_token'     => $access_token,
            'q'                => urlencode('#' . $this->args['query']),
            'count'            => $this->args['length'],
            'exclude_replies'  => true,
            'include_rts '     => false,
            'result_type'      => 'recent',
            'lang'             => 'sv'
        );

        // Request headers
        $headers = array(
            "GET /1.1/search/tweets.json" . http_build_query($data) . " HTTP/1.1",
            "Host: api.twitter.com",
            "User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
            "Authorization: Bearer " . $access_token
        );

        // Curl
        $curl = new \Modularity\Helper\Curl();
        $tweets = $curl->request('GET', $endpoint, $data, 'JSON', $headers);

        return json_decode($tweets)->statuses;
    }

    /**
     * Get a Twitter user's feed
     * @return object Feed data
     */
    public function getTwitterUser()
    {
        $access_token = $this->getTwitterAccessToken();

        if (isset($access_token->errors[0])) {
            return $access_token->errors[0];
        }

        // Request statuses
        $endpoint = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $data = array(
            'access_token'     => $access_token,
            'screen_name'      => $this->args['query'],
            'count'            => $this->args['length'],
            'exclude_replies'  => true,
            'include_rts '     => false
        );

        // Request headers
        $headers = array(
            "GET /1.1/search/tweets.json" . http_build_query($data) . " HTTP/1.1",
            "Host: api.twitter.com",
            "User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
            "Authorization: Bearer " . $access_token
        );

        // Curl
        $curl = new \Modularity\Helper\Curl();
        $tweets = $curl->request('GET', $endpoint, $data, 'JSON', $headers);

        return json_decode($tweets);
    }

    /**
     * Get a Facebook user's feed
     * @return object Feed
     */
    public function getFacebookUser()
    {

        /**
         * Request a token from Facebook Graph API
         */
        $endpoint = 'https://graph.facebook.com/oauth/access_token';
        $data = array(
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->args['api_user'],
            'client_secret' => $this->args['api_secret']
        );

        $curl = new \Modularity\Helper\Curl();
        $token = $curl->request('GET', $endpoint, $data);

        if (strpos($token, 'error') !== false) {
            $error = json_decode($token);
            return $error;
        }

        //Decode token
        $token = json_decode($token);

        /**
         * Request the posts
         */
        $endpoint = 'https://graph.facebook.com/' . $this->args['query'] . '/posts';
        $data = array(
            'access_token' => $token->access_token,
            'fields'       => 'from, full_picture, picture, message, created_time, object_id, link, name, caption, description, icon, type, status_type, likes'
        );
        $feed = $curl->request('GET', $endpoint, $data);

        return json_decode($feed)->data;
    }

    public function getInstagramUser($username)
    {
        if (is_null($this->cache)) {
            $curl = new \Modularity\Helper\Curl(true, 120);
            $curl->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            $this->cache = $this->parseInstagramMarkup($curl->request('GET', $this->baseUrl . $username));
        }

        $response = array();

        if (isset($this->cache->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges) && $items = $this->cache->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges) {
            if (!empty($items) && is_array($items)) {
                foreach ($items as $item) {

                    $item = $item->node;

                    $response[] = array(
                      'id' => $item->id . "-instagram",
                      'user_name' => $this->getInstagramProfile()['name'],
                      'profile_pic' => $this->getInstagramProfile()['profilepic'],
                      'timestamp' => $item->taken_at_timestamp,
                      'content' => wp_trim_words(str_replace("#", " #", $item->edge_media_to_caption->edges[0]->node->text), 40, "..."),

                      'image_large' => $item->thumbnail_resources[4]->src,
                      'image_small' => $item->thumbnail_resources[0]->src,

                      'number_of_likes' => (isset($item->edge_liked_by) && isset($item->edge_liked_by->count) ? ($item->edge_liked_by->count ? $item->edge_liked_by->count : 0) : 0),
                      'network_source' => 'https://www.instagram.com/p/'.$item->shortcode.'/',
                      'network_name' => 'instagram',

                      'link' => "",
                      'link_title' => "",
                      'link_content' => "",
                      'link_og_image' => "",
                    );
                }
            }
        }

        return $response;
    }

    /**
     * Request the user profile
     * @param string $username The usernanme to fetch profile of
     * @return array/bool The data fetched from the service api or false if none
     */

    private function getInstagramProfile() : array
    {
        if (isset($this->cache->entry_data->ProfilePage[0]->graphql->user)) {
            $user = $this->cache->entry_data->ProfilePage[0]->graphql->user;
            return array(
                'name' => $user->full_name,
                'user_name' => $user->username,
                'profilepic_sd' => $user->profile_pic_url,
                'profilepic' => $user->profile_pic_url_hd,
                'biography' => $user->biography,
            );
        }
        return array();
    }

    /**
    * Parse data recived
    * @param  $markup Raw data from webpage
    * @return Array with raw feed data
    */
    public function parseInstagramMarkup($markup)
    {
        //Define what to get
        $startTag = '<script type="text/javascript">window._sharedData = ';
        $endTag   = ';</script>';

        //Match string with reguklar exp
        $hasMatch = preg_match(
                      "#" . preg_quote($startTag, "#")
                      . '(.*?)'
                      . preg_quote($endTag, "#")
                      . "#"
                      . 's', $markup, $matches);

        //Return matches (if valid json)
        if ($hasMatch && isset($matches[0])) {
            $matches = str_replace($startTag, "", $matches[0]);
            $matches = str_replace($endTag, "", $matches);

            return json_decode($matches);
        }

        //Nothing found, return false.
        return false;
    }

    public function render()
    {
        switch ($this->args['network']) {
            case 'instagram':
            $this->markup .= '<div class="social-feed-wrapper">';
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-gallery social-feed-instagram social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';

                $this->renderInstagram();

                break;

            case 'facebook':
                $this->markup .= '<div class="box-content">';
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-feed social-feed-facebook social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderFacebook();
                break;

            case 'twitter':
                $this->markup .= '<div class="box-content">';
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-feed social-feed-twitter social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderTwitter();
                break;

            case 'pinterest':
                $this->markup .= '<div class="social-feed-wrapper">';
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-gallery social-feed-pinterest social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderPinterest();
                break;

            case 'googleplus':
                $this->markup .= '<div class="box-content">';
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-feed social-feed-facebook social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderGooglePlus();
                break;
        }

        $this->markup .= '</ul>';

        if ($this->args['page_link']) {
            $this->markup .= '<div class="social-feed-button"><a href="' . $this->args['link_url'] . '" target="_blank" class="btn btn-primary btn-lg btn-block">' . $this->args['link_text'] . '</a></div>';
        }

        $this->markup .= '</div>';

        echo $this->markup;
    }

    /**
     * Render Pinterest images
     * @return void
     */
    protected function renderPinterest()
    {
        $int = 0;

        foreach ($this->feedData as $item) {
            $int++;

            $this->addImage(
                null,
                array(
                    'name' => $item->pinner->full_name,
                    'picture' => $item->pinner->image_small_url
                ),
                $item->images->{'237x'}->url,
                isset($item->description) ? $item->description : null,
                $item->link
            );

            if ($int == $this->args['length']) {
                break;
            }
        }
    }

    /**
     * Render Instagram images
     * @return void
     */
    protected function renderInstagram()
    {

        $int = 0;

        if (isset($this->feedData->meta->error_message) || !isset($this->feedData)) {
            $msg = 'No error message, sorry about that.';

            if (isset($this->feedData->meta->error_message)) {
                $msg = $this->feedData->meta->error_message;
            }

            $this->addError($msg);
            return;
        }

        foreach ($this->feedData as $item) {
            $int++;

            $this->addImage(
                $item['timestamp'],
                array(
                    'name' => $item['user_name'],
                    'picture' => ''
                ),
                $item['image_large'],
                isset($item['content']) ? $item['content'] : null,
                $item['network_source']
            );

            if ($int == $this->args['length']) {
                break;
            }
        }
    }

    /**
     * Renders Facebook posts
     * @return void
     */
    protected function renderFacebook()
    {
        $int = 0;

        if (isset($this->feedData->error->message)) {
            $this->addError($this->feedData->error->message);
            return;
        }

        if (!empty($this->feedData)) {
            foreach ((array) $this->feedData as $item) {
                $int++;

                $date = new \DateTime($item->created_time);
                $timeZone = new \DateTimeZone(!empty(get_option('timezone_string')) ? get_option('timezone_string') : date_default_timezone_get());
                $date->setTimezone($timeZone);

                $this->addStory(
                    strtotime($date->format('Y-m-d H:i:s')),
                    array(
                        'name' => $item->from->name,
                        'picture' => '//graph.facebook.com/' . $item->from->id . '/picture?type=large'
                    ),
                    isset($item->message) ? $item->message : '',
                    array(
                        'type'         => isset($item->type) ? $item->type : null,
                        'status_type'  => isset($item->status_type) ? $item->status_type : null,
                        'name'         => isset($item->name) ? $item->name : null,
                        'description'  => isset($item->description) ? $item->description : null,
                        'caption'      => isset($item->caption) ? $item->caption : null,
                        'link'         => isset($item->link) ? $item->link : null,
                        'full_picture' => isset($item->full_picture) ? $item->full_picture : null
                    )
                );

                if ($int == $this->args['length']) {
                    break;
                }
            }
        }
    }

    /**
     * Renders a Twitter post item
     * @return void
     */
    protected function renderTwitter()
    {
        if (isset($this->feedData->message)) {
            $this->addError($this->feedData->message);
            return;
        }

        foreach ($this->feedData as $item) {
            $date = new \DateTime($item->created_at);
            $timeZone = new \DateTimeZone(get_option('timezone_string'));
            $date->setTimezone($timeZone);

            $this->addStory(
                strtotime($date->format('Y-m-d H:i:s')),
                array(
                    'name' => $item->user->name,
                    'picture' => $item->user->profile_image_url
                ),
                $item->text
            );
        }
    }

    protected function addImage($createdTime, $user, $image, $caption, $link)
    {
        $rowWidth = '';
        if (isset($this->args['row_length']) && is_numeric($this->args['row_length'])) {
            $rowWidth = round(100/$this->args['row_length'], 4) . '%';
            $item = '<li style="width:' . $rowWidth . '">';
        } else {
            $item = '<li>';
        }

        $time = '';
        if (isset($createdTime) && !empty($createdTime)) {
            $time = '<time datetime="' . date('Y-m-d H:i', $createdTime) . '">' . human_time_diff($createdTime, current_time('timestamp')) . ' '  . __('ago', 'modularity') . '</time>';
        }

        $item .= '
            <a href="' . $link . '" target="_blank" class="mod-social-image" style="background-image:url(' . $image . ');" class="mod-social-image">
                <img src="' . $image . '" alt="' . $user['name'] . '" class="mod-social-attachment-image">
                <div class="mod-social-user">
                    <img src="' . $user['picture'] . '" alt="' . $user['name'] . '">
                    <span>' . $user['name'] . '</span>
                    ' . $time .'
                </div>
                <div class="mod-social-story">
                    ' . wpautop($caption) . '
                </div>
            </a>
        </li>';

        $this->markup .= apply_filters('Modularity/mod_social/image', $item, $createdTime, $user, $image, $caption);
    }

    protected function addError($text)
    {
        $this->markup .= '<li class="error"><strong>Error:</strong> ' . $text . '</li>';
    }

    /**
     * Adds a story
     * @param timestamp $createdTime Created date timestamp
     * @param array     $user        User name and picture
     * @param string    $text        The text
     */
    protected function addStory($createdTime, $user, $text, $attachment = false)
    {
        $item = '
            <li>
                <div class="mod-social-user">
                    <img src="' . $user['picture'] . '" alt="' . $user['name'] . '">
                    <div>
                        <span>' . $user['name'] . '</span>
                        <time>' . human_time_diff($createdTime, current_time('timestamp')) . ' ' . __('ago', 'modularity') . '</time>
                    </div>
                </div>
                <div class="mod-social-story">
                    ' . wpautop($text) . '
                </div>
        ';

        if (is_array($attachment)) {
            $item .= $this->addAttachment($attachment);
        }

        $item .= '</li>';

        $this->markup .= apply_filters('Modularity/mod_social/story', $item, $createdTime, $user, $text);
    }

    protected function addAttachment($attachment)
    {
        $description = null;

        if ($attachment['description'] !== null) {
            $description = '<p>' . $attachment['description'] . '</p>';
        } else {
            return;
        }

        $att = '
            <a href="' . $attachment['link'] . '" target="_blank" class="mod-social-attachment mod-social-attachment-' . $attachment['type'] . ' mod-social-attachment-type-' . $attachment['status_type'] . '">
                <img src="' . $attachment['full_picture'] . '" class="mod-social-attachment-image" alt="' . $attachment['name'] . '">
                <div class="mod-social-attachment-content">
                    <h4>' . $attachment['name'] . '</h4>
                    ' . $description . '
                    <span class="caption">' . $attachment['caption'] . '</span>
                </div>
            </a>
        ';

        return $att;
    }

    /**
     * Returns the feed
     * @return array Feed
     */
    public function getFeedData()
    {
        return $this->feedData;
    }
}
