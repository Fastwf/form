<?php

namespace Fastwf\Tests\Constraints\Date;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\Date\DateField;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class DateFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Date\DateField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testValid()
    {
        $constraint = new DateField();
        $node = Node::from(['value' => '2021-01-20']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(
            \strtotime('2021-01-20'),
            $node->get()->getTimestamp()
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\DateField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testValidWithDateTime()
    {
        $dateTime = new \DateTime('2021-01-20');

        $constraint = new DateField();
        $node = Node::from(['value' => $dateTime]);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertSame(
            $dateTime,
            $node->get()
        );
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\DateField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testInvalid()
    {
        $constraint = new DateField();
        $node = Node::from(['value' => 'test']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}