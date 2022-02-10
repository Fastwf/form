<?php

namespace Fastwf\Tests\Entity\Containers;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Checkbox;
use Fastwf\Form\Entity\Containers\CheckboxGroup;

class CheckboxGroupTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\CheckboxGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testSetValue()
    {
        $group = new CheckboxGroup([
            'name' => 'test',
            'controls' => [
                new Checkbox(['attributes' => ['value' => 'kiwi']]),
                new Checkbox(['attributes' => ['value' => 'pear']]),
                new Checkbox(['checked' => true, 'attributes' => ['value' => 'banana']]),
            ],
        ]);

        $group->setValue(['kiwi']);

        $this->assertTrue($group->getControlAt(0)->isChecked());
        $this->assertFalse($group->getControlAt(2)->isChecked());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\CheckboxGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetValueGetData()
    {
        $group = new CheckboxGroup([
            'name' => 'test',
            'controls' => [
                new Checkbox(['attributes' => ['value' => 'kiwi']]),
                new Checkbox(['checked' => true, 'attributes' => ['value' => 'pear']]),
                new Checkbox(['checked' => true, 'attributes' => ['value' => 'banana']]),
            ],
        ]);

        $this->assertEquals(
            ['pear', 'banana'],
            $group->getData(),
        );
    }

}
