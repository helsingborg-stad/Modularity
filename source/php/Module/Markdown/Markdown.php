<?php

namespace Modularity\Module\Markdown;

use Parsedown;
use WP_Error;
use Modularity\Module\Markdown\Providers\ProviderInterface;

class Markdown extends \Modularity\Module {
    public $slug = 'markdown';
    public $supports = array();
    public $isBlockCompatible = true;
    public $cacheTtl = (MINUTE_IN_SECONDS * 60 * 12); //Seconds (12 hours)
    private $providers = [];
    private $lastUpdatedKey = '_last_updated';
    private $nextUpdateKey = '_next';

    public function init()
    {
        //Setup module
        $this->nameSingular = __("Markdown", 'modularity');
        $this->namePlural = __("Markdown", 'modularity');
        $this->description = __("Outputs a markdown resource as module.", 'modularity');

        //Setup providers
        $this->providers = [
            new Providers\Github(),
            new Providers\Bitbucket(),
            new Providers\Gitlab(),
            new Providers\AzureDevOps(),
            new Providers\CGit(),
            new Providers\Gitea(),
            new Providers\Codeberg(),
            new Providers\Sourcehut(),
        ];

        //Filter example fields
        add_filter('acf/prepare_field/key=field_67506eebcdbfd', array($this, 'createDocumentationField'));

        //Delete transients when saving
        add_action('acf/save_post', array($this, 'deleteTransients'), 20);
    }

    /**
     * Delete transients when saving.
     * 
     * @param int $postId The post ID.
     */
    public function deleteTransients($postId)
    {
        $fields = $this->getFields();
        $markdownUrl = $fields['mod_markdown_url'] ?: false;

        if ($markdownUrl) {
            $transientKey = $this->createTransientKey($markdownUrl);
            delete_transient($transientKey);
            delete_transient($transientKey . $this->lastUpdatedKey);
            delete_transient($transientKey . $this->nextUpdateKey);
        }
    }

    /**
     * Create custom documentation field.
     * 
     * @param array $field The field array.
     * 
     * @return array The field array.
     */
    public function createDocumentationField($field) {
        // Initialize table content
        $tableContent = '<table class="widefat striped">';
        $tableContent .= '<thead>';
        $tableContent .= '<tr>';
        $tableContent .= '<th>Provider Name</th>';
        $tableContent .= '<th>Example URL</th>';
        $tableContent .= '</tr>';
        $tableContent .= '</thead>';
        $tableContent .= '<tbody>';
    
        // Loop through providers
        foreach ($this->providers as $provider) {
            if ($provider instanceof \Modularity\Module\Markdown\Providers\ProviderInterface) {
                $name = esc_html($provider->getName());
                $example = esc_html($provider->getExample());
    
                $tableContent .= '<tr>';
                $tableContent .= "<td>{$name}</td>";
                $tableContent .= "<td>{$example}</td>";
                $tableContent .= '</tr>';
            }
        }
    
        $tableContent .= '</tbody>';
        $tableContent .= '</table>';
    
        $field['message'] = $tableContent;
    
        return $field;
    }

    /**
     * Get the module data.
     * 
     * @return array The module data.
     */
    public function data(): array
    {
        //Get fields
        $fields = $this->getFields();

        //Handle data
        $markdownUrl    = $fields['mod_markdown_url'] ?: false;
        $isMarkdownUrl = $this->checkIfIsValidMarkdownProvider($markdownUrl, ...$this->providers);
        $markdownContent = $isMarkdownUrl ? $this->getDocument($markdownUrl) : false;

        if(!is_wp_error($markdownContent)) {
            $parsedMarkdown = $isMarkdownUrl ? $this->parseMarkdown(
                $this->filterMarkDownContent($markdownContent)
            ) : false;
            $wpError = false;
        } else {
            $parsedMarkdown = false;
            $wpError = $markdownContent;
        }
       
        $showMarkdownSource = $fields['mod_markdown_show_source'] ?: false;

        //Setup translations
        $language = [
            'sourceUrl' =>  __('Source Url', 'modularity'),
            'nextUpdate' => __('Next update', 'modularity'),
            'lastUpdated' => __('Last updated', 'modularity'),
            'fetchError' => __('We could not fetch any content at this moment. Please try again later.', 'modularity'),
            'parseError' => __('The url provided could not be parsed by any of the allowed providers.', 'modularity'),
        ];

        //Return data
        return [
            'isMarkdownUrl' => $isMarkdownUrl,
            'wpError' => $wpError,
            'markdownContent' => $markdownContent,
            'parsedMarkdown' => $parsedMarkdown,
            'showMarkdownSource' => $showMarkdownSource,
            'markdownUrl' => $markdownUrl,
            'markdownLastUpdated' => get_transient($this->createTransientKey($markdownUrl) . $this->lastUpdatedKey),
            'markdownNextUpdate' => get_transient($this->createTransientKey($markdownUrl) . $this->nextUpdateKey),
            'language' => (object) $language,
        ];
    }

    /**
     * Filter markdown content.
     * 
     * @param string $markdownContent The markdown content.
     * 
     * @return string The filtered markdown content.
     */
    private function filterMarkDownContent($markdownContent)
    {
        $filters = [
            new Filters\DemoteTitles(),
        ]; 

        foreach ($filters as $filter) {
            $markdownContent = $filter->filter($markdownContent);
        }

        return $markdownContent;
    }

    /**
     * Check if the url is a valid markdown url.
     */
    private function checkIfIsValidMarkdownProvider($url, ProviderInterface ...$providers): bool
    {
        foreach ($providers as $provider) {
            if ($provider->isValidProviderUrl($url)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Parse markdown content.
     */
    private function parseMarkdown($markdown): string
    {
        try {
            $parsedown = new \Parsedown();
            return $parsedown->text($markdown);
        } catch (\Exception $e) {
            return "";
        }
    }

    /**
     * Get document from remote URL.
     * 
     * @param string $requestUrl The URL to request.
     * 
     * @return mixed The remote document.
     */
    public function getDocument($requestUrl): string | \WP_Error
    {
        $requestArgs = [
            'headers' => [
                'Content-Type: application/json',
            ]
        ];

        return $this->maybeRetriveCachedResponse($requestUrl, $requestArgs, true);
    }

    /**
     * Retrieve cached response if available or get remote response and set cached response.
     *
     * @param string $requestUrl The URL to request.
     * @param array $requestArgs Optional. Arguments for the remote request.
     * @param bool $cache Whether to use cached response or not.
     * @return mixed Cached response if available or remote response.
     */
    private function maybeRetriveCachedResponse($requestUrl, $requestArgs, $cache) : string | \WP_Error
    {
        $transientKey = $this->createTransientKey($requestUrl);

        if ($cache && $cachedDocument = get_transient($transientKey)) {
            return $cachedDocument;
        }

        return $this->getRemoteAndSetCachedResponse($requestUrl, $requestArgs, $transientKey);
    }

    /**
     * Get remote response and set cached response.
     *
     * @param string $requestUrl The URL to request.
     * @param array $requestArgs Optional. Arguments for the remote request.
     * @param string $transientKey The transient key for caching the response.
     * @return mixed Remote response.
     */
    private function getRemoteAndSetCachedResponse($requestUrl, $requestArgs, $transientKey) : string | \WP_Error
    {
        $response = wp_remote_get($requestUrl, $requestArgs); 

        if (is_wp_error($response) || ($responseCode = wp_remote_retrieve_response_code($response) !== 200)) {    
            if($responseCode !== 200) {
                return new \WP_Error('fetch_error', __('We could not fetch any content at this moment. Please try again later. Response Code ' . ($responseCode), 'modularity'));
            }
            return new \WP_Error('fetch_error', __('We could not fetch any content at this moment. Please try again later.', 'modularity'));
        }

        if ($data = wp_remote_retrieve_body($response)) {
            set_transient($transientKey, $data, $this->cacheTtl);
            set_transient($transientKey . $this->lastUpdatedKey, date("Y-m-d H:i", time()), $this->cacheTtl);
            set_transient($transientKey . $this->nextUpdateKey, date("Y-m-d H:i", time() + $this->cacheTtl), $this->cacheTtl);
        }

        return $data;
    }

    /**
     * Create a transient key for caching the response.
     *
     * @param string $requestUrl The URL to request.
     * @param array $requestArgs Optional. Arguments for the remote request.
     * @return string The transient key for caching the response.
     */
    private function createTransientKey($requestUrl) : string
    {
        return "mod_markdown_" . md5(serialize($requestUrl));
    }
}