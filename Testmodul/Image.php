<?php

namespace ImageModule;

class Image extends \Modularity\Module
{
    public $slug = 'image-new';
    public $nameSingular = 'Image new';
    public $namePlural = 'Images new';
    public $description = 'A test module for the new way of initializing modules, will display a simple image.';
    public $icon = '';
    public $supports = array();
    public $plugin = array();
    public $cacheTtl = 0;
    public $hideTitle  = false;
    public $isDeprecated = false;

    public $templateDir = MODULARITY_PATH . 'Testmodul/views';
}
