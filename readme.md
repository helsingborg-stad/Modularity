Modularity
==========
Modular component system plugin for WordPress. Drag and drop the bundled modules or your custom modules to your page layout.

Action reference
----------------

##### Modularity/Module/[MODULE SLUG]/enqueue
> Enqueue js or css only for the add and edit page of the specified module.

```php
add_action('Modularity/Module/mod-article/enqueue', function () {
    // Do your thing
});
```
