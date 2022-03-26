<?php

namespace Fastwf\Tests\Entity\Containers;

use Fastwf\Constraint\Data\Violation;
use Fastwf\Constraint\Data\ViolationConstraint;
use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Form\Entity\Containers\FormGroup;

class FormGroupTest extends TestCase
{

    /// Tests AFormGroup

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input  
     */
    public function testGetIterator()
    {
        $group = new FormGroup([
            'controls' => [
                new Input(['type' => 'text', 'name' => 'username']),
                new Input(['type' => 'email', 'name' => 'email']),
            ],
        ]);

        // Test the class
        $this->assertInstanceOf(\ArrayIterator::class, $group->getIterator());

        // Test iteration using foreach
        foreach ($group as $control) {
            $this->assertInstanceOf('Fastwf\Form\Entity\Control', $control);
        }
    }

    /// Tests FormGroup

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     */
    public function testGetTag()
    {
        $container = new FormGroup(['controls' => []]);

        $this->assertNull($container->getTag());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     */
    public function testGetContainerType()
    {
        $container = new FormGroup(['controls' => []]);

        $this->assertEquals('object', $container->getContainerType());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     */
    public function testFindControlFail()
    {
        $container = new FormGroup(['controls' => []]);

        $this->assertNull($container->findControl('test'));
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Parsing\StringParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetData()
    {
        $container = new FormGroup(['controls' => [
            new Input(['name' => 'birthday', 'type' => 'date', 'value' => '2021-01-01']),
            new Input(['name' => 'username', 'type' => 'text', 'value' => 'user']),
            new Input(['name' => 'wallets', 'type' => 'number', 'value' => '2']),
        ]]);

        $this->assertEquals(
            [
                'birthday' => new \DateTime('2021-01-01 00:00:00.000'),
                'username' => 'user',
                'wallets' => 2,
            ],
            $container->getData(),
        );
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Parsing\RadioParser
     */
    public function testGetDataRadio()
    {
        $container = new FormGroup(['controls' => [
            new Radio(['name' => 'fruit', 'attributes' => ['value' => 'apple']]),
            new Radio(['name' => 'fruit', 'attributes' => ['value' => 'banana']]),
            new Radio(['name' => 'fruit', 'attributes' => ['value' => 'raspberry']]),
        ]]);

        $container->setValue(['fruit' => 'raspberry']);

        $this->assertEquals(
            [
                'fruit' => 'raspberry',
            ],
            $container->getData(),
        );
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetSetControl()
    {
        $container = new FormGroup(['controls' => []]);

        $container->setControls([
            new Input(['name' => 'username', 'type' => 'text', 'value' => 'user']),
        ]);

        $this->assertEquals(1, \count($container->getControls()));
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testAddControl()
    {
        $container = new FormGroup(['controls' => []]);

        $container->addControl(new Input(['name' => 'username', 'type' => 'text', 'value' => 'user']));

        $this->assertEquals(1, \count($container->getControls()));
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testSetControlAt()
    {
        $container = new FormGroup(['controls' => []]);

        $container->addControl(new Input(['name' => 'username', 'type' => 'text', 'value' => 'user']));
        $container->setControlAt(0, new Input(['name' => 'email', 'type' => 'email']));

        $this->assertEquals('email', $container->getControlAt(0)->getName());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testSetValue()
    {
        $container = new FormGroup(['controls' => [
            new Input(['name' => 'username', 'type' => 'text', 'value' => 'user']),
            new Input(['name' => 'email', 'type' => 'email', 'value' => 'mail@test.com']),
        ]]);

        $container->setValue([
            'username' => 'username'
        ]);

        $this->assertEquals(
            [
                'username' => 'username',
                'email' => null,
            ],
            $container->getValue()
        );
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testSetViolations()
    {
        $container = new FormGroup(['controls' => [
            new Input(['name' => 'username', 'type' => 'text']),
            new Input(['name' => 'email', 'type' => 'email'])
        ]]);

        // Set the violation only on username
        $container->setViolation(new Violation(
            null,
            [],
            [
                'username' => new ViolationConstraint('test', []),
            ]
        ));

        $this->assertNotNull($container->getControlAt(0)->getViolation());
        $this->assertNull($container->getControlAt(1)->getViolation());

        // Set the violation only on email
        $container->setViolation(new Violation(
            null,
            [],
            [
                'email' => new ViolationConstraint('test', []),
            ]
        ));

        $this->assertNull($container->getControlAt(0)->getViolation());
        $this->assertNotNull($container->getControlAt(1)->getViolation());
    }
}
