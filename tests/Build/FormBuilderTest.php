<?php

namespace Fastwf\Tests\Build;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Form;
use Fastwf\Form\Build\FormBuilder;
use Fastwf\Form\Entity\FormControl;
use Fastwf\Form\Entity\Html\Button;
use Fastwf\Form\Entity\Html\Select;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Entity\Html\InputFile;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Form\Exceptions\BuildException;
use Fastwf\Form\Entity\Options\OptionGroup;
use Fastwf\Form\Entity\Containers\RadioGroup;
use Fastwf\Form\Entity\Containers\CheckboxGroup;
use Fastwf\Constraint\Constraints\String\Pattern;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Tests\Build\Security\TestingSecurityPolicy;

class FormBuilderTest extends TestCase
{

    private const FORM_ID = "form_id";

    /**
     * @covers Fastwf\Form\Build\FormBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\StringField
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testAddInputWithConstraint()
    {
        $form = FormBuilder::new(self::FORM_ID, "test")
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
     * @covers Fastwf\Form\Build\Constraints\TransformConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
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
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testAddInput()
    {
        $form = FormBuilder::new(self::FORM_ID, "test")
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

        /** @var FormControl */
        $formControl = $form->getControlAt(0);

        $this->assertNotNull($formControl->getViolation());
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Textarea
     */
    public function testAddTextarea()
    {
        $body = 'Hello world!';

        $form = FormBuilder::new(self::FORM_ID, "test")
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
     * @covers Fastwf\Form\Build\Constraints\TransformConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\CheckboxConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
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
     * @covers Fastwf\Form\Parsing\CheckboxParser
     */
    public function testAddCheckboxOnOff()
    {
        $form = FormBuilder::new(self::FORM_ID, 'test')
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
     * @covers Fastwf\Form\Build\Constraints\TransformConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\CheckboxConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
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
     * @covers Fastwf\Form\Parsing\CheckboxParser
     */
    public function testAddCheckboxValue()
    {
        $form = FormBuilder::new(self::FORM_ID, 'test')
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Button
     */
    public function testAddButton()
    {
        $body = 'Hello world!';

        $form = FormBuilder::new(self::FORM_ID, "test")
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testAddSelectSingle()
    {
        $form = FormBuilder::new(self::FORM_ID, "test")
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

        /** @var Select */
        $field = $form->getControlAt(0);

        $this->assertTrue($field instanceof Select);
        $this->assertEquals(2, \count($field->getOptions()));
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Entity\Options\OptionGroup
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testAddSelectMultiple()
    {
        $form = FormBuilder::new(self::FORM_ID, "test")
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

        /** @var Select */
        $field = $form->getControlAt(0);

        $this->assertTrue($field->getOptions()[0] instanceof OptionGroup);
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\OptionGroup
     */
    public function testAddSelectBadChoices()
    {
        $this->expectException(BuildException::class);

        FormBuilder::new(self::FORM_ID, "test")
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\String\Equals
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\InputFile
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testAddInputFile()
    {
        $form = FormBuilder::new(self::FORM_ID, "test")
            ->addInputFile(
                'file',
                [
                    'multiple' => true,
                ],
            )
            ->build();
        
        $field = $form->getControlAt(0);

        $this->assertTrue($field instanceof InputFile);
        $this->assertEquals(Form::FORM_MULTIPART, $form->getEnctype());
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
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
     * @covers Fastwf\Form\Build\Constraints\Widget\RadioGroupConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Groups\EntityGroupBuilder
     * @covers Fastwf\Form\Build\Groups\RadioGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
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
     */
    public function testAddRadioGroup()
    {
        $form = FormBuilder::new(self::FORM_ID, "test")
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

        /** @var RadioGroup */
        $control = $form->getControlAt(0);
        $this->assertTrue($control instanceof RadioGroup);
        $this->assertEquals(2, \count($control->getControls()));
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
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
     * @covers Fastwf\Form\Build\Constraints\Widget\CheckboxGroupConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Groups\EntityGroupBuilder
     * @covers Fastwf\Form\Build\Groups\CheckboxGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
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
     */
    public function testAddCheckboxGroup()
    {
        $items = ['Archer', 'Knight'];

        $form = FormBuilder::new(self::FORM_ID, "test")
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

        /** @var CheckboxGroup */
        $control = $form->getControlAt(0);
        $this->assertTrue($control instanceof CheckboxGroup);
        $this->assertEquals(3, \count($control->getControls()));
        $this->assertEquals(
            ['army' => $items],
            $form->getData(),
        );
        $this->assertTrue($form->validate());
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Security\ASecurityPolicy
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\String\Equals
     * @covers Fastwf\Form\Constraints\StringField
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\SecurityUtil
     */
    public function testSetSecureForm()
    {
        $policy = new TestingSecurityPolicy(null, '_csrf_token', 'application_seed');

        $form = FormBuilder::new(self::FORM_ID, "test", ['securityPolicy' => $policy])
            ->build();
        
        // Setup a new CSRF token in form
        $policy->newCsrfToken(self::FORM_ID, );
        
        /** @var Input */
        $control = $form->getControlAt(0);
        $this->assertEquals('hidden', $control->getType());

        // Verify that the value is set with generated CSRF token
        $this->assertEquals(
            ['_csrf_token' => $policy->getLastToken()],
            $form->getValue()
        );
    }

    /**
     * @covers Fastwf\Form\Build\FormBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Security\ASecurityPolicy
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\String\Equals
     * @covers Fastwf\Form\Constraints\StringField
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\StringParser
     * @covers Fastwf\Form\Utils\SecurityUtil
     */
    public function testSetSecureFormUsingExistingToken()
    {
        $token = "test_token";
        $policy = new TestingSecurityPolicy($token);

        $form = FormBuilder::new(self::FORM_ID, "test", ['securityPolicy' => $policy])
            ->build();
        
        $form->setValue(['__token' => $token]);

        $this->assertTrue($form->validate());
    }

}
