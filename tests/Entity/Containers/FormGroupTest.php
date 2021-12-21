<?php

namespace Fastwf\Tests\Entity\Containers;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Form\Entity\Containers\FormGroup;

class FormGroupTest extends TestCase
{

    /**  
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormContainer
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetContainerType()
    {
        $container = new FormGroup(['controls' => []]);

        $this->assertEquals('object', $container->getContainerType());
    }

    /**  
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormContainer
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testFindControlFail()
    {
        $container = new FormGroup(['controls' => []]);

        $this->assertNull($container->findControl('test'));
    }

}
