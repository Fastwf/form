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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testIsChecked()
    {
        $control = new Checkbox();
        $this->assertFalse($control->isChecked());

        $control->setValue('on');
        $this->assertTrue($control->isChecked());
    }

}
