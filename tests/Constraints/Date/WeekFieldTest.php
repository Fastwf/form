<?php

namespace Fastwf\Tests\Constraints\Date;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\Date\WeekField;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class WeekFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Date\WeekField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testValid()
    {
        $constraint = new WeekField();
        $node = Node::from(['value' => '2021-W01']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(
            \strtotime('2021-01-04'),
            $node->get()->getTimestamp()
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\WeekField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testInvalid()
    {
        $constraint = new WeekField();
        $node = Node::from(['value' => 'test']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}