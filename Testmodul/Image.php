<?php

namespace ImageModule;

class Image extends \Modularity\Module
{
    public static $slug = 'image';
    public static $nameSingular = 'Image';
    public static $namePlural = 'Images';
    public static $description = 'A test module for the new way of initializing modules, will display a simple image.';
    public static $icon = '';
    public static $supports = array();
    public static $plugin = array();
    public static $cacheTtl = 0;
    public static $hideTitle  = false;
}
