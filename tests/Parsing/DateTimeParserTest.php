<?php

namespace Fastwf\Tests\Parsing;

use Fastwf\Form\Parsing\DateTimeParser;
use PHPUnit\Framework\TestCase;

class DateTimeParserTest extends TestCase
{

    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateTimeParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testStringify()
    {
        $parser = new DateTimeParser();
        
        $this->assertEquals(
            "2022-03-01T12:35:36.543",
            $parser->strigify(new \DateTime("2022-03-01 12:35:36.543"), null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateTimeParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParse()
    {
        $parser = new DateTimeParser();

        $this->assertEquals(
            new \DateTime("2022-03-01 12:35:36.543"),
            $parser->parse("2022-03-01T12:35:36.543", null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateTimeParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseNull()
    {
        $parser = new DateTimeParser();

        $this->assertEquals(
            null,
            $parser->parse(null, null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateTimeParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseBadFormat()
    {
        $parser = new DateTimeParser();

        $this->assertEquals(
            null,
            $parser->parse("test", null)
        );
    }

}
