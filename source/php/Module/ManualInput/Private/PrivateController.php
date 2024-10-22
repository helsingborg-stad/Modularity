<?php

namespace Modularity\Module\ManualInput\Private;

use Modularity\Module\ManualInput\ManualInput;

class PrivateController
{
    public function __construct(private ManualInput $manualInputInstance)
    {}

    public function decorateData(array $data, array $fields)
    {
        if (
            $this->manualInputInstance->postStatus !== 'private' ||
            empty($fields['allow_user_modification'])
        ) {
            return $data;
        }

        $data['template'] = $this->manualInputInstance->template;

        $this->manualInputInstance->template = 'private';

        return $data;
    }
}