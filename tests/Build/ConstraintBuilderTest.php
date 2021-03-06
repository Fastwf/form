<?php

namespace Fastwf\Tests\Build;

use PHPUnit\Framework\TestCase;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Api\Exceptions\KeyError;
use Fastwf\Api\Exceptions\ValueError;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Build\Constraints\AConstraintBuilder;

class ConstraintBuilderTest extends TestCase
{

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testBuild()
    {
        $constraints = ConstraintBuilder::getDefault()
            ->from('input', 'text')
            ->add('required', null, [])
            ->add('minLength', 2, [])
            ->add('maxLength', 25, [])
            ->add('pattern', '^[a-z]{2,25}$', [])
            ->build();
        
        $this->assertEquals(
            [
                'required' => true,
                'minlength' => 2,
                'maxlength' => 25,
                'pattern' => '^[a-z]{2,25}$'
            ],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\String\ColorConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\TransformConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\StringField
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\String\ColorFormat
     */
    public function testBuildColor()
    {
        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'color', []);
        
        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('#FF0000'));
        $this->assertFalse($validator->validate('#FF0'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\String\UrlConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\TransformConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\StringField
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testBuildUrl()
    {
        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'url', []);
        
        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('http://localhost:8000/path/to/resource.json'));
        $this->assertFalse($validator->validate('invalid url'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Constraints\Date\DateField
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testBuildNumericDateConstraint()
    {
        $assert = [
            'min' => '2022-01-01',
            'max' => '2022-01-31',
            'step' => 2,
        ];

        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'date');
        
        foreach ($assert as $name => $value) {
            $builder->add($name, $value, $assert);
        }

        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('2022-01-15'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Constraints\Date\DateTimeField
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testBuildNumericDateTimeConstraint()
    {
        $assert = [
            'min' => '2022-01-01T12:00:00.000',
            'max' => '2022-01-01T12:00:00.500',
            'step' => 0.100,
        ];

        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'datetime-local');
        
        foreach ($assert as $name => $value) {
            $builder->add($name, $value, $assert);
        }

        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('2022-01-01T12:00:00.200'));
        $this->assertFalse($validator->validate('2022-01-01T12:00:00.600'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Constraints\Date\MonthField
     * @covers Fastwf\Form\Constraints\Date\StepMonth
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testBuildNumericMonthConstraint()
    {
        $assert = [
            'min' => '2022-01',
            'max' => '2022-05',
            'step' => 2,
        ];

        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'month');
        
        foreach ($assert as $name => $value) {
            $builder->add($name, $value, $assert);
        }

        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('2022-03'));
        $this->assertFalse($validator->validate('2022-04'));
    }

    /** 
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\IntegerField
     */
    public function testBuildInputNumber()
    {
        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'number');

        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('15'));
    }

    /** 
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\DoubleField
     * @covers Fastwf\Form\Constraints\Number\Step
     */
    public function testBuildInputNumberStepString()
    {
        $asserts = ['step' => '0.1'];

        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'number', $asserts)
            ->add('step', '0.1', $asserts);

        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('15.5'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\DoubleField
     * @covers Fastwf\Form\Constraints\Number\Step
     */
    public function testBuildInputRange()
    {
        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'range', ['step' => 0.2])
            ->add('step', 0.2, []);

        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('15.2'));
        $this->assertFalse($validator->validate('15.3'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\TimeFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\Time\TimeField
     * @covers Fastwf\Form\Constraints\Time\MinTime
     * @covers Fastwf\Form\Constraints\Time\MaxTime
     * @covers Fastwf\Form\Constraints\Time\StepTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testBuildTimeInput()
    {
        $assert = [
            'min' => '07:00:30.000',
            'max' => '12:00:30.500',
            'step' => 0.1,
        ];

        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'time');
        
        foreach ($assert as $name => $value) {
            $builder->add($name, $value, $assert);
        }

        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('08:00:00.100'));
        $this->assertFalse($validator->validate('13:00:00.150'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\Date\WeekField
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testBuildWeekInput()
    {
        $assert = [
            'min' => '2020-W01',
            'max' => '2020-W30',
            'step' => 2,
        ];

        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'week');
        
        foreach ($assert as $name => $value) {
            $builder->add($name, $value, $assert);
        }

        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate('2020-W09'));
        $this->assertFalse($validator->validate('2021-W10'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\StringField
     */
    public function testBuildInputEmail()
    {
        $this->assertTrue(
            (new Validator(
                ConstraintBuilder::getDefault()
                    ->from('input', 'email', [])
                    ->add('required', true, [])
                    ->build()[ConstraintBuilder::CSTRT]
            ))->validate('email1@test.com')
        );
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\StringField
     * @covers Fastwf\Form\Constraints\String\SplitModifier
     */
    public function testBuildInputEmailMultiple()
    {
        $this->assertTrue(
            (new Validator(
                ConstraintBuilder::getDefault()
                    ->from('input', 'email', [])
                    ->add('multiple', true, [])
                    ->add('required', true, [])
                    ->build()[ConstraintBuilder::CSTRT]
            ))->validate('email1@test.com, email2@test.com, email3@test.com')
        );
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\StringField
     */
    public function testBuildSelectSingle()
    {
        $builder = ConstraintBuilder::getDefault()
            ->from('select')
            ->add('enum', ['1', '2', '3'], []);
        
        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);
        $this->assertTrue($validator->validate('1'));
        $this->assertFalse($validator->validate('4'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     */
    public function testBuildSelectMultiple()
    {
        $asserts = [
            'multiple' => true,
            'enum' => ['1', '2', '3'],
            'required' => true,
        ];
        $builder = ConstraintBuilder::getDefault()
            ->from('select', null, $asserts);
        
        foreach ($asserts as $name => $args)
        {
            $builder->add($name, $args, $asserts);
        }
        
        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);
        $this->assertTrue($validator->validate(['1', '2']));
        $this->assertFalse($validator->validate(['3', '4']));
        $this->assertFalse($validator->validate(null));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\CheckboxConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\BooleanField
     */
    public function testBuildCheckboxSystemOnOff()
    {
        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'checkbox', []);
        
        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);
        $this->assertTrue($validator->validate('on'));
        $this->assertTrue($validator->validate('off'));
        $this->assertTrue($validator->validate(null));
        $this->assertFalse($validator->validate('test'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\CheckboxGroupConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\StringField
     * @covers Fastwf\Form\Constraints\BooleanField
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\String\Equals
     */
    public function testBuildCheckboxSystemValue()
    {
        $builder = ConstraintBuilder::getDefault()
            ->from('input', 'checkbox', ['equals' => 'value'])
            ->add('equals', 'value', []);
        
        $validator = new Validator($builder->build()[ConstraintBuilder::CSTRT]);
        $this->assertTrue($validator->validate('value'));
        $this->assertFalse($validator->validate('test'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\RadioGroupConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     */
    public function testBuildRadioGroupSystem()
    {
        $constraint = ConstraintBuilder::getDefault()
            ->from('radio-group')
            ->add('enum', ['male', 'female'], [])
            ->build();
        
        $validator = new Validator($constraint[ConstraintBuilder::CSTRT]);

        $this->assertTrue($validator->validate(null));
        $this->assertTrue($validator->validate('male'));
        $this->assertFalse($validator->validate('invalid'));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\CheckboxGroupConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     */
    public function testBuildCheckboxGroupSystem()
    {
        $constraint = ConstraintBuilder::getDefault()
            ->from('checkbox-group')
            ->add('enum', ['male', 'female', 'neutral'], [])
            ->add('array', ['minItems' => 1, 'maxItems' => 2, 'uniqueItems' => true], [])
            ->build();
        
        $validator = new Validator($constraint[ConstraintBuilder::CSTRT]);

        // Auto set to required true -> so will be false
        $this->assertFalse($validator->validate([null]));

        $this->assertTrue($validator->validate(['male']));
        $this->assertFalse($validator->validate(['invalid']));
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     */
    public function testNotFoundConstraint()
    {
        $this->expectException(KeyError::class);

        ConstraintBuilder::getDefault()
            ->from('input', 'password')
            ->add('password', null, []);
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     */
    public function testSetFactory()
    {
        $this->expectException(KeyError::class);

        ConstraintBuilder::getDefault()
            ->setFactory('ssh-key', function (...$_) {
                // no constraints
                return null;
            })
            ->setFactory('ssh-key', null)
            ->from('textarea', null)
            ->add('ssh-key', null, []);
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     */
    public function testSetGetBuilderWithNull()
    {
        // Remove an existing builder using null value
        $this->assertNull(
            ConstraintBuilder::getDefault()
                ->setBuilder(null, 'select')
                ->getBuilder('select')
        );
    }

    /**
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
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     */
    public function testRegisterError()
    {
        $this->expectException(ValueError::class);

        ConstraintBuilder::getDefault()
            ->setFactory('ssh-key', 'not callable value');
    }

    /**
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\AConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\StringConstraintBuilder
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testChainConstraints()
    {
        // Test there are no constraint when null is passed to chainConstraints
        $builder = new ConstraintBuilder();
        $params = $builder->setBuilder(new TestingConstraintBuilder(), 'testing')
            ->from('testing')
            ->build();
        
        // The constraint is only "not required field"
        $this->assertTrue(
            (new Validator($params[AConstraintBuilder::CSTRT]))
                ->validate('')
        );
    }

}
