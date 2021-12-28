<?php

namespace Fastwf\Tests\Entity\Containers;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Form\Entity\Containers\FormArray;

class FormArrayTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Html\Input  
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testSetValue()
    {
        $array = new FormArray(['control' => null]);

        $array->setControl(
            new Input(['type' => 'text', 'name' => 'username']),
        );

        $data = [
            'first',
            'second',
            'third'
        ];
        $array->setValue($data);

        $this->assertEquals($data, $array->getValue());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetTag()
    {
        $container = new FormArray(['control' => null]);

        $this->assertNull($container->getTag());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetContainerType()
    {
        $container = new FormArray(['control' => null]);

        $this->assertEquals('array', $container->getContainerType());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Html\Input  
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetData()
    {
        $container = new FormArray([
            'value' => ['1', '2', '3'],
            'control' => new Input(['type' => 'number', 'name' => 'username']),
        ]);

        $this->assertEquals(
            [1, 2, 3],
            $container->getData(),
        );
    }

}
