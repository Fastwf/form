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

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetDataColor()
    {
        $control = new Input(['type' => 'color', 'value' => "#000"]);
        $this->assertEquals("#000", $control->getData());

        $control = new Input(['type' => 'color']);
        $this->assertNull($control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetDataNumber()
    {
        $control = new Input(['type' => 'number', 'value' => "10"]);
        $this->assertEquals(10, $control->getData());

        $control = new Input(['type' => 'number', 'value' => "3.14", "attributes" => ["step" => "invalid step"]]);
        $this->assertEquals(3, $control->getData());

        $control = new Input(['type' => 'number', 'value' => "3.14", "attributes" => ["step" => "0.01"]]);
        $this->assertEquals(3.14, $control->getData());

        $control = new Input(['type' => 'number']);
        $this->assertNull($control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetDataRange()
    {
        $control = new Input(['type' => 'range', 'value' => "10"]);
        $this->assertEquals(10, $control->getData());

        $control = new Input(['type' => 'range']);
        $this->assertNull($control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDataDate()
    {
        $control = new Input(['type' => 'date', 'value' => "2021-01-01"]);
        $this->assertEquals(strtotime("2021-01-01"), $control->getData()->getTimestamp());

        $control = new Input(['type' => 'date', 'value' => "01/01/2021"]);
        $this->assertNull($control->getData());

        $control = new Input(['type' => 'date']);
        $this->assertNull($control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDataDateTime()
    {
        $control = new Input(['type' => 'datetime-local', 'value' => "2021-01-01 12:00"]);
        $this->assertEquals(strtotime("2021-01-01 12:00"), $control->getData()->getTimestamp());

        $control = new Input(['type' => 'datetime-local', 'value' => "01/01/2021 12h30"]);
        $this->assertNull($control->getData());

        $control = new Input(['type' => 'datetime-local']);
        $this->assertNull($control->getData());
    }

}
