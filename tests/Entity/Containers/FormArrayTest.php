<?php

namespace Fastwf\Tests\Entity\Containers;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Api\Exceptions\KeyError;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Constraint\Data\Violation;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Constraint\Constraints\Number\Minimum;
use Fastwf\Form\Constraints\IntegerField;
use Fastwf\Form\Entity\Containers\FormArray;
use Fastwf\Constraint\Data\ViolationConstraint;

class FormArrayTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormArray
     */
    public function testKeyErrorMissingName()
    {
        $this->expectException(KeyError::class);

        new FormArray();
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Html\Input  
     */
    public function testSetValue()
    {
        $array = new FormArray(['name' => 'array', 'control' => null]);

        $array->setControl(
            new Input(['type' => 'text', 'name' => 'username']),
        );

        $data = [
            'first',
            'second',
            'third'
        ];
        $array->setValue($data);

        $this->assertEquals($data, $array->getValue());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormArray
     */
    public function testGetTag()
    {
        $container = new FormArray(['name' => 'array', 'control' => null]);

        $this->assertNull($container->getTag());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormArray
     */
    public function testGetContainerType()
    {
        $container = new FormArray(['name' => 'array', 'control' => null]);

        $this->assertEquals('array', $container->getContainerType());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     */
    public function testGetData()
    {
        $container = new FormArray([
            'name' => 'array',
            'value' => ['1', '2', '3'],
            'control' => new Input(['type' => 'number', 'name' => 'username']),
        ]);

        $this->assertEquals(
            [1, 2, 3],
            $container->getData(),
        );
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Entity\Containers\FormArrayIterator
     */
    public function testGetIterator()
    {
        $container = new FormArray([
            'name' => 'array',
            'value' => ['1', '2', '3'],
            'control' => new Input(['type' => 'number', 'name' => 'position']),
        ]);

        // Iterate on form array and check if controls hold the right value
        foreach ($container as $index => $control)
        {
            $this->assertEquals($index + 1, $control->getData());
        }
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Entity\Containers\FormArrayIterator
     */
    public function testSetViolation()
    {
        // Test set violation methods and the iteration on the form array for child propagation
        $container = new FormArray([
            'name' => 'array',
            'value' => null,
            'control' => new Input(['type' => 'number', 'name' => 'position']),
        ]);

        $container->setViolation(new Violation(
            null,
            [],
            [
                0 => new ViolationConstraint('test', [])
            ]
        ));

        $iterator = $container->getIterator();
        $iterator->rewind();

        $this->assertNotNull($iterator->current()->getViolation());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Constraints\IntegerField
     */
    public function testGetConstraint()
    {
        // Test that the constraint is an array type of integer field greather than 0
        $constraint = new Chain(true, new IntegerField(), new Minimum(0));

        $container = new FormArray([
            'name' => 'array',
            'value' => null,
            'control' => new Input(['type' => 'number', 'name' => 'position', 'constraint' => $constraint]),
        ]);

        $validator = new Validator($container->getConstraint());

        $this->assertTrue($validator->validate([1, 2, 3]));
        $this->assertFalse($validator->validate([1, -2, 3]));
    }

}
