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
        $data = $this->getFields();
        $data['args'] = $this->args;
        
        
        //Do not use link
        if ($data['mod_image_link'] == "false") {
            $data['mod_image_link_url'] = "";
        }
        
        //Set image class
        $imgClasses = array();
        if ($data['mod_image_responsive'] === true) {
            $imgClasses[] = 'image-responsive';
        }
        $data['img_classes'] = implode(' ', $imgClasses);
        
        //Crop image (if non existing)
        $data['image'] = $this->getImageData($data);
        
        echo '<pre>' . print_r( $data['image'], true ) . '</pre>';
        echo '<pre>' . print_r( $data['mod_image_image'], true ) . '</pre>';
        //Add box classes
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-filled'), $this->post_type, $this->args));

        $data['template'] = $this->template();
        return $data;
    }

    /**
     * Create a cropped image if needed
     * @return string
     */
    public function getImageData(array $data)
    {
        // if (!$data['mod_image_crop'] && $imageId) {
        //     return $data['mod_image_image']['sizes'][$data['mod_image_size']];
        // }
        $imageId = $data['mod_image_image']['id'];

        return ImageHelper::getImageAttachmentData($imageId, 'medium_large');

        // return $imageSrc[0];
    }

    /**
     * Choose appropriate style
     * @return string
     */

    public function template()
    {
        if ($this->args['id'] === 'right-sidebar') {
            return 'box.blade.php';
        }

        return 'default.blade.php';
    }

    /**
     * Creates a list of predefined sizes to choose from
     * @return array
     */

    public function appendImageSizes($field)
    {
        $sizes = get_intermediate_image_sizes();
        foreach ($sizes as $size) {
            $field['choices'][$size] = $size;
        }

        return $field;
    }
}
