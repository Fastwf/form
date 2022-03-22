<?php

namespace Fastwf\Form\Utils\Pipes;

use Fastwf\Form\Utils\DateIntervalUtil;
use Fastwf\Interpolation\Api\Evaluation\PipeInterface;

/**
 * The format time pipe that allows to transform the time to humain format.
 */
class FormatTimePipe implements PipeInterface
{

    public function transform($value, $arguments)
    {
        return DateIntervalUtil::formatTime($value);
    }

}
