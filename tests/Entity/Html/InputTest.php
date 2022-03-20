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
     * @covers Fastwf\Form\Parsing\StringParser
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
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
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
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
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
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
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
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateTimeParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDataDateTime()
    {
        $control = new Input(['type' => 'datetime-local', 'value' => "2021-01-01T12:00"]);
        $this->assertEquals(strtotime("2021-01-01 12:00"), $control->getData()->getTimestamp());

        $control = new Input(['type' => 'datetime-local', 'value' => "01/01/2021 12h30"]);
        $this->assertNull($control->getData());

        $control = new Input(['type' => 'datetime-local']);
        $this->assertNull($control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\MonthParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDataMonth()
    {
        $control = new Input(['type' => 'month', 'value' => "2021-01"]);
        $this->assertEquals(strtotime("2021-01-01 00:00:00"), $control->getData()->getTimestamp());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\TimeParser
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testGetDataTime()
    {
        $control = new Input(['type' => 'time', 'value' => "10:00"]);
        $this->assertEquals(new \DateInterval("PT10H"), $control->getData());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\WeekParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDataWeek()
    {
        $control = new Input(['type' => 'week', 'value' => "2021-W10"]);
        $this->assertEquals(strtotime("2021-03-08 00:00:00"), $control->getData()->getTimestamp());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Parsing\DateTimeParser
     * @covers Fastwf\Form\Parsing\MonthParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Parsing\StringParser
     * @covers Fastwf\Form\Parsing\TimeParser
     * @covers Fastwf\Form\Parsing\WeekParser
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetSValue()
    {
        $controls = [
            new Input(['type' => 'text']),
            new Input(['type' => 'date', 'value' => new \DateTime("2022-03-05")]),
            new Input(['type' => 'datetime-local', 'value' => new \DateTime("2022-03-05 12:30:15.123")]),
            new Input(['type' => 'month', 'value' => new \DateTime("2022-03-01 00:00:00.000")]),
            new Input(['type' => 'range', 'value' => 3.14]),
            new Input(['type' => 'time', 'value' => new \DateInterval("PT10H")]),
            new Input(['type' => 'week', 'value' => new \DateTime("2021-03-08 00:00:00")])
        ];

        $this->assertSame(
            ['', '2022-03-05', '2022-03-05T12:30:15.123', '2022-03', '3.14', '10:00', '2021-W10'],
            \array_map(function ($control) { return $control->getSValue(); }, $controls)
        );
    }

}
