<?php

namespace Modularity\Module\Latest;

class Latest extends \Modularity\Module
{
    public function __construct()
    {
        //Register
        $this->register(
            'latest',
            'Latest',
            'Latest',
            'Outputs posts in a given order',
            array(''),
            null,
            'acf-post-type-field/acf-posttype-select.php' //included plugin
        );

        //Filter select
        add_filter('acf/load_field/name=filter_posts_by_category', array($this,'getTaxonomiesOptions'));
        //add_filter('acf/load_field/name=color', array($this,'getTaxonomiesOptions'));
    }

    public function getTaxonomiesOptions($field)
    {

        $field['choices'] = array();

        $choices = get_taxonomies();

        if (is_array($choices)) {
            foreach ($choices as $choice) {

                $terms = get_terms($choice);


                $field['choices'][ $choice ] = $choice;
            }
        }

        return $field;

    }
}
