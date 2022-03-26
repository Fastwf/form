<?php

namespace Fastwf\Tests\Constraints\Date;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\Date\MonthField;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class MonthFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Date\MonthField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testValid()
    {
        $constraint = new MonthField();
        $node = Node::from(['value' => '2021-01']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(
            \strtotime('2021-01-01'),
            $node->get()->getTimestamp()
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\MonthField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testValidWithDateTime()
    {
        $dateTime = new \DateTime('2021-01-01');

        $constraint = new MonthField();
        $node = Node::from(['value' => $dateTime]);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertSame(
            $dateTime,
            $node->get()
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\MonthField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testInvalid()
    {
        $constraint = new MonthField();
        $node = Node::from(['value' => '2021-00']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}
