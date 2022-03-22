<?php

namespace Fastwf\Form\Utils\Pipes;

use Fastwf\Interpolation\Api\Evaluation\Environment;
use Fastwf\Interpolation\Api\Evaluation\PipeInterface;

/**
 * Installer class that allows to add pipe required to format default form constraints.
 */
class BasePipeInstaller
{

    /**
     * Install the default required pipe in environment.
     *
     * @param Environment $environment The environment to update.
     */
    public function install($environment)
    {
        $environment->setPipe('fmtDate', $this->getFmtDatePipe());
        $environment->setPipe('fmtTime', $this->getFmtTimePipe());
    }

    /**
     * Get an instance of date format pipe.
     *
     * @return PipeInterface The instance to install in environment.
     */
    protected function getFmtDatePipe()
    {
        return new FormatDatePipe();
    }

    /**
     * Get an instance of time format pipe.
     *
     * @return PipeInterface The instance to install in environment.
     */
    protected function getFmtTimePipe()
    {
        return new FormatTimePipe();
    }

}
