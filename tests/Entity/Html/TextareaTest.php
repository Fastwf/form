<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Api\Exceptions\ValueError;
use Fastwf\Form\Entity\Html\Textarea;
use Fastwf\Form\Parsing\StringParser;

class TextareaTest extends TestCase
{

    /// Test Control

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Textarea
     */
    public function testSetGetLabel()
    {
        $textarea = new Textarea();
        $textarea->setLabel('label');
        $this->assertEquals('label', $textarea->getLabel());
    }

    /// Test FormControl

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Textarea
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\StringParser
     */
    public function testSetGetParser()
    {
        $textarea = new Textarea();
        $textarea->setParser(new StringParser());
        $this->assertInstanceOf(StringParser::class, $textarea->getParser());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Textarea
     */
    public function testSetParserValueError()
    {
        $this->expectException(ValueError::class);

        $textarea = new Textarea();
        $textarea->setParser(new \stdClass());
    }

    /// Test Textarea

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
