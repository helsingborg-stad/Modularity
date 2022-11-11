<?php

namespace Modularity\Module\Video;

class Video extends \Modularity\Module
{
    public $slug = 'video';
    public $supports = array();
    private $imageLocations = [
        'youtube'   => 'https://img.youtube.com/vi/%s/maxresdefault.jpg',
        'vimeo'     => 'https://vumbnail.com/%s.jpg'
    ];

    public function init()
    {
        $this->nameSingular = __("Video", 'modularity');
        $this->namePlural = __("Video", 'modularity');
        $this->description = __("Outputs an embedded Video.", 'modularity');

        //Cover images
        add_action('save_post_mod-' . $this->slug, array($this, 'getVideoCover'), 10, 3);
    }

    private function shouldSave() {
        //Bail early if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        //Bail early if cron
        if (defined('DOING_CRON') && DOING_CRON) {
            return false;
        }

        return true;
    }

    /**
     * Detect video type
     */

    private function detectVideoService($string)
    {
        if (str_contains($string, 'vimeo')) {
            return 'vimeo';
        }
        if (str_contains($string, 'youtube')) {
            return 'youtube';
        }
        return false;
    }

    public function getVideoCover($postId, $post, $isUpdate)
    {
        if (!$this->shouldSave()) {
            return false;
        }

        $coverImage = get_field('placeholder_image', $postId);
        $embedUrl   = get_field('embed_link', $postId);

        if ($coverImage === false && filter_var($embedUrl, FILTER_VALIDATE_URL) !== false) {
            $videoService   = $this->detectVideoService($embedUrl);
            $videoId        = $this->getVideoId($embedUrl, $videoService);
            $coverImage     = $this->getCoverUrl($videoId, $videoService);

            $filePath = $this->downloadCoverImage($coverImage, $videoId);

            if ($filePath) {
                update_post_meta(
                    $postId,
                    'placeholder_fallback_image',
                    $this->getUploadsSubdir() . $filePath
                );
                return $filePath;
            }
        }

        delete_post_meta($postId, 'placeholder_fallback_image');

        return false;
    }

    private function downloadCoverImage($url, $videoId) {
        if ($fileContents = $this->readRemoteFile($url)) {
            return $this->storeImage($fileContents, $videoId);
        }
        return false;
    }

    private function readRemoteFile($url)
    {
        $responseHandle = wp_remote_get($url);

        if (!is_wp_error($responseHandle)) {
            $responseCode = wp_remote_retrieve_response_code($responseHandle);
            $responseMime = wp_remote_retrieve_header($responseHandle, 'content-type');
            $responseBody = wp_remote_retrieve_body($responseHandle);

            if ($responseCode == 200 && in_array($responseMime, ['image/jpeg', 'image/jpg'])) {
                return $responseBody;
            }
        }
        return false;
    }

    private function storeImage($fileContent, $videoId)
    {
        $uploadsDir = $this->getUploadsDir();
        $fileSystem = $this->initFileSystem();
        $filename   = $videoId . ".jpg";

        $fileSystem->put_contents(
            $uploadsDir . "/" . $filename,
            $fileContent,
            FS_CHMOD_FILE
        );

        if ($fileSystem->exists($uploadsDir . "/" . $filename)) {
            return $filename;
        }

        return false;
    }

    private function initFileSystem()
    {
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
        global $wp_filesystem;
        return $wp_filesystem;
    }

    private function getUploadsDir()
    {
        return wp_upload_dir()['path'];
    }

    private function getUploadsSubdir()
    {
        return wp_upload_dir()['subdir'];
    }

    private function getVideoId($embedLink, $videoService)
    {
        if ($videoService == 'youtube') {
            return $this->parseYoutubeId($embedLink);
        }

        if ($videoService == 'vimeo') {
            return $this->parseVimeoId($embedLink);
        }

        return false;
    }

    private function parseYoutubeId($embedLink) {
        parse_str(
            parse_url($embedLink, PHP_URL_QUERY),
            $queryParameters
        );

        if (isset($queryParameters['v']) && !empty($queryParameters['v'])) {
            return $queryParameters['v'];
        }

        return false;
    }

    private function parseVimeoId($embedLink)
    {
        $parts = explode('/', $embedLink);

        if (is_array($parts) & !empty($parts)) {
            foreach ($parts as $part) {
                if (is_numeric($part)) {
                    return $part;
                }
            }
        }
        return false;
    }

    private function getCoverUrl($id, $videoService) {
        if (isset($this->imageLocations[$videoService])) {
            return sprintf($this->imageLocations[$videoService], $id);
        }
        return false;
    }

    /**
     * Manage view data
     *
     * @return array
     */
    public function data(): array
    {
        $data = get_fields($this->ID);


        //Embed code
        $data['embedCode'] = $this->getEmbedMarkup($data['embed_link']);

        $data['id'] = uniqid('embed');

        // Image
        $data['image'] = false;
        if (! isset($data['embedCode']) && isset($data['placeholder_image']) && !empty($data['placeholder_image'])) {
            $data['image'] = wp_get_attachment_image_src(
                $data['placeholder_image']['id'],
                apply_filters(
                    'Modularity/video/image',
                    municipio_to_aspect_ratio('16:9', array(1140, 641)),
                    $this->args
                )
            )[0];
        }

        // Fallback image
        if (!$image) {
            $fallbackImage = get_post_meta($this->ID, 'placeholder_fallback_image', true);
            if ($fallbackImage) {
                $data['image'] =  wp_get_upload_dir()['baseurl'] . $fallbackImage;
            }
        }

        //Uploaded
        if ($data['type'] == 'upload') {
            $data['source'] = $data['video_mp4']['url'];
        }

        //Lang
        $data['lang'] = (object) [
            'embedFailed' => __('This video could not be embedded. <a href="%s" target="_blank">View the video by visiting embedded page.</a>', 'modularity'),
        ];

        return $data;
    }

    /**
     * Embed
     *
     * @param [type] $embedLink
     * @return bool|string
     */
    private function getEmbedMarkup($embedLink)
    {
        return wp_oembed_get($embedLink, array( 'width' => 1080, 'height' => 720));
    }

    public function style()
    {
        wp_register_style('mod-video-style', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('css/video.css'));

        wp_enqueue_style('mod-video-style');
    }

    public function script()
    {
        wp_register_script('mod-video-script', MODULARITY_URL . '/dist/'
        . \Modularity\Helper\CacheBust::name('js/video.js'));

        wp_enqueue_script('mod-video-script');
    }

    private function accessProtected($obj, $prop) {
        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
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