<?php

namespace Fastwf\Tests\Entity\Options;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Options\Option;

class OptionTest extends TestCase
{

    /// Test AOption

    /**
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     */
    public function testSetGetDisabled()
    {
        $option = new Option(['value' => 'test']);

        $this->assertFalse($option->isDisabled());
        
        $option->setDisabled(true);
        $this->assertTrue($option->isDisabled());
    }

    /**
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     */
    public function testSetGetLabel()
    {
        $option = new Option(['value' => 'test']);

        $this->assertEquals('', $option->getLabel());
        
        $option->setLabel('label');
        $this->assertEquals('label', $option->getLabel());
    }

    /// Test Option

    /**
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     */
    public function testGetTag()
    {
        $html = new Option(['value' => 'test']);

        $this->assertEquals('option', $html->getTag());
    }

    /**
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     */
    public function testSetGetValue()
    {
        $option = new Option(['value' => 'test']);

        $this->assertEquals('test', $option->getValue());
        
        $option->setValue('value');
        $this->assertEquals('value', $option->getValue());
    }

}