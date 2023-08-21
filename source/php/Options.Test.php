<?php

class SUT extends Modularity\Options
{
}

class OptionsTest extends WP_UnitTestCase
{

    protected $sut = null;
    protected $user = null;

    public function set_up()
    {
        parent::set_up();

        $this->sut = new SUT();
    }

    public function testIsValidPostSaveReturnsTrueIfActionAndNonceValid()
    {
        $_POST['modularity-action'] = 'modularity-options';
        $_POST['_wpnonce'] = wp_create_nonce('modularity-options');
        $this->assertTrue($this->sut->isValidPostSave());
    }

    public function testIsValidPostSaveReturnsFalseIfNotLoadedIndicatorSet()
    {
        $_POST['modularity-action'] = 'modularity-options';
        $_POST['_wpnonce'] = wp_create_nonce('modularity-options');
        $_POST['modularity-option-page-loading'] = 'foo';
        $this->assertFalse($this->sut->isValidPostSave());
    }

    public function testIsValidPostSaveReturnsFalseIfActionNotSet()
    {
        $this->assertFalse($this->sut->isValidPostSave());
    }

    public function testIsValidPostSaveReturnsFalseIfAction()
    {
        $_POST['modularity-action'] = 'foo';
        $this->assertFalse($this->sut->isValidPostSave());
    }

    public function testIsValidPostSaveReturnsFalseIfNonceNotSet()
    {
        $_POST['modularity-action'] = 'modularity-options';
        $this->assertFalse($this->sut->isValidPostSave());
    }

    public function testIsValidPostSaveReturnsFalseIfNonceInvalid()
    {
        $_POST['modularity-action'] = 'modularity-options';
        $_POST['_wpnonce'] = 'foo';
        $this->assertFalse($this->sut->isValidPostSave());
    }
}
