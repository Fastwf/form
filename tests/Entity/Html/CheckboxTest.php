<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Checkbox;

class CheckboxTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testSynchronizeValueConstructAttributePriority()
    {
        // Branch value === attributes.value
        $control = new Checkbox([
            'attributes' => ['value' => 'anyString'],
            'value' => 'anyString',
        ]);

        $this->assertTrue($control->isChecked());

        // Branch checked is set and no value
        $control = new Checkbox([
            'attributes' => ['value' => 'anyString'],
            'checked' => true
        ]);

        $this->assertEquals('anyString', $control->getValue());

        // Branch default
        $control = new Checkbox([
            'attributes' => ['value' => 'anyString'],
            'value' => 'invalid',
            'checked' => true,
        ]);

        $this->assertNull($control->getValue());
        $this->assertFalse($control->isChecked());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testCheckboxGetData()
    {
        $control = new Checkbox(['value' => 'on', 'checked' => true]);
        $this->assertTrue($control->getData());

        $control->setChecked(false);
        $this->assertFalse($control->getData());

        $data = 'category 1';

        $control->setAttributes(['value' => $data]);
        $this->assertNull($control->getData());

        $control->setValue($data);
        $this->assertEquals($data, $control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testSynchronizeValueValuePriorityAndIsChecked()
    {
        $control = new Checkbox();
        $this->assertFalse($control->isChecked());

        $control->setValue('on');
        $this->assertTrue($control->isChecked());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testSynchronizeValueCheckedPriority()
    {
        $control = new Checkbox([]);

        $control->setChecked(true);

        $this->assertEquals('on', $control->getValue());
        $this->assertTrue($control->getData());
    }

}
