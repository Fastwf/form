<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Textarea;

class TextareaTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Textarea
     */
    public function testGetTag()
    {
        $html = new Textarea();

        $this->assertEquals('textarea', $html->getTag());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Textarea
     */
    public function testGetData()
    {
        $control = new Textarea(['value' => 'Hello world!']);

        $this->assertEquals('Hello world!', $control->getData());
    }

}
