<?php

namespace Modularity;

abstract class Module
{
    /**
     * The slug of the module
     * Example: image
     * @var string
     */
    public static $slug = '';

    /**
     * Singular name of the modue
     * Example: Image
     * @var string
     */
    public static $nameSingular = '';

    /**
     * Plural name of the module
     * Example: Images
     * @var string
     */
    public static $namePlural = '';

    /**
     * Module description
     * Shows a fixed with and height image
     * @var string
     */
    public static $description = '';

    /**
     * Module icon (Base64 endoced data uri)
     * @var string
     */
    public static $icon = '';

    /**
     * What the module post type should support (title and revision will be added automatically)
     * Example: array('editor', 'attributes')
     * @var array
     */
    public static $supports = array();

    /**
     * Any module plugins (path to file to include)
     * @var array
     */
    public static $plugin = array();

    /**
     * Cache ttl
     * @var integer
     */
    public static $cacheTtl = 0;

    /**
     * The initial setting for "hide title" of the module
     * @var boolean
     */
    public static $hideTitle  = false;

    /**
     * Is the module deprecated?
     * @var boolean
     */
    public static $isDeprecated = false;
}
