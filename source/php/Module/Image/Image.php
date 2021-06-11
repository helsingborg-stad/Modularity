<?php

namespace Modularity\Module\Image;

class Image extends \Modularity\Module
{
    public $slug = 'image';
    public $supports = array();
    public $icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxOTkgMTk5Ij48cGF0aCBkPSJNMCAxNzkuNjQ4VjE5OWgxOS4zNTJjMy44MDUtNy4yNDQgMTEuMzk2LTEyLjE4OCAyMC4xNDgtMTIuMTg4IDguNjM1IDAgMTYuMTQ2IDQuODEgMjAgMTEuODk4IDMuODU0LTcuMDg4IDExLjM2NS0xMS44OTggMjAtMTEuODk4czE2LjE0NiA0LjgxIDIwIDExLjg5OGMzLjg1NC03LjA4OCAxMS4zNjUtMTEuODk4IDIwLTExLjg5OHMxNi4xNDYgNC44MSAyMCAxMS44OThjMy44NTQtNy4wODggMTEuMzY1LTExLjg5OCAyMC0xMS44OTggOC43NSAwIDE2LjM0MiA0Ljk0MyAyMC4xNDggMTIuMTg4SDE5OXYtMTkuMzUyYy03LjI0NC0zLjgwNS0xMi4xODgtMTEuMzk2LTEyLjE4OC0yMC4xNDggMC04LjYzNSA0LjgxLTE2LjE0NiAxMS44OTgtMjAtNy4wODgtMy44NTQtMTEuODk4LTExLjM2NS0xMS44OTgtMjBzNC44MS0xNi4xNDYgMTEuODk4LTIwYy03LjA4OC0zLjg1NC0xMS44OTgtMTEuMzY1LTExLjg5OC0yMHM0LjgxLTE2LjE0NiAxMS44OTgtMjBjLTcuMDg4LTMuODU0LTExLjg5OC0xMS4zNjUtMTEuODk4LTIwIDAtOC43NSA0Ljk0My0xNi4zNDIgMTIuMTg4LTIwLjE0OFYwaC0xOS4zNTJDMTc1Ljg0IDcuMjQ0IDE2OC4yNSAxMi4xODggMTU5LjUgMTIuMTg4Yy04LjYzNSAwLTE2LjE0Ni00LjgxLTIwLTExLjg5OC0zLjg1NCA3LjA4OC0xMS4zNjUgMTEuODk4LTIwIDExLjg5OFMxMDMuMzU0IDcuMzc4IDk5LjUuMjljLTMuODU0IDcuMDg4LTExLjM2NSAxMS44OTgtMjAgMTEuODk4UzYzLjM1NCA3LjM3OCA1OS41LjI5Yy0zLjg1MyA3LjA4OC0xMS4zNjUgMTEuODk4LTIwIDExLjg5OC04Ljc1MiAwLTE2LjM0NC00Ljk0My0yMC4xNDgtMTIuMTg4SDB2MTkuMzUyQzcuMjQ0IDIzLjE1OCAxMi4xODggMzAuNzUgMTIuMTg4IDM5LjVjMCA4LjYzNS00LjgxIDE2LjE0Ni0xMS44OTggMjAgNy4wODggMy44NTQgMTEuODk4IDExLjM2NSAxMS44OTggMjBTNy4zNzggOTUuNjQ2LjI5IDk5LjVjNy4wODggMy44NTQgMTEuODk4IDExLjM2NSAxMS44OTggMjBzLTQuODEgMTYuMTQ2LTExLjg5OCAyMGM3LjA4OCAzLjg1NCAxMS44OTggMTEuMzY1IDExLjg5OCAyMCAwIDguNzUyLTQuOTQ0IDE2LjM0NC0xMi4xODggMjAuMTQ4ek0zMCAzMGgxMzl2MTM0Ljg1MmwtMzUuNjMtNTQuNjc3LTIyLjczIDM0Ljg4Ny0zNi4zNzItNTUuODE4TDMwIDE1Ny4xOFYzMHptNzIuNSAzMS43NWMwLTkuMjUgNy40OTgtMTYuNzUgMTYuNzUtMTYuNzVDMTI4LjUgNDUgMTM2IDUyLjUgMTM2IDYxLjc1YzAgOS4yNTItNy41IDE2Ljc1LTE2Ljc1IDE2Ljc1LTkuMjUyIDAtMTYuNzUtNy40OTgtMTYuNzUtMTYuNzV6Ii8+PC9zdmc+';

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
        $data = get_fields($this->ID);
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
        $data['img_src'] = $this->maybeCropImage($data);

        //Add box classes
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-filled'), $this->post_type, $this->args));

        $data['template'] = $this->template();

        return $data;
    }

    /**
     * Create a cropped image if needed
     * @return string
     */
    public function maybeCropImage($data)
    {
        if (!$data['mod_image_crop']) {
            return $data['mod_image_image']['sizes'][$data['mod_image_size']];
        }

        $imageSrc = wp_get_attachment_image_src(
            $data['mod_image_image']['ID'],
            apply_filters('Modularity/image/image',
                municipio_to_aspect_ratio('16:9', array($data['mod_image_crop_width'], $data['mod_image_crop_height']))
            )
        );

        return $imageSrc[0];
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
