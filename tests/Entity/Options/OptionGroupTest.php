<?php

namespace Fastwf\Tests\Entity\Options;

use PHPUnit\Framework\TestCase;
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

}