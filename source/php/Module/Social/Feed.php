<?php

namespace Modularity\Module\Social;

class Feed
{
    public $args = array();

    protected $feedData = array();
    protected $markup = '';

    public function __construct($args = array())
    {
        $defaultArgs = array(
            'network'    => 'instagram',
            'type'       => 'hashtag',
            'query'      => 'sweden',
            'length'     => 10,
            'max_height' => 300,
            'row_length' => false,
            'api_user'   => '',
            'api_secret' => ''
        );

        $this->args = array_merge($defaultArgs, $args);

        /**
         * Get feed depending on args
         */
        switch ($this->args['network']) {
            case 'instagram':
                if ($this->args['type'] == 'hashtag') {
                    $this->feedData = $this->getInstagramHashtag();
                } else {
                    $this->feedData = $this->getIstagramUserProfileFeed();
                }
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

    /**
     * Get Instagram self-user feed
     * @return object Feed data
     * @since 1.3.96
     * @deprecated depricated since 1.4.2, 23 Jun 2016
     */
    protected function getInstagramSelfFeed()
    {
        $endpoint = 'https://api.instagram.com/v1/users/self/media/recent/';
        $data = array(
            'access_token' => $this->args['api_secret']
        );

        $curl = new \Modularity\Helper\Curl();
        $recent = $curl->request('GET', $endpoint, $data);
        return json_decode($recent);
    }

    /**
     * Get Instagram user profile feed (/user/media/ un-documented endpoint)
     * @return object Feed data
     * @since 1.4.2
     */
    protected function getIstagramUserProfileFeed()
    {
        $endpoint   = 'https://www.instagram.com/'.$this->args['query'].'/media/';

        $curl       = new \Modularity\Helper\Curl();
        $recent     = $curl->request('GET', $endpoint, array());
        $recent     = json_decode($recent);

        //Rename object
        if (isset($recent->items) && !isset($recent->data)) {
            $recent->data = $recent->items;
            unset($recent->items);
        }

        return $recent;
    }

    /**
     * Get Instagram hashtag feed
     * @return object Feed data
     */
    protected function getInstagramHashtag()
    {
        //Fallback to public api key
        if (empty($this->args['api_user'])) {
            $this->args['api_user'] = "1406045013.3a81a9f.7c505432dfd3455ba8e16af5a892b4f7";
        }

        //Structure url to call
        $endpoint = 'https://api.instagram.com/v1/tags/'.$this->args['query'].'/media/recent/';
        $data = array(
            'access_token' => $this->args['api_user']
        );

        //Call and return
        $curl = new \Modularity\Helper\Curl();
        $recent = $curl->request('GET', $endpoint, $data);
        return json_decode($recent);
    }

    /**
     * Get Instagram user feed
     * @return object Feed data
     * @deprecated depricated since 1.3.96, 3 Jun 2016
     */
    protected function getInstagramUser()
    {
        $userId = $this->getInstagramUserId($this->args['query']);

        $endpoint = 'https://api.instagram.com/v1/users/' . $userId . '/media/recent/';
        $data = array(
            'client_id' => $this->args['api_user'],
            'access_token' => $this->args['api_secret']
        );

        $curl = new \Modularity\Helper\Curl();
        $recent = $curl->request('GET', $endpoint, $data);
        return json_decode($recent);
    }

    /**
     * Get Instagram user ID from username
     * @param  string   $username Username
     * @return integer            User ID
     * @deprecated depricated since 1.3.96, 3 Jun 2016
     */
    protected function getInstagramUserId($username)
    {
        $endpoint = 'https://api.instagram.com/v1/users/search';
        $data = array(
            'q' => $username,
            'client_id' => $this->args['api_user'],
            'access_token' => $this->args['api_secret']
        );

        $curl = new \Modularity\Helper\Curl();
        $users = $curl->request('GET', $endpoint, $data);
        $users = json_decode($users);

        $userId = null;

        foreach ($users->data as $user) {
            if ($user->username == $username) {
                return $user->id;
            }
        }

        return false;
    }

    public function render()
    {
        switch ($this->args['network']) {
            case 'instagram':
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-gallery social-feed-instagram social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderInstagram();
                break;

            case 'facebook':
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-feed social-feed-facebook social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderFacebook();
                break;

            case 'twitter':
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-feed social-feed-twitter social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderTwitter();
                break;

            case 'pinterest':
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-gallery social-feed-pinterest social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderPinterest();
                break;

            case 'googleplus':
                $this->markup .= '<ul style="max-height:' . $this->args['max_height'] . 'px" class="social-feed social-feed-feed social-feed-facebook social-feed-' . $this->args['type'] . '" data-query="' . $this->args['query'] . '">';
                $this->renderGooglePlus();
                break;
        }

        $this->markup .= '</ul>';

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

        if (isset($this->feedData->meta->error_message) || !isset($this->feedData->data)) {
            $msg = 'No error message, sorry about that.';

            if (isset($this->feedData->meta->error_message)) {
                $msg = $this->feedData->meta->error_message;
            }

            $this->addError($msg);
            return;
        }

        foreach ($this->feedData->data as $item) {
            $int++;

            $this->addImage(
                $item->created_time,
                array(
                    'name' => $item->user->username,
                    'picture' => $item->user->profile_picture
                ),
                $item->images->low_resolution->url,
                isset($item->caption->text) ? $item->caption->text : null,
                $item->link
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

        if(!empty($this->feedData)) {

            foreach ((array) $this->feedData as $item) {
                $int++;

                $date = new \DateTime($item->created_time);
                $timeZone = new \DateTimeZone(get_option('timezone_string'));
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
