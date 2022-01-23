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
    public function testInvalid()
    {
        $constraint = new TimeField();
        $node = Node::from(['value' => '12-00']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}