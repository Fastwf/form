<?php

namespace Fastwf\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Build\FormBuilder;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Form\Entity\Containers\FormArray;
use Fastwf\Form\Entity\Containers\FormGroup;
use Fastwf\Form\Entity\Control;

class ControlTest extends TestCase {

    const FORM_ID = 'form_id';
    const FIRST_NAME = 'first_name';

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetFullNameBasic() {
        $control = new Input(['name' => self::FIRST_NAME, 'type' => 'text']);

        $this->assertEquals(self::FIRST_NAME, $control->getFullName());
    }

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetFullNameForm() {
        $form = FormBuilder::new(self::FORM_ID, 'test')
            ->addInput(self::FIRST_NAME)
            ->build();
        
        $this->assertEquals(self::FIRST_NAME, $form->getControlAt(0)->getFullName());

        $form->setName('parent');
        $this->assertEquals("parent[" . self::FIRST_NAME . "]", $form->getControlAt(0)->getFullName());
    }

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\Groups\GroupBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetFullNameInsideGroup() {
        $form = FormBuilder::new(self::FORM_ID, 'test')
            ->newGroupBuilder('child')
            ->addInput(self::FIRST_NAME)
            ->buildInParent()
            ->build();

        /** @var FormGroup */
        $group = $form->getControlAt(0);
        $this->assertEquals('child[' . self::FIRST_NAME . ']', $group->getControlAt(0)->getFullName());

        $form->setName('parent');
        $this->assertEquals("parent[child][" . self::FIRST_NAME . "]", $group->getControlAt(0)->getFullName());
    }

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\Groups\GroupBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetFullNameGroupInsideGroup() {
        $form = FormBuilder::new(self::FORM_ID, 'test')
            ->newGroupBuilder('child')
                ->newGroupBuilder('sub_child')
                    ->addInput(self::FIRST_NAME)
                    ->buildInParent()
                ->buildInParent()
            ->build();

        /** @var FormGroup */
        $child = $form->getControlAt(0);
        /** @var FormGroup */
        $group = $child->getControlAt(0);
        $this->assertEquals('child[sub_child][' . self::FIRST_NAME . ']', $group->getControlAt(0)->getFullName());

        $form->setName('parent');
        $this->assertEquals("parent[child][sub_child][" . self::FIRST_NAME . "]", $group->getControlAt(0)->getFullName());
    }

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\Groups\GroupBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetFullNameGroupInsideUnamedGroup() {
        $form = FormBuilder::new(self::FORM_ID, 'test')
            ->newGroupBuilder(null)
                ->newGroupBuilder('child')
                    ->addInput(self::FIRST_NAME)
                    ->buildInParent()
                ->buildInParent()
            ->build();

        /** @var FormGroup */
        $child = $form->getControlAt(0);
        /** @var FormGroup */
        $group = $child->getControlAt(0);
        $this->assertEquals('child[' . self::FIRST_NAME . ']', $group->getControlAt(0)->getFullName());

        $form->setName('parent');
        $this->assertEquals("parent[child][" . self::FIRST_NAME . "]", $group->getControlAt(0)->getFullName());
    }

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\Groups\ArrayBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Containers\FormArrayIterator
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetFullNameInsideArray() {
        $form = FormBuilder::new(self::FORM_ID, 'test')
            ->newArrayBuilder('child')
            ->ofInput()
            ->buildInParent()
            ->build();

        /** @var FormArray */
        $group = $form->getControlAt(0);

        $iter = $group->getIterator();
        $iter->rewind();

        /** @var Control */
        $control = $iter->current();

        $this->assertEquals('child[0]', $control->getFullName());

        $form->setName('parent');
        $this->assertEquals("parent[child][0]", $control->getFullName());
    }

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
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
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Build\Groups\ArrayBuilder
     * @covers Fastwf\Form\Build\Groups\GroupBuilder
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormArray
     * @covers Fastwf\Form\Entity\Containers\FormArrayIterator
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetFullNameGroupInsideArray() {
        $form = FormBuilder::new(self::FORM_ID, 'test')
            ->newArrayBuilder('positions')
                ->ofGroup()
                    ->addInput('x', 'number')
                    ->addInput('y', 'number')
                    ->buildInParent()
                ->buildInParent()
            ->build();

        /** @var FormArray */
        $group = $form->getControlAt(0);

        $iter = $group->getIterator();
        $iter->rewind();

        /** @var FormGroup */
        $group = $iter->current();
        $control = $group->getControlAt(0);

        $this->assertEquals('positions[0][x]', $control->getFullName());

        $form->setName('graph');
        $this->assertEquals("graph[positions][0][x]", $control->getFullName());
    }

}
