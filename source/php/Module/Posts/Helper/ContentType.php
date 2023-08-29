<?php

namespace Modularity\Module\Posts\Helper;

class ContentType
{
    public static function getContentType(string $type = '')
    {
        if (empty($type)) {
            return false;
        }
        $contentType = false;
        if (class_exists('\Municipio\Helper\ContentType')) {
            $contentType = \Municipio\Helper\ContentType::getContentType($type);
            if (!empty($contentType)) {
                return $contentType[0]->key;
            }
        }
        return $contentType;
    }
}
