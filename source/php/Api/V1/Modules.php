<?php

namespace Modularity\Api\V1;

use WP_Error;
use WP_REST_Controller;

class Modules extends WP_REST_Controller
{
    protected $namespace = \Modularity\Api\RestApiNamespace::V1;
    protected $rest_base = "modules";

    public function __construct()
    {
        return $this;
    }

    public function register_routes()
    {
        add_action('rest_api_init', function () {
            register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_item'),
                    'premission_callback' => array($this, 'get_item_permissions_check')
                )
            ));
        });
    }

    public function get_item($request)
    {
        $moduleId = $request->get_param('id');
        $post = get_post($moduleId);

        if ($post === null) {
            return $this->getItemNotFoundError();
        }

        if (!str_starts_with($post->post_type, \Modularity\ModuleManager::MODULE_PREFIX)) {
            return $this->getItemNotFoundError();
        }

        if (!isset(\Modularity\ModuleManager::$classes[$post->post_type])) {
            return $this->getItemNotFoundError();
        }

        $class = get_class(\Modularity\ModuleManager::$classes[$post->post_type]);
        $module = new $class($post);
        $display = new \Modularity\Display($module);

        return $display->getModuleMarkup($module, []);
    }

    private function getItemNotFoundError()
    {
        return new WP_Error('not_found', 'Module not found', ['status' => 404]);
    }
}
