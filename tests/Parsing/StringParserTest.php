<?php

namespace Fastwf\Tests\Parsing;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Parsing\StringParser;

class StringParserTest extends TestCase
{

    private const VALUE = 'value';

    /**
     * @covers Fastwf\Form\Parsing\StringParser
     */
    public function testStrigify()
    {
        $parser = new StringParser();

        $this->assertEquals(
            self::VALUE,
            $parser->strigify(self::VALUE, null)
        );
    }

    /**
     * @covers Fastwf\Form\Parsing\StringParser
     */
    public function testParse()
    {
        $parser = new StringParser();

        $this->assertEquals(
            self::VALUE,
            $parser->parse(self::VALUE, null)
        );
    }

}
