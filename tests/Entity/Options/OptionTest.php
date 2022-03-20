<?php

namespace Fastwf\Tests\Entity\Options;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Options\Option;

class OptionTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Options\AOption
     * @covers Fastwf\Form\Entity\Options\Option
     */
    public function testGetTag()
    {
        $html = new Option(['value' => 'test']);

        $this->assertEquals('option', $html->getTag());
    }

}