<?php 

namespace Modularity\Module\Markdown\Filters;

/**
 * Interface FilterInterface
 *
 * @package Modularity\Module\Markdown\Filters
 */

interface FilterInterface {
    /**
     * Filter the content.
     *
     * @param string $content The content to filter.
     *
     * @return string The filtered content.
     */
    public function filter(string $content): string;
}