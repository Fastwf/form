<?php

namespace Fastwf\Tests\Entity\Containers;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Form\Entity\Containers\FormGroup;

class FormGroupTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testSetControlAt()
    {
        $container = new FormGroup(['controls' => []]);

        $container->addControl(new Input(['name' => 'username', 'type' => 'text', 'value' => 'user']));
        $container->setControlAt(0, new Input(['name' => 'email', 'type' => 'email']));

        $this->assertEquals('email', $container->getControlAt(0)->getName());
    }

}
