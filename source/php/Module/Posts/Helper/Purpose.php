<?php

namespace Modularity\Module\Posts\Helper;

class Purpose
{
    public static function getPurpose(string $type = '')
    {
        if (empty($type)) {
            return false;
        }
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
