<?php

namespace Modularity\Module\Posts\TemplateController;

use Municipio\PostObject\PostObjectInterface;
use Municipio\PostObject\PostObjectRenderer\Appearances\Appearance;
use Municipio\PostObject\PostObjectRenderer\PostObjectRendererFactory;

/**
 * Class CollectionTemplate
 *
 * Template controller for rendering a collection of posts.
 *
 * @package Modularity\Module\Posts\TemplateController
 */
class CollectionTemplate extends AbstractController
{
    protected $module;

    /**
     * CollectionTemplate constructor.
     *
     * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
     */
    public function __construct(\Modularity\Module\Posts\Posts $module)
    {
        parent::__construct($module);
    }

    public function addDataViewData(array $data, array $fields)
    {
        $data = parent::addDataViewData($data, $fields);
        
        $renderer = PostObjectRendererFactory::create(Appearance::CollectionItem, [
            'displayFeaturedImage' => in_array('image', $fields['posts_fields'] ?? []),
            'gridColumnClass' => $fields['posts_columns'] ?? [],
        ]);

        $data['renderedPosts'] = join(array_map(function (PostObjectInterface $postObject) use ($renderer) {
            return $postObject->getRendered($renderer);
        }, $data['posts']));

        return $data;
    }
}
