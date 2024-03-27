<?php

namespace Modularity\Helper;

use Mockery;
use WP_Mock\Tools\TestCase;
use WP_Mock;

class UpdateDateOnPostsRelatedToModuleTest extends TestCase
{
    /**
     * @testdox related posts get a new post_modified date, which reflects the post_modified date of the module.
     */
    public function testIfSuppliedModuleIsNotAModule() {
        $modulePost = Mockery::mock(\WP_Post::class);
        $modulePost->ID = 1;
        $modulePost->post_type = 'mod-module';
        $modulePost->post_modified = '2020-01-01 00:00:00';
        $relatedPost = new \stdClass();
        $relatedPost->post_id = 2;
        $moduleManager = $this->getModuleManager([$modulePost->ID => [$relatedPost]]);

        WP_Mock::userFunction('wp_update_post')->once()->with([
            'ID' => 2,
            'post_modified' => '2020-01-01 00:00:00',
            'post_modified_gmt' => '2020-01-01 00:00:00'
        ]);
        WP_Mock::userFunction('get_gmt_from_date')->once()->andReturn('2020-01-01 00:00:00');

        $updateDateOnPostsRelatedToModule = new UpdateDateOnPostsRelatedToModule($moduleManager);
        $updateDateOnPostsRelatedToModule->update($modulePost);

        $this->assertConditionsMet();
    }

    private function getModuleManager(array $relatedPostsByModuleId = []):\Modularity\ModuleManager {
        return new class($relatedPostsByModuleId) extends \Modularity\ModuleManager {

            private static array $relatedPostsByModuleId;

            public function __construct(array $relatedPostsByModuleId)
            {
                self::$relatedPostsByModuleId = $relatedPostsByModuleId;
            }

            public static function getModuleUsage($id, $limit = false) {
                return self::$relatedPostsByModuleId[$id] ?? [];
            }
        };
    }
}
