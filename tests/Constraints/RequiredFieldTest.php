<?php

namespace Fastwf\Tests\Constraints;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\RequiredField;
use Fastwf\Tests\Constraints\ConstraintTestCase;
use Fastwf\Constraint\Constraints\Type\DoubleType;
use Fastwf\Constraint\Constraints\Type\StringType;

class RequiredFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testValidOptional()
    {
        $constraint = new RequiredField(false);

        $this->assertNull($constraint->validate(
            new Node(),
            $this->context,
        ));
    }

    /**
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testValidExistsOnlyRequired()
    {
        $constraint = new RequiredField(true);
        $node = Node::from(['value' => 'test']);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testValidExistsWithConstraint()
    {
        $subConstraint = new StringType();
        $constraint = new RequiredField(true, $subConstraint);
        $node = Node::from(['value' => 'test']);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testInvalidNullOnlyRequired()
    {
        $constraint = new RequiredField(true);
        $node = Node::from(['value' => null]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testInvalidEmptyOnlyRequired()
    {
        $constraint = new RequiredField(true);
        $node = Node::from(['value' => '']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\RequiredField
     */
    public function testInvalidNotEmpty()
    {
        $subConstraint = new DoubleType();
        $constraint = new RequiredField(true, $subConstraint);
        $node = Node::from(['value' => 'test']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}
