<?php

namespace Fastwf\Tests\Build\Factory;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Build\Factory\DateFactory;
use Fastwf\Form\Build\Factory\NumberFactory;
use Fastwf\Form\Exceptions\FactoryException;
use Fastwf\Form\Build\Factory\NumericFactory;
use Fastwf\Form\Build\Factory\DateTimeFactory;

class NumericFactoryTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     */
    public function testOfNumber()
    {
        $this->assertTrue(NumericFactory::of('input', 'number', 'any') instanceof NumberFactory);
        $this->assertTrue(NumericFactory::of('input', 'range', 'any') instanceof NumberFactory);
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     */
    public function testOfDate()
    {
        $this->assertTrue(NumericFactory::of('input', 'date', 'any') instanceof DateFactory);
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     */
    public function testOfDateTime()
    {
        $this->assertTrue(NumericFactory::of('input', 'datetime-local', 'any') instanceof DateTimeFactory);
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     */
    public function testFactoryException()
    {
        $this->expectException(FactoryException::class);

        NumericFactory::of('select', null, null);
    }

}
