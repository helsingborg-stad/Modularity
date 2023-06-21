<?php

namespace Modularity\Module\Posts\Helper;

class Purpose
{
    public static function getPurpose(string $type = '')
    {
        if (class_exists('\Municipio\Helper\Purpose')) {
            $purpose = \Municipio\Helper\Purpose::getPurpose($type);
            if ($purpose = \Municipio\Helper\Purpose::getPurpose($type)) {
                return $purpose->getKey();
            }
        }
        return false;
    }
}
