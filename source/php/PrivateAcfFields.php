<?php

namespace Modularity;

class PrivateAcfFields
{
    public function __construct()
    {
        $fields = [
            'field_67126c170c176'
        ];

        foreach ($fields as $field) {
            add_filter('acf/prepare_field/key=' . $field, array($this, 'conditionallyShowBasedOnStatus'));
        }
    }

    public function conditionallyShowBasedOnStatus( $field ) {
        if (!isset($field['conditional_logic']) || !is_array($field['conditional_logic'])) {
            $field['conditional_logic'] = [];
        }

        $field['conditional_logic'][] = 
            [
                [
                    'field' => 'field_67124199dcb25',
                    'operator' => '==',
                    'value' => 'private'
                ]
            ];

        return $field;
    }
}