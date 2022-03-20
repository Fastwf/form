<?php

namespace Fastwf\Tests\Parsing;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Form\Parsing\NumberParser;

class NumberParserTest extends TestCase
{

    /**
     * @var Input
     */
    private $input;

    protected function setUp(): void
    {
        $this->input = new Input([
            'type' => 'number',
        ]);
    }

    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testStringifyInteger()
    {
        $parser = new NumberParser();
        
        $this->assertEquals(
            "1",
            $parser->strigify(1, null)
        );
    }

    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testStringifyDouble()
    {
        $parser = new NumberParser();
        
        $this->assertEquals(
            "3.14",
            $parser->strigify(3.14, null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testParseInteger()
    {
        $parser = new NumberParser();

        $this->input->setAttributes(['step' => '1']);

        $this->assertSame(
            1,
            $parser->parse("1", $this->input)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testParseDouble()
    {
        $parser = new NumberParser();

        $this->input->setAttributes(['step' => '0.1']);

        $this->assertSame(
            10.5,
            $parser->parse("10.5", $this->input)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testParseNull()
    {
        $parser = new NumberParser();

        $this->assertEquals(
            null,
            $parser->parse(null, $this->input)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\NumberParser
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     */
    public function testParseBadFormat()
    {
        $parser = new NumberParser();

        $this->assertSame(
            null,
            $parser->parse("test", $this->input)
        );
    }

}
