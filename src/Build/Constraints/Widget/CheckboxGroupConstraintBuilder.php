<?php

namespace Fastwf\Form\Build\Constraints\Widget;

use Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder;

/**
 * Builder for checkbox-group control.
 */
class CheckboxGroupConstraintBuilder extends AOptionMultipleConstraintBuilder
{

    protected function resetMultipleFlag()
    {
        // This widget is an equivalent of select form control with multiple mode activated
        $this->multiple = true;
    }

}
