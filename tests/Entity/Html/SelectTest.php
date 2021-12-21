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
     */
    public function testGetTag()
    {
        $html = new Select(['options' => []]);

        $this->assertEquals('select', $html->getTag());
    }

}
