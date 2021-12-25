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
        $array = new FormArray(['control' => []]);

        $array->setControl([
            new Input(['type' => 'text', 'name' => 'username']),
        ]);

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
        $container = new FormArray(['control' => []]);

        $this->assertNull($container->getTag());
    }

    /**  
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetContainerType()
    {
        $container = new FormArray(['control' => []]);

        $this->assertEquals('array', $container->getContainerType());
    }

}
