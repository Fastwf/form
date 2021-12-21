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
     * @covers Fastwf\Form\Entity\Containers\FormContainer
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Html\Input  
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testSetValue()
    {
        $array = new FormArray(['controls' => []]);

        $array->setControls([
            new Input(['type' => 'text', 'name' => 'username']),
            new Input(['type' => 'text', 'name' => 'username']),
            new Input(['type' => 'text', 'name' => 'username']),
        ]);

        $array->setValue([
            'first',
            'second',
            'third'
        ]);

        $this->assertEquals('first', $array->getControlAt(0));
        $this->assertEquals('second', $array->getControlAt(1));
        $this->assertEquals('third', $array->getControlAt(2));
    }

    /**  
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormContainer
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetTag()
    {
        $container = new FormArray(['controls' => []]);

        $this->assertNull($container->getTag());
    }

    /**  
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormContainer
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetContainerType()
    {
        $container = new FormArray(['controls' => []]);

        $this->assertEquals('array', $container->getContainerType());
    }

}
