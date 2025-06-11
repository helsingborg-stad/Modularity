<?php

declare(strict_types=1);

namespace Modularity\Module\Posts\RegisterPaginationQueryVar;

use Modularity\HooksRegistrar\Hookable;
use WpService\Contracts\AddFilter;

/**
 * Registers dynamic pagination query vars for modular posts.
 */
class RegisterPaginationQueryVar implements Hookable
{
    public const PATTERN = '/^mod-posts-(\d+)-page$/';

    /**
     * Constructor.
     * @param array $requestParams GET parameters to check against the pattern.
     * @param AddFilter $wpService Service to add WordPress filters.
     */
    public function __construct(private array $requestParams, private AddFilter $wpService)
    {
    }

    /**
     * Add hooks to WordPress.
     *
     * @return void
     */
    public function addHooks(): void
    {
        $this->wpService->addFilter('query_vars', [$this, 'registerPaginationQueryVars']);
    }

    /**
     * Registers dynamic pagination query vars based on GET parameters.
     *
     * @param array $queryVars Existing query vars.
     * @return array Modified query vars.
     */
    public function registerPaginationQueryVars(array $queryVars): array
    {
        foreach (array_keys($this->requestParams) as $key) {
            if ($this->matchesPattern($key) && !in_array($key, $queryVars, true)) {
                $queryVars[] = $key;
            }
        }

        return $queryVars;
    }

    private function getPattern(): string
    {
        return self::PATTERN;
    }

    private function matchesPattern(string $key): bool
    {
        return preg_match($this->getPattern(), $key) === 1;
    }
}