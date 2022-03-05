<?php

namespace Fastwf\Tests\Parsing;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Parsing\WeekParser;
use Fastwf\Form\Utils\DateTimeUtil;

class WeekParserTest extends TestCase
{

    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\WeekParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testStringify()
    {
        $parser = new WeekParser();
        
        $this->assertEquals(
            '2021-W01',
            $parser->strigify(DateTimeUtil::getWeek('2021-W01'), null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\WeekParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParse()
    {
        $parser = new WeekParser();

        $this->assertEquals(
            DateTimeUtil::getWeek('2021-W01'),
            $parser->parse('2021-W01', null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\WeekParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseNull()
    {
        $parser = new WeekParser();

        $this->assertEquals(
            null,
            $parser->parse(null, null)
        );
    }
    
    /**
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\WeekParser
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testParseBadFormat()
    {
        $parser = new WeekParser();

        $this->assertEquals(
            null,
            $parser->parse("test", null)
        );
    }

}
