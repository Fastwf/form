<?php

namespace Fastwf\Form\Build\Constraints\Widget;

use Fastwf\Form\Build\Constraints\Widget\ASelectConstraintBuilder;

/**
 * Builder for checkbox-group control.
 */
class CheckboxGroupConstraintBuilder extends ASelectConstraintBuilder
{

    protected function resetMultipleFlag()
    {
        // This widget is an equivalent of select form control with multiple mode activated
        $this->multiple = true;
    }

}
