<?php

namespace Modularity\Module\Posts\Helper;

class Purpose
{
    public static function getPurpose(string $type = '')
    {
        $purpose = false;
        if (class_exists('\Municipio\Helper\Purpose')) {
            $purpose = \Municipio\Helper\Purpose::getPurpose($type);
            if (!empty($purpose)) {
                return $purpose[0]->key;
            }
        }
        return $purpose;
    }
}
