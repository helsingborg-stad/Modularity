<?php

namespace Modularity\Module\ManualInput\Private;

use Modularity\Module\ManualInput\ManualInput;

class PrivateController
{
    public static $index = 0;
    public function __construct(private ManualInput $manualInputInstance)
    {
        $this->registerMeta();
        add_filter('acf/update_value/key=field_6718c31e2862b', array($this, 'assignUniqueIdToRows'), 20, 4);
    }

    public function decorateData(array $data, array $fields): array
    {
        if (
            $this->manualInputInstance->postStatus !== 'private' ||
            empty($fields['allow_user_modification'])
        ) {
            return $data;
        }

        $user = wp_get_current_user();

        if (empty($user->ID)) {
            return $data;
        }

        $data['template'] = $this->manualInputInstance->template;
        $this->manualInputInstance->template = 'private';

        $data['user'] = $user->ID;

        $data['lang'] = [
            'save'        => __('Save', 'modularity'),
            'cancel'      => __('Cancel', 'modularity'),
            'description' => __('Description', 'modularity'),
            'name'        => __('Name', 'modularity'),
            'saving'      => __('Saving', 'modularity'),
            'obligatory'  => __('This item is obligatory', 'modularity'),
            'error'       => __('An error occurred and the data could not be saved. Please try again later', 'modularity'),
            'changeContent' => __('Change the lists content', 'modularity'),
        ];

        $data['filteredManualInputs'] = $this->getUserStructuredManualInputs($data, $user->ID);

        return $data;
    }

    private function getUserStructuredManualInputs(array $data): array
    {
        $userManualInputs = get_user_meta($data['user'], 'manualInputs', true);

        if (empty($userManualInputs[$this->manualInputInstance->ID]) || empty($data['manualInputs'])) {
            return $data['manualInputs'] ?? [];
        }

        $userManualInput = $userManualInputs[$this->manualInputInstance->ID];

        $filteredManualInputs = [];
        foreach ($data['manualInputs'] as $manualInput) {
            $manualInput['classList'] ??= [];
            $manualInput['attributeList'] ??= [];

            if (
                empty($manualInput['obligatory']) && 
                isset($userManualInput[$manualInput['uniqueId']]) && 
                !$userManualInput[$manualInput['uniqueId']]
            ) {
                $manualInput['classList'][] = 'u-display--none';
                $manualInput['checked'] = false;
            } else {
                $manualInput['checked'] = true;
            }

            $manualInput['attributeList']['data-js-item-id'] = $manualInput['uniqueId']; 

            $filteredManualInputs[] = $manualInput;
        }

        return $filteredManualInputs;
    }

    private function registerMeta(): void
    {
        register_meta('user', 'manualInputs', array(
            'type' => 'object',
            'show_in_rest' => array(
                'schema' => array(
                    'type' => 'object',
                    'additionalProperties' => array(
                        'type' => 'object',
                        'properties' => array(
                            'key' => array(
                                'type' => 'bool',
                            ),
                        ),
                        'additionalProperties' => true,
                    ),
                ),
            ),
            'single' => true,
        ));
    }

    public function assignUniqueIdToRows($value, $postId, $field, $original): string
    {
        if (empty($value)) {
            $value = self::$index . '-' . uniqid();
            self::$index++;
        }

        return $value;
    }
}