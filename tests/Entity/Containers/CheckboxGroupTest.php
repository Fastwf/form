<?php

namespace Fastwf\Tests\Entity\Containers;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Build\FormBuilder;
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

        /** @var Checkbox */
        $control = $group->getControlAt(0);
        $this->assertTrue($control->isChecked());
        
        /** @var Checkbox */
        $control = $group->getControlAt(2);
        $this->assertFalse($control->isChecked());
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

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\AConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\DateConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\DateTimeConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\MonthConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\NumberConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\TimeConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\WeekConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\EmailConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\StringConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\CheckboxGroupConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\Groups\CheckboxGroupBuilder
     * @covers Fastwf\Form\Build\Groups\EntityGroupBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetFullNameRadioInsideGroup()
    {
        $form = FormBuilder::new('id', 'test')
            ->addCheckboxGroup('fruits', [
                'choices' => [
                    ['value' => 'kiwi'],
                    ['value' => 'pear'],
                    ['value' => 'banana']
                ]
            ])
            ->build();

        /** @var CheckboxGroup */
        $group = $form->getControlAt(0);
        
        $this->assertEquals('fruits[]', $group->getControlAt(0)->getFullName());

        $form->setName('form');
        $this->assertEquals('form[fruits][]', $group->getControlAt(0)->getFullName());
    }

}
