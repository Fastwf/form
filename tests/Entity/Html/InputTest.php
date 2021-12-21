<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Input;

class InputTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetTag()
    {
        $html = new Input(['type' => 'text']);

        $this->assertEquals('input', $html->getTag());
    }

}
