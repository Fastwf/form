<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Button;

class ButtonTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Button
     */
    public function testGetTag()
    {
        $html = new Button();

        $this->assertEquals('button', $html->getTag());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Button
     * @covers Fastwf\Form\Parsing\StringParser
     */
    public function testGetData()
    {
        $html = new Button(['value' => 'button']);

        $this->assertEquals('button', $html->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Button
     * @covers Fastwf\Form\Parsing\StringParser
     */
    public function testSetGetType()
    {
        $html = new Button();

        $this->assertEquals(Button::TYPE_SUBMIT, $html->getType());

        $html->setType(Button::TYPE_BUTTON);
        $this->assertEquals(Button::TYPE_BUTTON, $html->getType());
    }

}
