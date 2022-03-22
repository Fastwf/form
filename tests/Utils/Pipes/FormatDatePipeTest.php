<?php

namespace Fastwf\Tests\Utils\Pipes;

use DateTime;
use Fastwf\Api\Exceptions\ValueError;
use PHPUnit\Framework\TestCase;
use Fastwf\Interpolation\LexInterpolator;
use Fastwf\Form\Utils\Pipes\BasePipeInstaller;

class FormatDatePipeTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Utils\Pipes\BasePipeInstaller
     * @covers Fastwf\Form\Utils\Pipes\FormatDatePipe
     * @covers Fastwf\Form\Utils\Pipes\FormatTimePipe
     */
    public function testError()
    {
        $this->expectException(ValueError::class);

        $interpolator = new LexInterpolator();
        (new BasePipeInstaller())->install($interpolator->getEnvironment());

        $interpolator->interpolate('%{date|fmtDate(type)}', ['date' => new DateTime(), 'type' => 'unknown']);
    }

}
