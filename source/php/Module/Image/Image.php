<?php

namespace Modularity\Module\Image;

use Municipio\Helper\Image as ImageHelper;
use Modularity\Integrations\Component\ImageResolver;
use Modularity\Integrations\Component\ImageFocusResolver;
use ComponentLibrary\Integrations\Image\Image as ImageComponentContract;

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

        $image = $this->getImageData(
            $fields, 
            $this->getImageSize($fields)
        ); 

        if($image) {
            $data['image'] = $image;

            //Resolve caption
            $data['image']['caption'] = $this->getImageCaption(
                $fields, 
                $data['image']
            );

            //Get image size & id
            $imageId = $this->getImageId($fields);
            $imageSize = $this->getImageSize($fields);
    
            //Resolve image
            if($imageSize !== null && $imageId !== null) {
                $resolvedImage = ImageComponentContract::factory(
                    $imageId,
                    $imageSize,
                    new ImageResolver(),
                    new ImageFocusResolver('test')
                );

                $data['image']['src'] = $resolvedImage;
            } else {
                $data['image'] = false;
            }
        } else {
            $data['image'] = false;
        }
        
        $data['imageLink'] = $this->imageHasLink($fields) ? $fields['mod_image_link_url'] : false;

        return $data;
    }

    /**
     * Get the image size
     * 
     * @param array $fields All the acf fields
     * 
     * @return array|null
     */
    private function getImageSize($fields): ?array {

        //Return custom image size if set
        if ($this->hasCustomImageSize($fields)) {
            $size = [
                $fields['mod_image_crop_width'] ?? false,
                $fields['mod_image_crop_height'] ?? false
            ];
        }

        //Return predefined image size
        if ($this->hasPredefinedImageSize($fields)) {
            $size = $this->getRegisteredImageSize($fields['mod_image_size'], $fields);
        }

        //Normalize types in array
        if(is_array($size) && !empty($size)) {
            array_walk($size, function(&$value) {
                $value = is_numeric($value) ? (int)$value : (bool)$value;
            });
            return $size;
        }

        return null;
    }

    /**
     * If the image has a predefined size.
     * 
     * @param array $fields All the acf fields
     * @return bool
     */
    private function hasPredefinedImageSize(array $fields): bool {
        return !empty($fields['mod_image_size']) && $fields['mod_image_size'] !== "custom";
    }

    /**
     * Check if the image has a custom size.
     *
     * @param array $fields All the ACF fields.
     * @return bool
     */
    private function hasCustomImageSize(array $fields): bool
    {
        // Check if 'mod_image_size' exists and is set to "custom"
        $hasCustomSize = !empty($fields['mod_image_size']) && $fields['mod_image_size'] === "custom";

        // Check if both width and height for the custom image crop are provided
        $hasValidCropDimensions = !empty($fields['mod_image_crop_width']) && !empty($fields['mod_image_crop_height']);

        // Return true only if both conditions are met
        return $hasCustomSize && $hasValidCropDimensions;
    }

    /**
     * Get the registered image size.
     * 
     * @param string $size The size to get
     * @param array $fields All the acf fields
     * @return array|null
     */
    private function getRegisteredImageSize(?string $size, array $fields): ?array 
    {
        $sizes = $fields['mod_image_image']['sizes'] ?? []; 

        if(array_key_exists($size, $sizes)) {
            return [
                $sizes[$size . '-width'],
                $sizes[$size . '-height']
            ];
        }
        return null;
    }

    /**
     * Get the image id
     * 
     * @param array $fields All the acf fields
     * @return int|null
     */
    private function getImageId($fields): ?int {
        return $fields['mod_image_image']['ID'] ?? null;
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
