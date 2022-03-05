<?php

namespace Fastwf\Tests\Parsing;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Parsing\MonthParser;

class MonthParserTest extends TestCase
{

    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\MonthParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testStringify()
    {
        $parser = new MonthParser();
        
        $this->assertEquals(
            "2021-01",
            $parser->strigify(new \DateTime("2021-01-01 00:00:00.000"), null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\MonthParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParse()
    {
        $parser = new MonthParser();

        $this->assertEquals(
            new \DateTime("2021-01-01 00:00:00.000"),
            $parser->parse("2021-01", null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\MonthParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseNull()
    {
        $parser = new MonthParser();

        $this->assertEquals(
            null,
            $parser->parse(null, null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\MonthParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseBadFormat()
    {
        $parser = new MonthParser();

        $this->assertEquals(
            null,
            $parser->parse("test", null)
        );
    }

}
