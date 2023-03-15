<?php

use Modularity\Ajax;

/**
 * Testing Ajax functionality.
 *
 * @group ajax
 */
class AjaxTest extends WP_Ajax_UnitTestCase
{

    protected static ?WP_Post $post = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $factory = new WP_UnitTest_Factory();
        self::$post = $factory->post->create_and_get();
    }

    /**
     * Returns Post on call.
     */
    public function testGetPostWithExistingPostIDReturnsPost()
    {
        // Given
        $_POST['action'] = 'get_post';
        $_POST['id'] = self::$post->ID;

        // When
        $response = $this->doAjaxRequest($_POST['action']);
        $result = json_decode($response);

        // Then
        $this->assertSame(self::$post->ID, $result->ID);
    }

    /**
     * Returns false if Post does not exist.
     */
    public function testGetPostReturnsFalseWhenPostNotExist()
    {
        // Given
        $_POST['action'] = 'get_post';
        $_POST['id'] = 123456;

        // When
        $response = $this->doAjaxRequest($_POST['action']);

        // Then
        $this->assertSame('false', $response);
    }

    /**
     * Returns 'false' when no Post ID given.
     */
    public function testGetPostWithoutRequiredID()
    {
        // Given
        $_POST['action'] = 'get_post';
        $_POST['id'] = null;

        // When
        $response = $this->doAjaxRequest($_POST['action']);

        // Then
        $this->assertSame('false', $response);
    }

    /**
     * Returns 'false' when no Post ID given.
     */
    public function testGetPostModulesWithoutRequiredID()
    {
        // Given
        $_POST['action'] = 'get_post_modules';
        $_POST['id'] = null;

        // When
        $response = $this->doAjaxRequest('get_post_modules');

        // Then
        $this->assertSame('false', $response);
    }

    /**
     * Returns Post modules.
     */
    public function testGetPostModulesReturnsPostModules()
    {
        // Given
        $_POST['action'] = 'get_post_modules';
        $_POST['id'] = self::$post->ID;

        // When
        $response = $this->doAjaxRequest('get_post_modules');

        // Then
        $this->assertSame('[]', $response);
    }

    private function doAjaxRequest(string $action):string
    {
        try {
            $this->_handleAjax($action);
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        return $this->_last_response;
    }
}
