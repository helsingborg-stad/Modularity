<?php
class acf_field_posttype_select extends acf_field
{
    /*
    *  __construct
    *
    *  This function will setup the field type data
    *
    *  @type    function
    *  @date    5/03/2014
    *  @since   5.0.0
    *
    *  @param   n/a
    *  @return  n/a
    */
    public function __construct()
    {
        $this->name = 'posttype_select';
        $this->label = __('Post Type Select', 'acf-posttype_select');
        $this->category = 'choice';
        $this->defaults = array(
            'multiple'      => 0,
            'allow_null'    => 0,
            'default_value' => '',
            'placeholder'   => '',
            'disabled'      => 0,
            'readonly'      => 0,
        );
        $this->l10n = array(
            'error' => __('Error! Please enter a higher value', 'acf-posttype_select'),
        );

        // do not delete!
        parent::__construct();
    }

    /*
    *  render_field_settings()
    *
    *  Create extra settings for your field. These are visible when editing a field
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $field (array) the $field being edited
    *  @return  n/a
    */
    public function render_field_settings($field)
    {

        /*
        *  acf_render_field_setting
        *
        *  This function will create a setting for your field.
        *  Simply pass the $field parameter and an array of field settings.
        *  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
        *
        *  More than one setting can be added by copy/paste the above code.
        *  Please note that you must also have a matching $defaults value for the field name (font_size)
        */

        // default_value
        acf_render_field_setting($field, array(
            'label' => __('Default Value', 'acf'),
            'instructions' => __('Enter each default value on a new line', 'acf'),
            'type' => 'textarea',
            'name' => 'default_value'
        ));

        // allow_null
        acf_render_field_setting($field, array(
            'label' => __('Allow Null?', 'acf'),
            'instructions' => '',
            'type' => 'radio',
            'name' => 'allow_null',
            'choices' => array(
                1 => __('Yes', 'acf'),
                0 => __('No', 'acf')
            ),
            'layout' => 'horizontal'
        ));

        // multiple
        acf_render_field_setting($field, array(
            'label'         => __('Select multiple values?','acf'),
            'instructions'  => '',
            'type'          => 'radio',
            'name'          => 'multiple',
            'choices'       => array(
                1               => __("Yes",'acf'),
                0               => __("No",'acf'),
            ),
            'layout'    =>  'horizontal',
        ));

    }

    /*
    *  render_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param   $field (array) the $field being rendered
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $field (array) the $field being edited
    *  @return  n/a
    */
    public function render_field($field)
    {
        // convert value to array
        $field['value'] = acf_get_array($field['value'], false);

        // add empty value (allows '' to be selected)
        if (empty($field['value'])) {
            $field['value'][''] = '';
        }

        // placeholder
        if (empty($field['placeholder'])) {
            $field['placeholder'] = __("Select",'acf');
        }

        // vars
        $atts = array(
            'id'                => $field['id'],
            'class'             => $field['class'],
            'name'              => $field['name'],
            'data-multiple'     => $field['multiple'],
            'data-placeholder'  => $field['placeholder'],
            'data-allow_null'   => $field['allow_null']
        );

        // multiple
        if ($field['multiple']) {
            $atts['multiple'] = 'multiple';
            $atts['size'] = 5;
            $atts['name'] .= '[]';
        }

        // special atts
        foreach (array( 'readonly', 'disabled' ) as $k) {
            if (!empty($field[ $k ])) {
                $atts[ $k ] = $k;
            }
        }

        // vars
        $els = array();
        // $choices = array();

        $posttypes = $this->post_type_options();
        foreach ($posttypes as $pt) {
            $els[] = array(
                'type' => 'option',
                'value' => $pt['value'],
                'label' => $pt['label'],
                'selected' => in_array($pt['value'], $field['value'])
            );
        }

        // null
        if ($field['allow_null']) {
            array_unshift($els, array(
                'type' => 'option',
                'value' => '',
                'label' => '- ' . $field['placeholder'] . ' -'
            ));
        }

        // html
        echo '<select ' . acf_esc_attr($atts) . '>';

        // construct html
        if (!empty($els)) {
            foreach ($els as $el) {
                // extract type
                $type = acf_extract_var($el, 'type');
                if ($type == 'option') {
                    // get label
                    $label = acf_extract_var($el, 'label');
                    // validate selected
                    if (acf_extract_var($el, 'selected')) {
                        $el['selected'] = 'selected';
                    }

                    // echo
                    echo '<option ' . acf_esc_attr($el) . '>' . $label . '</option>';
                } else {
                    // echo
                    echo '<' . $type . ' ' . acf_esc_attr($el) . '>';
                }


            }

        }

        echo '</select>';
    }

    /**
     * Outputs Post Types in HTML option tags
     *
     * @return array Array of post type names and labels
     */
    private function post_type_options()
    {
        $args = apply_filters('acf_field_posttype_select/get_post_types_args', array( 'public' => true ));
        $post_types = get_post_types($args, 'objects');
        $output = array();
        foreach ($post_types as $post_type) {
            $output[] = array(
                'value' => $post_type->name,
                'label' => $post_type->label
            );
        }
        return $output;
    }
}

// create field
new acf_field_posttype_select();
