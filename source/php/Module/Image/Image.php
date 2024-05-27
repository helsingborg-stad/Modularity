<?php

namespace Modularity\Module\Image;

use Municipio\Helper\Image as ImageHelper;

class Image extends \Modularity\Module
{
    public $slug = 'image';
    public $supports = array();
    public $isBlockCompatible = false;

    /**
     * Init the module, give it a name etc.
     * @return void
     */
    public function init()
    {
        $this->nameSingular = __('Image', 'modularity');
        $this->namePlural = __('Images', 'modularity');
        $this->description = __('Outputs an image', 'modularity');

        add_action('acf/load_field/name=mod_image_size', array($this, 'appendImageSizes'));
    }

    /**
     * Setup data
     * @return array
     */
    public function data() : array
    {

        //Get data
        $data = [];
        $fields = $this->getFields();
        
        $image = $this->getImageData($fields, $this->getImageSize($fields)); 

        if($image) {
            $data['image'] = $image;
            $data['image']['caption'] = $this->getImageCaption(
                $fields, 
                $data['image']
            );
        } else {
            $data['image'] = false;
        }
        
        $data['imageLink'] = $this->imageHasLink($fields) ? $fields['mod_image_link_url'] : false;

        return $data;
    }

    /**
     * Get all data attached to the image.
     * 
     * @param array $fields All the acf fields
     * @param array|string $size Array containing height and width OR predefined size as a string.
     * @return array
     */
    private function getImageData(array $fields, $size)
    {
        $imageId = $fields['mod_image_image']['ID'];
        return ImageHelper::getImageAttachmentData($imageId, $size);
    }
    
    /**
     * Get all data attached to the image.
     * 
     * @param array $fields All the acf fields
     * @return array|string
     */
    private function getImageSize(array $fields) {
        $size = !empty($fields['mod_image_size']) ? $fields['mod_image_size'] : 'medium_large';

        if ($this->hasCustomImageSize($fields)) {
            $size = [
                $fields['mod_image_crop_width'],
                $fields['mod_image_crop_height']
            ];
        }
        
        return $size;
    }

    /**
     * If the image has a custom size.
     * 
     * @param array $fields All the acf fields
     * @return bool
     */
    private function hasCustomImageSize(array $fields) {
        return !empty($fields['mod_image_size']) && $fields['mod_image_size'] === "custom" && !empty($fields['mod_image_crop_width']) && !empty($fields['mod_image_crop_height']);
    }

         /**
     * If the image should be a link or not.
     * 
     * @param array $fields All the acf fields
     * @param array $image All the data attached to the image
     * @return string|false
     */
    private function getImageCaption(array $fields, array $image) {
        $caption = false;

        if (!empty($image['caption'])) {
            $caption = $image['caption'];
        }

        if (!empty($fields['mod_image_caption'])) {
            $caption = $fields['mod_image_caption'];
        }
        
        return $caption;
    }

    /**
     * If the image should be a link or not.
     * 
     * @param array $fields All the acf fields
     * @return bool
     */
    private function imageHasLink(array $fields) {
        return !empty($fields['mod_image_link']) && $fields['mod_image_link'] != "false" && !empty($fields['mod_image_link_url']);
    }

    /**
     * Creates a list of predefined sizes to choose from
     * @return array
     */
     public function appendImageSizes(array $field)
     {
         $sizes = get_intermediate_image_sizes();
         foreach ($sizes as $size) {
             $field['choices'][$size] = $size;
         }
 
         return $field;
     }

    /**
     * Choose appropriate style
     * @return string
     */

    public function template()
    {
        return 'default.blade.php';
    }
}
