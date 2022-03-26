<?php

namespace Fastwf\Tests\Constraints\Time;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\Time\TimeField;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class TimeFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Time\TimeField
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testValid()
    {
        $constraint = new TimeField();
        $node = Node::from(['value' => '12:00']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(
            new \DateInterval("PT12H"),
            $node->get(),
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Time\TimeField
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testValidWithDateInterval()
    {
        $dateInterval = new \DateInterval("PT12H");

        $constraint = new TimeField();
        $node = Node::from(['value' => $dateInterval]);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertSame(
            $dateInterval,
            $node->get(),
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Time\TimeField
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testInvalid()
    {
        $constraint = new TimeField();
        $node = Node::from(['value' => '12-00']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Time\TimeField
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testInvalidWithNull()
    {
        $constraint = new TimeField();
        $node = Node::from(['value' => null]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}
