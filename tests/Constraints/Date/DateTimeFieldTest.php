<?php

namespace Fastwf\Tests\Constraints\Date;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\Date\DateTimeField;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class DateTimeFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Date\DateTimeField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testValid()
    {
        $constraint = new DateTimeField();
        $node = Node::from(['value' => '2021-01-20T12:00']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(
            \strtotime('2021-01-20 12:00'),
            $node->get()->getTimestamp()
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\DateTimeField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testValidWithDateTime()
    {
        $dateTime = new \DateTime('2021-01-20 12:00');

        $constraint = new DateTimeField();
        $node = Node::from(['value' => $dateTime]);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertSame(
            $dateTime,
            $node->get()
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\DateTimeField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testInvalid()
    {
        $constraint = new DateTimeField();
        $node = Node::from(['value' => 'test']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}