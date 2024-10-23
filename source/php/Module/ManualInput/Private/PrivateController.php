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
            'saving'      => __('Saving', 'modularity')
        ];

        return $data;
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

    public function assignUniqueIdToRows($value, $post_id, $field, $original): string
    {
        if (empty($value)) {
            $value = self::$index . '-' . uniqid();
            self::$index++;
        }

        return $value;
    }
}