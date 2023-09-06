<?php

use Modularity\Editor;

class EditorTest extends PHPUnit\Framework\TestCase
{
    protected static $postBackup = null;

    public static function setUpBeforeClass(): void
    {
        self::$postBackup = $_POST;
    }

    public static function tearDownAfterClass(): void
    {
        $_POST = self::$postBackup;
    }

    protected function setUp(): void
    {
        $_POST = array();
    }

    public function testPageWasLoadedBeforeSaveReturnsFalseIfNotLoaded()
    {
        $editor = new Editor();
        $_POST[Editor::EDITOR_PAGE_LOADING_KEY] = 'fake';

        $this->assertFalse($editor->pageWasLoadedBeforeSave());
    }

    public function testPageWasLoadedBeforeSaveReturnsTrueIfLoaded()
    {
        $editor = new Editor();

        $this->assertTrue($editor->pageWasLoadedBeforeSave());
    }
}
