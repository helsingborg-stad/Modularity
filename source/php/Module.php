<?php

namespace Modularity;

abstract class Module
{
    /**
     * WP_Post properties will automatically be extracted to properties of this class.
     * This array contains the keys to the extracted properties.
     * @var array
     */
    public $extractedPostProperties = array();

    /**
     * The slug of the module
     * Example: image
     * @var string
     */
    public $slug = '';

    /**
     * Singular name of the modue
     * Example: Image
     * @var string
     */
    public $nameSingular = '';

    /**
     * Plural name of the module
     * Example: Images
     * @var string
     */
    public $namePlural = '';

    /**
     * Module description
     * Shows a fixed with and height image
     * @var string
     */
    public $description = '';

    /**
     * Module icon (Base64 endoced data uri)
     * @var string
     */
    public $icon = '';

    /**
     * What the module post type should support (title and revision will be added automatically)
     * Example: array('editor', 'attributes')
     * @var array
     */
    public $supports = array();

    /**
     * Any module plugins (path to file to include)
     * @var array
     */
    public $plugin = array();

    /**
     * Cache ttl
     * @var integer
     */
    public $cacheTtl = 0;

    /**
     * The initial setting for "hide title" of the module
     * @var boolean
     */
    public $hideTitle  = false;

    /**
     * Is the module deprecated?
     * @var boolean
     */
    public $isDeprecated = false;

    /**
     * Path to template folder for this module
     * @var string
     */
    public $templateDir = false;

    /**
     * View data (data that will be sent to the blade view)
     * @var array
     */
    public $data = array();

    /**
     * Constructs a module
     * @param int $postId
     */
    public function __construct(\WP_Post $post = null)
    {
        if (is_numeric($post)) {
            $post = get_post($post);
        }

        if (is_a($post, '\WP_Post')) {
            $this->extractPostProperties($post);
        }
    }

    /**
     * Extracts WP_Post properties into Module properties
     * @param  \WP_Post $post
     * @return void
     */
    private function extractPostProperties(\WP_Post $post)
    {

        foreach ($post as $key => $value) {
            $this->extractedPostProperties[] = $key;
            $this->$key = $value;
        }
    }

    /**
     * Get module view
     * @return string
     */
    public function template()
    {
        return $this->slug . '.blade.php';
    }

    /**
     * Get module view data
     * @return array
     */
    public function getViewData()
    {
        $data = $this->data;

        foreach ($this->extractedPostProperties as $property) {
            $data[$property] = $this->$property;
        }

        return $data;
    }
}
