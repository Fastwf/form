<?php

namespace Fastwf\Tests\Entity\Containers;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Form\Entity\Containers\RadioGroup;
use Fastwf\Constraint\Constraints\String\Enum;

class RadioGroupTest extends TestCase
{

    /// Test EntityGroup

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testSetGetConstraint()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'female']]),
            ],
        ]);

        $group->setConstraint(new Enum(['male', 'female']));

        $this->assertTrue(
            (new Validator($group->getConstraint()))->validate('male'),
        );
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testSetGetValidation()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'female']]),
            ],
            'constraint' => new Enum(['male', 'female']),
        ]);

        $this->assertNull($group->getViolation());

        $validator = new Validator($group->getConstraint());
        $validator->validate('invalid');

        $group->setViolation($validator->getViolations());

        $this->assertNotNull($group->getViolation());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testSetGetHelp()
    {
        $initial = 'Initial help';
        $help = 'This is radio group help message';

        $group = new RadioGroup([
            'name' => 'radio',
            'help' => $initial,
            'controls' => [],
        ]);

        $this->assertEquals($initial, $group->getHelp());
        
        $group->setHelp($help);

        $this->assertEquals($help, $group->getHelp());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetContainerType()
    {
        $group = new RadioGroup(['controls' => []]);

        $this->assertEquals('widget', $group->getContainerType());
    }

    /// Test RadioGroup

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testSetValue()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'female']]),
            ],
        ]);

        $this->assertFalse($group->getControlAt(0)->isChecked());

        $group->setValue('male');

        $this->assertTrue($group->getControlAt(0)->isChecked());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetValue()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'checked' => true, 'attributes' => ['value' => 'female']]),
            ],
        ]);

        $this->assertEquals('female', $group->getValue());
    }

    /**
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\EntityGroup
     * @covers Fastwf\Form\Entity\Containers\RadioGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\CheckableInput
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\Radio
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetDataNull()
    {
        $group = new RadioGroup([
            'name' => 'radio',
            'controls' => [
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'male']]),
                new Radio(['name' => 'radio', 'attributes' => ['value' => 'female']]),
            ],
        ]);

        $this->assertNull($group->getData());
    }

}
