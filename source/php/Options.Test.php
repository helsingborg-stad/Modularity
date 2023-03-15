<?php

use Modularity\Options;

class OptionsTest extends WP_UnitTestCase {

    protected ?Options $OptionsClass = null;

    public function setUp():void {
        $this->OptionsClass = new class extends Options {};
    }

    public function tear_down()
    {
        $_POST = [];
        $this->OptionsClass = null;
    }

    private function invokeMethod($object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
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

    public function testSaveReturnsWhenInvalidPostSave()
    {
        $this->assertNull($this->OptionsClass->save());
    }

    public function testSaveDoesActionSaveOnSuccess()
    {
        $_POST['modularity-action'] = 'modularity-options';
        $_POST['_wpnonce'] = wp_create_nonce( 'modularity-options' );
        $this->OptionsClass->save();
        $this->assertEquals(1, did_action('Modularity/Options/Save'));
    }

    public function  testGetFieldNameReturnsCorrectValueWhenNotMultiple()
    {
        $result = $this->invokeMethod($this->OptionsClass, 'getFieldName', ['option-name', true]);
        $this->assertEquals('modularity-options[option-name][]', $result);
    }
    
    public function  testGetFieldNameReturnsCorrectValueWhenIsMultiple()
    {
        $result = $this->invokeMethod($this->OptionsClass, 'getFieldName', ['option-name', false]);
        $this->assertEquals('modularity-options[option-name]', $result);
    }
}