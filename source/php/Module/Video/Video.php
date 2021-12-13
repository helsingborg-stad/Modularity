<?php

namespace Modularity\Module\Video;

class Video extends \Modularity\Module
{
    public $slug = 'video';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Video", 'modularity');
        $this->namePlural = __("Video", 'modularity');
        $this->description = __("Outputs an embedded Video.", 'modularity');
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

        // Image
        $data['image'] = false;
        if (isset($data['placeholder_image']) && !empty($data['placeholder_image'])) {
            $data['image'] = wp_get_attachment_image_src(
                $data['placeholder_image']['id'],
                apply_filters(
                    'Modularity/video/image',
                    municipio_to_aspect_ratio('16:9', array(1140, 641)),
                    $this->args
                )
            );
        }

        //Uploaded
        $data['source'] = $data['video_mp4']['url'];

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
        return wp_oembed_get($embedLink, array( 'width' => 1080, 'height' => 720 ));
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
