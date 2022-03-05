<?php

namespace Fastwf\Tests\Parsing;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Parsing\TimeParser;
use Fastwf\Form\Utils\DateIntervalUtil;

class TimeParserTest extends TestCase
{

    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\TimeParser
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testStringify()
    {
        $parser = new TimeParser();
        
        $this->assertEquals(
            "01:00",
            $parser->strigify(DateIntervalUtil::getTime("01:00"), null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\TimeParser
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testParse()
    {
        $parser = new TimeParser();

        $this->assertEquals(
            DateIntervalUtil::getTime("01:00"),
            $parser->parse("01:00", null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\TimeParser
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testParseNull()
    {
        $parser = new TimeParser();

        $this->assertEquals(
            null,
            $parser->parse(null, null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\TimeParser
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testParseBadFormat()
    {
        $parser = new TimeParser();

        $this->assertEquals(
            null,
            $parser->parse("test", null)
        );
    }

}
