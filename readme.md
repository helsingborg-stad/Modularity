Modularity
==========

Modular component system plugin for WordPress. Drag and drop the bundled modules or your custom modules to your page layout.

Creating modules
----------------

To create your very own Modularity module you simply create a plugin with a class that extends our Modularity\Module class.

A module actually is the same as a custom post type. However we've added a few details to enable you to use them as modules.

Use the `$this->register()` method to create a very basic module.

Here's a very basic example module for you:

```php
/*
 * Plugin Name: Modularity Article Module
 * Plugin URI: -
 * Description: Article module for Modularity
 * Version: 1.0
 * Author: Modularity
 */

class VeryBasicModule extends \Modularity\Module
{
    public function __construct()
    {
        $id = 'article';
        $nameSingular = 'Article';
        $namePlural = 'Articles';
        $description = 'Outputs a full article with title and content';
        $supports = array('title', 'editor');
        $icon = '[BASE-64 encoded svg data-uri]';

        $this->register(
            $id,
            $nameSingular,
            $namePlural,
            $description,
            $supports,
            $icon
        );
    }
}
```

Action reference
----------------

##### Modularity/Module/[MODULE SLUG]/enqueue
> Enqueue js or css only for the add and edit page of the specified module.

```php
add_action('Modularity/Module/mod-article/enqueue', function () {
    // Do your thing
});
```
