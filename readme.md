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

namespace MyArticleModule;

class Article extends \Modularity\Module
{
    public function __construct()
    {
        $id = 'article';
        $nameSingular = 'Article';
        $namePlural = 'Articles';
        $description = 'Outputs a full article with title and content';
        $supports = array('editor'); // All modules automatically supports title
        $icon = '[BASE-64 encoded svg data-uri]';
        $plugin = '/path/to/include-file.php' // CAn also be an array of paths to include 
        $cacheTTL = 60*60*24 //Time to live for fragment cache (stored in memcached). 

        $this->register(
            $id,
            $nameSingular,
            $namePlural,
            $description,
            $supports,
            $icon,
            $plugin,
            $cacheTTL
        );
    }
}

new \MyArticleModule\Article;
```

#### Module templates

You can easily create your own module templates by placing them in: `/wp-content/themes/[my-theme]/templates/module/`.

Name your template file with the following pattern: `modularity-[module-id].php`. You can get your module's id from the Modularity options page.

#### Module boilerplate

You can download our module boilerplate. It will be a good starting point for any custom module that you would like to build.

[Download it here (NOT AVAILABLE YET)](http://www.helsingborg.se)


Action reference
----------------

#### Modularity

> Runs when Modularity core is loaded. Typically used to add custom modules.

*Example:*
```php
add_action('Modularity', function () {
    // Do your thing
});
```

#### Modularity/Module/[MODULE SLUG]/enqueue

> Enqueue js or css only for the add and edit page of the specified module.

*Example:*

```php
add_action('Modularity/Module/mod-article/enqueue', function () {
    // Do your thing
});
```

#### Modularity/Options/Module

> Action to use for adding option fields to modularity options page.
> Use "Modularity/Options/Save" action to handle save of the option field added

*Example:*

```php
add_action('Modularity/Options/Module', function () {
    echo '<input type="text">';
});
```

Filter reference
----------------
#### Modularity/Module/TemplateVersion3

> Enable preview of the upcoming version 3 views with BEM formatting. 
> This may be used already when progressing towards BEM. 

*Example:*

```php
add_filter('Modularity/Module/TemplateVersion3', function(){return true;});
```

#### Modularity/Editor/WidthOptions

> Filter module width options

*Params:*
```
$options      The default width options array ('value' => 'label')
```

*Example:*

```php
add_filter('Modularity/Editor/WidthOptions', function ($options) {
    // Do your thing
    return $filteredValue;
});
```

---


#### Modularity/Editor/SidebarIncompability

> Enables the theme to add incompability indicators of specific module to an sidebar area. The user will not be able to drag and drop to unsupported areas. This filter may simplify the theme developers work by ruling out some cases. 

*Params:*
```
$moduleSpecification      The default options for the module post object. 
```

*Example:*

```php
add_filter('Modularity/Editor/WidthOptions', function ($moduleSpecification) {
    
    $moduleSpecification['sidebar_compability'] = array("content-area-top"); 

    return $moduleSpecification;
});
```

---

#### Modularity/Display/BeforeModule

> Filter module sidebar wrapper (before)

*Params:*
```
$beforeModule     The value to filter
$args             Arguments of the sidebar (ex: before_widget)
$moduleType       The module's type
$moduleId         The ID of the module
```

*Example:*
```php
add_filter('Modularity/Display/BeforeModule', function ($beforeModule, $args, $moduleType, $moduleId) {
    // Do your thing
    return $filteredValue;
});
```

---

#### Modularity/Display/AfterModule

> Filter module sidebar wrapper (after)

*Params:*
```
$afterModule      The value to filter
$args             Arguments of the sidebar (ex: before_widget)
$moduleType       The module's type
$moduleId         The ID of the module
```

*Example:*

```php
add_filter('Modularity/Display/AfterModule', function ($afterModule, $args, $moduleType, $moduleId) {
    // Do your thing
    return $filteredValue;
});
```

---

#### Modularity/Module/Container/Sidebars

> Container wrapper: Filter what sidebars that should support a containing wrapper on some modules

*Params:*
```
$sidebars      A array of sidebar id's
```

---

#### Modularity/Module/Container/Modules

> Container wrapper: Filter what modules that should support a containing wrapper

*Params:*
```
$modules      A array of module ids (post-type names)
```

---

#### Modularity/Module/Container/Template

> Container wrapper: Filter the template with html that should be wrapped around each module

*Params:*
```
$markup      A string with markup containing {{module-markup}} replacement key
```

---

#### Modularity/Module/TemplatePath & Modularity/Theme/TemplatePath

> Modify (add/edit) paths where to look for module/theme templates
> Typically used for adding search path's for finding custom modules/theme templates.
> 
> *Attention: Unsetting paths may cause issues displaying modules. Plase do not do this unless you know exacly what you are doing.*

*Params:*
```
$paths      The value to filter
```

*Example:*
```php
add_filter('Modularity/Module/TemplatePath', function ($paths) {
    return $paths;
});

add_filter('Modularity/Theme/TemplatePath', function ($paths) {
    return $paths;
});
```

#### Modularity/Module/Classes

> Modify the list of classes added to a module's main element

*Params:*
```
$classes      The classes (array)
$moduleType   The module type
$sidebarArgs  The sidebar's args
```

*Example:*
```php
add_filter('Modularity/Module/Classes', function ($classes, $moduleType, $sidebarArgs) {
    $classes[] = 'example-class';
    return $classes;
});
```

#### Modularity/Display/Markup

> Module display markup

*Params:*
```
$markup      The markup
$module      The module post
```

*Example:*
```php
add_filter('Modularity/Display/Markup', function ($markup, $module) {
    return $markup;
});
```

#### Modularity/Display/[MODULE SLUG]/Markup

*Params:*
```
$markup      The markup
$module      The module post
```

*Example:*
```php
add_filter('Modularity/Display/Markup', function ($markup, $module) {
    return $markup;
});
```

#### Modularity/CoreTemplatesSearchTemplates

> What template files to look for

*Params:*
```
$templates
```

*Example:*
```php
add_filter('Modularity/CoreTemplatesSearchTemplates', function ($templates) {
    $templates[] = 'my-custom-template';
    return $templates;
});
```

#### Modularity/Module/Posts/Date

> Modify the displayed publish date in Post Modules

*Params:*
```
$date
$postId
$postType
```

*Example:*
```php
add_filter('Modularity/Module/Posts/Date', function ($date, $postId, $postType) {
    return $date;
});
```

#### Modularity/Editor/ModuleCssScope

> Allow editors to select a unique appeance (provided by a theme etc) for a module. Adds a single class to the module wrapper, to allow scoping of css styles. 

*Params:*
```
$scopes - Previously declared scopes. 
```

*Example:*
```php
add_filter('Modularity/Editor/ModuleCssScope',function($scopes) {
        return array(
            'mod-posts' => array(
                's-buy-card' => __("Make this module sparkle!", 'modularity'),
                's-user-list' => __("A boring user list is what i see", 'modularity')
            )
        );
    });
```

# Tested with support from BrowserStack
This software is tested with the awesome tools from Browserstack.

{::nomarkdown}
<a href="https://browserstack.com"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAAArCAYAAAFKj/ckAAAAAXNSR0IArs4c6QAAAAlwSFlzAAALEwAACxMBAJqcGAAABCVpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDUuNC4wIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iCiAgICAgICAgICAgIHhtbG5zOmV4aWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vZXhpZi8xLjAvIgogICAgICAgICAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICAgICAgICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyI+CiAgICAgICAgIDx0aWZmOlJlc29sdXRpb25Vbml0PjI8L3RpZmY6UmVzb2x1dGlvblVuaXQ+CiAgICAgICAgIDx0aWZmOkNvbXByZXNzaW9uPjA8L3RpZmY6Q29tcHJlc3Npb24+CiAgICAgICAgIDx0aWZmOlhSZXNvbHV0aW9uPjcyPC90aWZmOlhSZXNvbHV0aW9uPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpZUmVzb2x1dGlvbj43MjwvdGlmZjpZUmVzb2x1dGlvbj4KICAgICAgICAgPGV4aWY6UGl4ZWxYRGltZW5zaW9uPjIwMDwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDxleGlmOkNvbG9yU3BhY2U+MTwvZXhpZjpDb2xvclNwYWNlPgogICAgICAgICA8ZXhpZjpQaXhlbFlEaW1lbnNpb24+NDM8L2V4aWY6UGl4ZWxZRGltZW5zaW9uPgogICAgICAg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" alt="Browserstack logotype" style="width: 200px; max-width: 200px;"/></a>
{:/}
