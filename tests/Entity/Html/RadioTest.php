<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Api\Exceptions\KeyError;

class RadioTest extends TestCase
{

    // CheckableInput->synchronizeValue is tested in Checkbox

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testKeyError()
    {
        $this->expectException(KeyError::class);

        new Radio(['value' => 'apple']);
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\RadioParser
     */
    public function testGetData()
    {
        $control = new Radio(['attributes' => ['value' => 'apple']]);

        $this->assertNull($control->getData());

        $control->setValue('apple');
        $this->assertEquals('apple', $control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testSetChecked()
    {
        $control = new Radio(['attributes' => ['value' => 'apple']]);

        $control->setChecked(true);
        $this->assertEquals('apple', $control->getValue());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testIsChecked()
    {
        $control = new Radio(['attributes' => ['value' => 'apple']]);
        $this->assertFalse($control->isChecked());

        $control->setValue('apple');
        $this->assertTrue($control->isChecked());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\RadioParser
     */
    public function testGetSValue()
    {
        $control = new Radio(['attributes' => ['value' => 'apple']]);

        $this->assertEquals('apple', $control->getSValue());
    }

}
