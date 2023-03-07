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

    public static function wpSetUpBeforeClass(WP_UnitTest_Factory $factory)
    {
        self::$post = $factory->post->create_and_get();
    }

    public function testClassInstantiationRegistersHooks() {
        $ajax = new Ajax();
        $this->assertSame(10, has_action('wp_ajax_get_post', array($ajax, 'getPost')));
        $this->assertSame(10, has_action('wp_ajax_get_post_modules', array($ajax, 'getPostModules')));
    }

    /**
     * Returns Post on call.
     */
    public function testGetPostReturnsPost()
    {
        $action = 'get_post';
        $_POST['action'] = $action;
        $_POST['id'] = self::$post->ID;

        try {
            $this->_handleAjax($action);
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $result = json_decode($this->_last_response);
        $this->assertSame(self::$post->ID, $result->ID);
    }
    
    /**
     * Returns false if Post does not exist.
     */
    public function testGetPostReturnsFalseWhenPostNotExist()
    {
        $action = 'get_post';
        $_POST['action'] = $action;
        $_POST['id'] = 123456;

        try {
            $this->_handleAjax($action);
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $this->assertSame('false', $this->_last_response);
    }

    /**
     * Returns 'false' when no Post ID given.
     */
    public function testGetPostWithoutRequiredID()
    {
        $action = 'get_post';
        $_POST['action'] = $action;
        $_POST['id'] = null;

        try {
            $this->_handleAjax($action);
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $this->assertSame('false', $this->_last_response);
    }

    /**
     * Returns 'false' when no Post ID given.
     */
    public function testGetPostModulesWithoutRequiredID()
    {
        $action = 'get_post_modules';
        $_POST['action'] = $action;
        $_POST['id'] = null;

        try {
            $this->_handleAjax($action);
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $this->assertSame('false', $this->_last_response);
    }

    /**
     * Returns Post modules.
     */
    public function testGetPostModulesReturnsPostModules()
    {
        $action = 'get_post_modules';
        $_POST['action'] = $action;
        $_POST['id'] = self::$post->ID;

        try {
            $this->_handleAjax($action);
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $this->assertSame('[]', $this->_last_response);
    }
}
