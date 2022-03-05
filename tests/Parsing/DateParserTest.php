<?php

namespace Fastwf\Tests\Parsing;

use Fastwf\Form\Parsing\DateParser;
use PHPUnit\Framework\TestCase;

class DateParserTest extends TestCase
{

    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testStringify()
    {
        $parser = new DateParser();
        
        $this->assertEquals(
            "2022-03-01",
            $parser->strigify(new \DateTime("2022-03-01 00:00:00.000"), null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParse()
    {
        $parser = new DateParser();

        $this->assertEquals(
            new \DateTime("2022-03-01 00:00:00.000"),
            $parser->parse("2022-03-01", null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseNull()
    {
        $parser = new DateParser();

        $this->assertEquals(
            null,
            $parser->parse(null, null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseBadFormat()
    {
        $parser = new DateParser();

        $this->assertEquals(
            null,
            $parser->parse("test", null)
        );
    }
    
    /// Test AParser

    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testStringifyString()
    {
        $parser = new DateParser();

        $date = "2022-03-01";
        $this->assertEquals(
            $date,
            $parser->strigify("2022-03-01", null)
        );
    }

    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testStringifyNull()
    {
        $parser = new DateParser();

        $this->assertEquals(
            "",
            $parser->strigify(null, null)
        );
    }

    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseDateTime()
    {
        $parser = new DateParser();

        $date = new \DateTime("2022-03-01 00:00:00.000");
        $this->assertEquals(
            $date,
            $parser->parse($date, null)
        );
    }

}
