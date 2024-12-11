<?php

namespace Modularity\Module\Posts\Helper;

use Modularity\Helper\WpQueryFactory\WpQueryFactoryInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use WpService\Implementations\FakeWpService;

class GetPostsTest extends TestCase {

    protected function tearDown(): void
    {
        unset($GLOBALS['post']);
    }

    #[TestDox('getCurrentPostID() returns the current post ID')]
    public function testGetCurrentPostIDReturnsTheCurrentPostID() {
        $wpService = new FakeWpService(['getTheID' => 1, 'isArchive' => false]);
        $wpQueryFactory = $this->createStub(WpQueryFactoryInterface::class);
        
        $getPosts = new GetPosts($wpService, $wpQueryFactory);

        $this->assertEquals(1, $getPosts->getCurrentPostID());
    }
    
    #[TestDox('getCurrentPostID() returns false when post is not set')]
    public function testGetCurrentPostIDReturnsFalseWhenPostIsNotSet() {
        $wpService = new FakeWpService(['getTheID' => false, 'isArchive' => false]);
        $wpQueryFactory = $this->createStub(WpQueryFactoryInterface::class);
        
        $getPosts = new GetPosts($wpService, $wpQueryFactory);

        $this->assertFalse($getPosts->getCurrentPostID());
    }
    
    
    #[TestDox('getCurrentPostID() returns false when in archive context')]
    public function testGetCurrentPostIDReturnsFalseWhenInArchiveContext() {
        $wpService = new FakeWpService(['getTheID' => 1, 'isArchive' => true]);
        $wpQueryFactory = $this->createStub(WpQueryFactoryInterface::class);
        
        $getPosts = new GetPosts($wpService, $wpQueryFactory);

        $this->assertFalse($getPosts->getCurrentPostID());
    }
}