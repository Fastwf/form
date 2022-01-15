<?php

namespace Fastwf\Tests\Constraints\Number;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Constraints\Date\StepDateTime;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class StepDateTimeTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     */
    public function testValidStep()
    {
        $constraint = new StepDateTime(3600);
        $node = Node::from(['value' => new \DateTime('2022-01-01 12:00:00.000')]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     */
    public function testValidFrom()
    {
        $constraint = new StepDateTime(3600, new \DateTime('2022-01-01 12:30:00.000'));
        $node = Node::from(['value' => new \DateTime('2022-01-01 20:30:00.000')]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     */
    public function testInvalid()
    {
        $constraint = new StepDateTime(3600, new \DateTime('2022-01-01 12:30:00.000'));
        $node = Node::from(['value' => new \DateTime('2022-01-01 20:00:00.000')]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     */
    public function testConstructorException()
    {
        $this->expectException(ValueError::class);

        $constraint = new StepDateTime(0);
    }

}
