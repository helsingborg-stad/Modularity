<?php

namespace ImageModule;

class Image extends \Modularity\Module
{
    public $slug = 'image';
    public $nameSingular = 'Image';
    public $namePlural = 'Images';
    public $description = 'A test module for the new way of initializing modules, will display a simple image.';
    public $icon = '';
    public $supports = array();
    public $plugin = array();
    public $cacheTtl = 0;
    public $hideTitle  = false;
    public $isDeprecated = false;

    public $templateDir = MODULARITY_PATH . 'Testmodul/views';
}
