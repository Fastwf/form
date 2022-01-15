<?php

namespace Fastwf\Tests\Build;

use PHPUnit\Framework\TestCase;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Form\Exceptions\KeyError;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Build\ConstraintBuilder;

class ConstraintBuilderTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\ConstraintBuilder
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
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Constraints\Date\DateField
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testBuildNumericConstraint()
    {
        $assert = [
            'min' => '2022-01-01',
            'max' => '2022-01-31',
            'step' => 2,
            'nullable' => true,
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
     */
    public function testRegisterUnregister()
    {
        $this->expectException(KeyError::class);

        ConstraintBuilder::getDefault()
            ->register('ssh-key', function (...$_) {
                // no constraints
                return null;
            })
            ->unregister('ssh-key')
            ->from('textarea', null)
            ->add('ssh-key', null, []);
    }

    /**
     * @covers Fastwf\Form\Build\ConstraintBuilder
     */
    public function testRegisterError()
    {
        $this->expectException(ValueError::class);

        ConstraintBuilder::getDefault()
            ->register('ssh-key', 'not callable value');
    }

}
