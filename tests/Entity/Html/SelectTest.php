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

        /** @var Option */
        $option0 = $options[0];
        /** @var Option */
        $option2 = $options[2];
        /** @var OptionGroup */
        $optionGroup1 = $options[1];
        /** @var OptionGroup */
        $optionGroup3 = $options[3];

        $this->assertTrue($option2->isSelected());
        $this->assertTrue($optionGroup1->getOptions()[2]->isSelected());
        $this->assertTrue($optionGroup3->getOptions()[0]->isSelected());

        $this->assertFalse($option0->isSelected());
        $this->assertFalse($optionGroup3->getOptions()[1]->isSelected());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
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

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testGetValue()
    {
        $select = new Select([
            'name' => 'select',
            'options' => [
                new Option(['value' => 'apple']),
                new Option(['value' => 'banana']),
                new Option(['value' => 'orange']),
            ]
        ]);

        $this->assertEquals([], $select->getValue());

        $select->setValue('apple');
        $this->assertEquals(['apple'], $select->getValue());

        $select->setMultiple(true);
        $select->setValue(['apple', 'banana']);
        $this->assertEquals(['apple', 'banana'], $select->getValue());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testSetIsMultiple()
    {
        $select = new Select([
            'name' => 'select',
            'options' => [
                new Option(['value' => 'apple']),
                new Option(['value' => 'banana']),
                new Option(['value' => 'orange']),
            ]
        ]);

        $this->assertFalse($select->isMultiple());

        $select->setMultiple(true);
        $this->assertTrue($select->isMultiple());

        $select->setMultiple(false);
        $this->assertFalse($select->isMultiple());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Select
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testSetGetOptions()
    {
        $options = [
            new Option(['value' => 'apple']),
            new Option(['value' => 'banana']),
            new Option(['value' => 'orange']),
        ];

        $select = new Select([
            'name' => 'select',
            'options' => $options
        ]);

        $this->assertSame(
            $options,
            $select->getOptions(),
        );

        $select->setValue('apple');
        $select->setOptions([
            new Option(['value' => 'apple']),
            new Option(['value' => 'banana']),
            new Option(['value' => 'orange']),
        ]);

        $expectedAppleOption = new Option(['value' => 'apple']);
        $expectedAppleOption->setSelected(true);

        $this->assertEquals(
            [
                $expectedAppleOption,
                new Option(['value' => 'banana']),
                new Option(['value' => 'orange']),
            ],
            $select->getOptions(),
        );
    }

}
