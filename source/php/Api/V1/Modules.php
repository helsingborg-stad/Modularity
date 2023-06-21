<?php

namespace Modularity\Api\V1;

use WP_Error;
use WP_REST_Controller;

class Modules extends WP_REST_Controller
{
    protected $namespace = \Modularity\Api\RestApiNamespace::V1;
    protected $restBase = "modules";

    public function __construct()
    {
        return $this;
    }

    public function register_routes()
    {
        add_action('rest_api_init', function () {
            register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_item'),
                    'premission_callback' => array($this, 'get_item_permissions_check'),
                    'args' => [
                        'id' => [
                            'description' => __('Unique identifier for the module.'),
                            'type' => 'integer',
                        ],
                        '_wpnonce' => [
                            'description' => __('Security nonce.'),
                            'type' => 'string',
                        ],
                    ],
                )
            ));
        });
    }

    public function get_item($request)
    {
        $moduleId = $request->get_param('id');
        $nonce = $request->get_param('_wpnonce');
        $post = get_post($moduleId);

        if (!wp_verify_nonce($request->get_param('_wpnonce'), 'wp_rest')) {
            return new WP_Error('rest_forbidden', __('Invalid security nonce.'), ['status' => 403]);
        }

        if ($this->itemExists($post)) {
            return $this->getItemNotFoundError();
        }

        $class = get_class(\Modularity\ModuleManager::$classes[$post->post_type]);
        $module = new $class($post);
        $display = new \Modularity\Display($module);



        return $display->getModuleMarkup($module, []);
    }

    private function itemExists($post)
    {
        return $post === null || !str_starts_with($post->post_type, \Modularity\ModuleManager::MODULE_PREFIX) || !isset(\Modularity\ModuleManager::$classes[$post->post_type]);
    }

    private function getItemNotFoundError()
    {
        return new WP_Error('not_found', 'Module not found', ['status' => 404]);
    }
}
