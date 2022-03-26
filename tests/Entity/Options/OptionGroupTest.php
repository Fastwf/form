<?php

namespace Fastwf\Tests\Entity\Options;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Options\Option;
use Fastwf\Form\Entity\Options\OptionGroup;

class OptionGroupTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\OptionGroup
     */
    public function testGetTag()
    {
        $html = new OptionGroup(['options' => []]);

        $this->assertEquals('optgroup', $html->getTag());
    }

    /**
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     * @covers Fastwf\Form\Entity\Options\OptionGroup
     */
    public function testSetGetOptions()
    {
        $optionGroup = new OptionGroup(['options' => []]);

        $this->assertEquals([], $optionGroup->getOptions());

        $options = [new Option(['value' => 'test'])];
        $optionGroup->setOptions($options);

        $this->assertEquals($options, $optionGroup->getOptions());
    }

}