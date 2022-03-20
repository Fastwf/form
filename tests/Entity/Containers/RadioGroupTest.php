<?php

namespace Fastwf\Tests\Entity\Containers;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Form\Entity\Containers\RadioGroup;
use Fastwf\Constraint\Constraints\String\Enum;
use Fastwf\Form\Build\FormBuilder;

class RadioGroupTest extends TestCase
{

    /// Test EntityGroup

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     */
    public function testSetGetConstraint()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'female']]),
            ],
        ]);

        $group->setConstraint(new Enum(['male', 'female']));

        $this->assertTrue(
            (new Validator($group->getConstraint()))->validate('male'),
        );
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     */
    public function testSetGetValidation()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'female']]),
            ],
            'constraint' => new Enum(['male', 'female']),
        ]);

        $this->assertNull($group->getViolation());

        $validator = new Validator($group->getConstraint());
        $validator->validate('invalid');

        $group->setViolation($validator->getViolations());

        $this->assertNotNull($group->getViolation());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     */
    public function testSetGetHelp()
    {
        $initial = 'Initial help';
        $help = 'This is radio group help message';

        $group = new RadioGroup([
            'name' => 'radio',
            'help' => $initial,
            'controls' => [],
        ]);

        $this->assertEquals($initial, $group->getHelp());
        
        $group->setHelp($help);

        $this->assertEquals($help, $group->getHelp());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     */
    public function testGetContainerType()
    {
        $group = new RadioGroup(['controls' => []]);

        $this->assertEquals('widget', $group->getContainerType());
    }

    /// Test RadioGroup

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     */
    public function testSetValue()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'female']]),
            ],
        ]);

        /** @var Radio */
        $control = $group->getControlAt(0);
        $this->assertFalse($control->isChecked());

        $group->setValue('male');

        $this->assertTrue($control->isChecked());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     */
    public function testGetValue()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'checked' => true, 'attributes' => ['value' => 'female']]),
            ],
        ]);

        $this->assertEquals('female', $group->getValue());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     */
    public function testGetDataNull()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'female']]),
            ],
        ]);

        $this->assertNull($group->getData());
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\RadioGroupConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\Groups\EntityGroupBuilder
     * @covers Fastwf\Form\Build\Groups\RadioGroupBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     */
    public function testGetFullNameRadioInsideGroup()
    {
        $form = FormBuilder::new('id', 'test')
            ->addRadioGroup('gender', [
                'choices' => [
                    [ 'value' => 'male', 'label' => 'Male' ],
                    [ 'value' => 'female', 'label' => 'Female' ],
                ]
            ])
            ->build();

        /** @var RadioGroup */
        $group = $form->getControlAt(0);
        
        $this->assertEquals('gender', $group->getControlAt(0)->getFullName());

        $form->setName('form');
        $this->assertEquals('form[gender]', $group->getControlAt(0)->getFullName());
    }

}
