<?php

namespace Fastwf\Tests\Build\Groups;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Build\FormBuilder;

class GroupBuilderTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Groups\GroupBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testNewGroupBuilder()
    {
        $form = FormBuilder::new('test')
            ->newGroupBuilder('address')
                ->setAttributes(['class' => 'app-formgroup app-form--address'])
                ->addInput('lane')
                ->addInput('zip_code', 'number')
                ->addInput('city')
                ->buildInParent()
            ->build();
        
        $this->assertEquals(
            [
                'address' => [
                    'lane' => null,
                    'zip_code' => null,
                    'city' => null,
                ]
            ],
            $form->getData(),
        );
    }

}
