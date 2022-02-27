<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Select;
use Fastwf\Form\Entity\Options\Option;
use Fastwf\Form\Entity\Options\OptionGroup;

class SelectTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Entity\Options\OptionGroup
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testConstructorAndUpdateSelection()
    {
        $select = new Select([
            'value' => ['childB3', 'parentC', 'childD1'],
            'options' => [
                new Option(['value' => 'parentA']),
                new OptionGroup([
                    'label' => 'parentB',
                    'options' => [
                        new Option(['value' => 'childB1']),
                        new Option(['value' => 'childB2']),
                        new Option(['value' => 'childB3']),
                    ]
                ]),
                new Option(['value' => 'parentC']),
                new OptionGroup([
                    'label' => 'parentD',
                    'options' => [
                        new Option(['value' => 'childD1']),
                        new Option(['value' => 'childD2']),
                        new Option(['value' => 'childD3']),
                    ]
                ]),
            ]
        ]);
        $options = $select->getOptions();

        $this->assertTrue($options[2]->isSelected());
        $this->assertTrue($options[1]->getOptions()[2]->isSelected());
        $this->assertTrue($options[3]->getOptions()[0]->isSelected());

        $this->assertFalse($options[0]->isSelected());
        $this->assertFalse($options[3]->getOptions()[1]->isSelected());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testGetTag()
    {
        $html = new Select(['options' => []]);

        $this->assertEquals('select', $html->getTag());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testGetData()
    {
        $control = new Select([
            'options' => [
                new Option(['value' => 'apple']),
                new Option(['value' => 'banana']),
                new Option(['value' => 'orange']),
            ],
            'value' => ['apple', 'orange'],
            'multiple' => true,
        ]);

        $this->assertEquals(
            ['apple', 'orange'],
            $control->getData(),
        );

        $control->setMultiple(false);
        $control->setValue('apple');
        $this->assertEquals('apple', $control->getData());

        $control->setValue(null);
        $this->assertEquals(null, $control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testGetFullName()
    {
        $select = new Select([
            'name' => 'select',
            'options' => [
                new Option(['value' => 'apple']),
                new Option(['value' => 'banana']),
                new Option(['value' => 'orange']),
            ]
        ]);

        $this->assertEquals('select', $select->getFullName());

        $select->setMultiple(true);
        $this->assertEquals('select[]', $select->getFullName());
    }

}
