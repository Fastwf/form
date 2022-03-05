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
     * @covers Fastwf\Form\Build\Constraints\AConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\DateConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\DateTimeConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\MonthConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\NumberConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\TimeConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\WeekConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\EmailConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\StringConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\InputFile
     * @covers Fastwf\Form\Parsing\StringParser
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testNewGroupBuilder()
    {
        $form = FormBuilder::new('test')
            ->newGroupBuilder('address')
                ->setAttributes(['class' => 'app-formgroup app-form--address'])
                ->addInput('lane')
                ->addInput('zip_code', 'number')
                ->addInput('city')
                ->addInputFile('map', [])
                ->buildInParent()
            ->build();
        
        $this->assertEquals(
            [
                'address' => [
                    'lane' => null,
                    'zip_code' => null,
                    'city' => null,
                    'map' => null,
                ]
            ],
            $form->getData(),
        );
    }

}
