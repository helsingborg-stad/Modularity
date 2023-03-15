<?php

use Modularity\Options;

class OptionsTest extends WP_UnitTestCase {

    protected ?Options $OptionsClass = null;

    public function setUp():void {
        $this->OptionsClass = new class extends Options {};
    }
    
    public function testAddMetaBoxesReturnsTrue()
    {
        $this->assertTrue($this->OptionsClass->addMetaBoxes());
    }
    
    public function testIsValidPostSaveReturnsFalseWhenIsNotModularityAction()
    {
        $this->assertFalse($this->OptionsClass->isValidPostSave());
    }

    public function testIsValidPostSaveReturnsFalseWhenModularityActionInvalidType()
    {
        $_POST['modularity-action'] = 'invalid-type';
        $this->assertFalse($this->OptionsClass->isValidPostSave());
    }

    public function testIsValidPostSaveReturnsFalseWhenNonceInvalid()
    {
        $_POST['modularity-action'] = 'modularity-options';
        $_POST['_wpnonce'] = wp_create_nonce( 'invalid-nonce' );
        $this->assertFalse($this->OptionsClass->isValidPostSave());
    }

    public function testIsValidPostSaveReturnsTrueWhenAllParamsValidate()
    {
        $_POST['modularity-action'] = 'modularity-options';
        $_POST['_wpnonce'] = wp_create_nonce( 'modularity-options' );
        $this->assertTrue($this->OptionsClass->isValidPostSave());
    }
}