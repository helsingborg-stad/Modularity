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

    /**
     * Registers the routes for the Modules API.
     */
    public function register_routes()
    {
        add_action('rest_api_init', function () {
            register_rest_route($this->namespace, '/' . $this->restBase . '/(?P<id>[\d]+)', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_item'),
                    'permission_callback' => array($this, 'get_item_permissions_check'),
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

    /**
     * Checks the permissions for retrieving a single item.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return bool|WP_Error Returns true if the security nonce is valid, otherwise returns a WP_Error object.
     */
    public function get_item_permissions_check($request)
    {
        $parsedHomeUrl = $this->getDomain(home_url() ?? null);
        $parsedReferer = $this->getDomain($_SERVER['HTTP_REFERER'] ?? null);

        if (!$this->isSameDomain($parsedHomeUrl, $parsedReferer)) {
            return new WP_Error('invalid_origin', __('Invalid request origin.'), ['status' => 400]);
        }

        return true;
    }

    /**
     * Checks if two domains are the same.
     *
     * @param string $domain1 The first domain to compare.
     * @param string $domain2 The second domain to compare.
     * @return bool Returns true if the domains are the same, false otherwise.
     */
    private function isSameDomain(string $domain1, string $domain2): bool {
        return $domain1 === $domain2;
    }

    /**
     * Retrieves the domain from a URL.
     *
     * @param string $url The URL to parse.
     * @return string|null The domain of the URL, or null if the URL is invalid.
     */
    private function getDomain(string $url): string|null
    {
        return parse_url($url)['host'] ?? null;
    }

    /**
     * Retrieves a single module item.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return string The module markup.
     */
    public function get_item($request)
    {
        $moduleId   = $request->get_param('id');
        $post       = get_post($moduleId);

        if ($this->itemExists($post)) {
            return $this->getItemNotFoundError();
        }

        $class      = get_class(\Modularity\ModuleManager::$classes[$post->post_type]);
        $module     = new $class($post);
        $display    = new \Modularity\Display($module);

        return $display->getModuleMarkup($module, []);
    }

    /**
     * Checks if an item exists.
     *
     * @param object|null $post The post object to check.
     * @return bool Returns true if the item exists, false otherwise.
     */
    private function itemExists($post)
    {
        return $post === null || !str_starts_with($post->post_type, \Modularity\ModuleManager::MODULE_PREFIX) || !isset(\Modularity\ModuleManager::$classes[$post->post_type]);
    }

    /**
     * Returns an instance of WP_Error indicating that the module was not found.
     *
     * @return WP_Error An instance of WP_Error with the error code 'not_found', error message 'Module not found',
     *                  and additional data indicating the status code 404.
     */
    private function getItemNotFoundError()
    {
        return new WP_Error('not_found', 'Module not found', ['status' => 404]);
    }
}
