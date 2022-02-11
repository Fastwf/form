<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Exceptions\KeyError;

class ArrayUtilTest extends TestCase
{

    private $array = [
        'key' => 'value'
    ];

    /**
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGet()
    {
        $this->assertEquals('value', ArrayUtil::get($this->array, 'key'));
    }

    /**
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetException()
    {
        $this->expectException(KeyError::class);
        
        ArrayUtil::get($this->array, 'not-found');
    }

    /**
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetSafe()
    {
        $this->assertEquals('value', ArrayUtil::getSafe($this->array, 'key'));
    }

    /**
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetSafeDefault()
    {
        $this->assertEquals('other', ArrayUtil::getSafe($this->array, 'not-found', 'other'));
    }

    /**
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testMergeAll()
    {
        $from = ['a' => 'A', 'b' => 'B'];
        $to = ['c' => 'C'];

        ArrayUtil::merge($from, $to);

        $this->assertEquals(
            ['a' => 'A', 'b' => 'B', 'c' => 'C'],
            $to,
        );
    }

    /**
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testMergeSubSet()
    {
        $from = ['a' => 'A', 'b' => 'B'];
        $to = ['c' => 'C'];

        ArrayUtil::merge($from, $to, ['b', 'd']);

        $this->assertEquals(
            ['b' => 'B', 'c' => 'C'],
            $to,
        );
    }

}
