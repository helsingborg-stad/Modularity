<?php

declare(strict_types=1);

namespace Modularity\Module\Posts\RegisterPaginationQueryVar;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use WpService\Contracts\AddFilter;

/**
 * Registers dynamic pagination query vars for modular posts.
 */
class RegisterPaginationQueryVarTest extends \PHPUnit\Framework\TestCase{

    #[TestDox('can be instantiated')]
    public function testCanBeInstantiated(): void
    {
        $wpService = $this->getWpServiceMock();
        $instance = new RegisterPaginationQueryVar([], $wpService);
        $this->assertInstanceOf(RegisterPaginationQueryVar::class, $instance);
    }

    #[TestDox('attaches to the query_vars filter')]
    public function testAttachesToQueryVarsFilter(): void
    {
        $wpService = $this->getWpServiceMock();
        $instance = new RegisterPaginationQueryVar([], $wpService);

        $wpService->expects($this->once())
            ->method('addFilter')
            ->with('query_vars', [$instance, 'registerPaginationQueryVars']);

        $instance->addHooks();
    }

    #[TestDox('registers pagination query var if it matches the pattern')]
    public function testRegistersPaginationQueryVars(): void
    {
        $queryVars = ['existing_var'];
        $instance = new RegisterPaginationQueryVar(['mod-posts-123-page' => '1'], $this->getWpServiceMock());

        $result = $instance->registerPaginationQueryVars($queryVars);
        $this->assertContains('mod-posts-123-page', $result);
    }

    #[TestDox('does not register pagination query var if it does not match the pattern')]
    public function testDoesNotRegisterNonMatchingQueryVars(): void
    {
        $queryVars = ['existing_var'];
        $instance = new RegisterPaginationQueryVar(['mod-posts-invalid-page' => '1'], $this->getWpServiceMock());

        $result = $instance->registerPaginationQueryVars($queryVars);
        $this->assertEquals(['existing_var'], $result);
    }
    
    #[TestDox('does not register pagination query var if it is already in the query vars')]
    public function testDoesNotRegisterExistingQueryVars(): void
    {
        $queryVars = ['mod-posts-123-page', 'existing_var'];
        $instance = new RegisterPaginationQueryVar(['mod-posts-123-page' => '1'], $this->getWpServiceMock());

        $result = $instance->registerPaginationQueryVars($queryVars);
        $this->assertEquals(['mod-posts-123-page', 'existing_var'], $result);
    }

    private function getWpServiceMock(): AddFilter|MockObject
    {
        return $this->createMock(AddFilter::class);
    }

}