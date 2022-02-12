<?php

namespace Fastwf\Tests\Build;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Build\FormBuilder;
use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Form\Entity\Html\Button;
use Fastwf\Form\Entity\Html\Select;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Form\Exceptions\BuildException;
use Fastwf\Form\Entity\Options\OptionGroup;
use Fastwf\Form\Entity\Containers\RadioGroup;
use Fastwf\Form\Entity\Containers\CheckboxGroup;
use Fastwf\Constraint\Constraints\String\Pattern;

class FormBuilderTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Constraints\StringField
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddInputWithConstraint()
    {
        $form = FormBuilder::new("test")
            ->addInput(
                'amount',
                'text',
                [
                    'constraint' => new Chain(false, new StringField(), new Pattern("\\d+([,.]\\d*)")),
                ],
            )
            ->build();

        $form->setValue([
            'amount' => '3,14',
        ]);
        $this->assertTrue($form->validate());
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Constraints\Date\DateField
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testAddInput()
    {
        $form = FormBuilder::new("test")
            ->addInput(
                'birthDate',
                'date',
                [
                    'assert' => [
                        'max' => (new \DateTime())->format(DateTimeUtil::HTML_DATE_FORMAT),
                    ],
                ],
            )
            ->build();
        
        $invalidDate = new \DateTime();
        $invalidDate->add(new \DateInterval('P2D'));

        $form->setValue([
            'birthDate' => $invalidDate->format(DateTimeUtil::HTML_DATE_FORMAT),
        ]);
        $form->validate();

        $formControl = $form->getControlAt(0);

        $this->assertNotNull($formControl->getViolation());
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Textarea
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddTextarea()
    {
        $body = 'Hello world!';

        $form = FormBuilder::new("test")
            ->addTextarea(
                'body',
                [
                    'label' => 'Email body',
                    'defaultValue' => $body,
                ],
            )
            ->build();
        
        $this->assertEquals(
            [
                'body' => $body,
            ],
            $form->getData(),
        );
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Constraints\BooleanField
     * @covers Fastwf\Form\Constraints\String\Equals
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testAddCheckboxOnOff()
    {
        $form = FormBuilder::new('test')
            ->addCheckbox('accept_cookies', [])
            ->build();
        
        $form->setValue(['accept_cookies' => 'on']);

        $this->assertTrue($form->validate());
        $this->assertEquals(['accept_cookies' => true], $form->getData());
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\String\Equals
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddCheckboxValue()
    {
        $form = FormBuilder::new('test')
            ->addCheckbox(
                'fruit',
                [
                    'checked' => true,
                    'value' => 'kiwi'
                ]
            )
            ->build();

        $this->assertEquals(['fruit' => 'kiwi'], $form->getData());
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Button
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddButton()
    {
        $body = 'Hello world!';

        $form = FormBuilder::new("test")
            ->addButton(
                'body',
                ['label' => 'Submit'],
            )
            ->build();
        
        $this->assertTrue($form->getControlAt(0) instanceof Button);
    }

    /// CHOICES

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddSelectSingle()
    {
        $form = FormBuilder::new("test")
            ->addSelect(
                'gender', [
                'choices' => [
                    [
                        'value' => 'male',
                        'label' => 'Male',
                    ],
                    [
                        'value' => 'female',
                        'label' => 'Female',
                    ],
                ],
            ])
            ->build();

        $field = $form->getControlAt(0);

        $this->assertTrue($field instanceof Select);
        $this->assertEquals(2, \count($field->getOptions()));
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Entity\Options\OptionGroup
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddSelectMultiple()
    {
        $form = FormBuilder::new("test")
            ->addSelect(
                'fruits',
                [
                    'choices' => [
                        [
                            'label' => 'Winter',
                            'options' => [
                                ['value' => 'Apple'],
                                ['value' => 'Pear'],
                                ['value' => 'Kiwi'],
                            ],
                        ],
                        [
                            'label' => 'Summer',
                            'options' => [
                                ['value' => 'Peach'],
                                ['value' => 'Watermelon'],
                            ],
                        ],
                    ],
                    'multiple' => true,
                ],
            )
            ->build();

        $field = $form->getControlAt(0);

        $this->assertTrue($field->getOptions()[0] instanceof OptionGroup);
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\OptionGroup
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddSelectBadChoices()
    {
        $this->expectException(BuildException::class);

        FormBuilder::new("test")
            ->addSelect(
                'groups',
                [
                    'choices' => [
                        [
                            'label' => 'parent',
                            'options' => [
                                [
                                    'label' => 'Children',
                                    'options' => [],
                                ],
                            ],
                        ],
                    ],
                    'multiple' => true,
                ],
            );
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Build\Groups\EntityGroupBuilder
     * @covers Fastwf\Form\Build\Groups\RadioGroupBuilder
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddRadioGroup()
    {
        $form = FormBuilder::new("test")
            ->addRadioGroup('gender', [
                'choices' => [
                    [
                        'value' => 'male',
                        'label' => 'Male',
                    ],
                    [
                        'value' => 'female',
                        'label' => 'Female',
                    ],
                ],
            ])
            ->build();

        $this->assertTrue($form->getControlAt(0) instanceof RadioGroup);
        $this->assertEquals(2, \count($form->getControlAt(0)->getControls()));
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Build\Groups\EntityGroupBuilder
     * @covers Fastwf\Form\Build\Groups\CheckboxGroupBuilder
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\CheckboxGroup
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Checkbox
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testAddCheckboxGroup()
    {
        $items = ['Archer', 'Knight'];

        $form = FormBuilder::new("test")
            ->addCheckboxGroup('army', [
                'choices' => [
                    [
                        'value' => 'Archer',
                    ],
                    [
                        'value' => 'Knight',
                    ],
                    [
                        'value' => 'Catapult',
                    ],
                ],
                'defaultValue' => ['Archer', 'Knight'],
            ])
            ->build();

        \var_dump($form);
        $this->assertTrue($form->getControlAt(0) instanceof CheckboxGroup);
        $this->assertEquals(3, \count($form->getControlAt(0)->getControls()));
        $this->assertEquals(
            ['army' => $items],
            $form->getData(),
        );
        $this->assertTrue($form->validate());
    }

}
