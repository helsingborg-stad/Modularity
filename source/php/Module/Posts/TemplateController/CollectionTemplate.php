<?php

namespace Modularity\Module\Posts\TemplateController;

use Municipio\PostObject\PostObjectInterface;
use Municipio\PostObject\PostObjectRenderer\Appearances\Appearance;
use Municipio\PostObject\PostObjectRenderer\PostObjectRendererFactory;
use Municipio\PostObject\PostObjectRenderer\PostObjectRendererInterface;

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
        $renderer = PostObjectRendererFactory::create(Appearance::CollectionItem, $this->getRendererConfig($fields));
        $data['renderedPosts'] = join(array_map(fn ($postObject) => $this->renderPostObject($postObject, $renderer), $data['posts']));

        return $data;
    }

    private function getRendererConfig(array $fields):array {
        return [
            'displayFeaturedImage' => in_array('image', $fields['posts_fields'] ?? []),
            'gridColumnClass' => $fields['posts_columns'] ?? [],
        ];
    }

    private function renderPostObject(PostObjectInterface $postObject, PostObjectRendererInterface $renderer):string {
        return $postObject->getRendered($renderer);
    }
}
