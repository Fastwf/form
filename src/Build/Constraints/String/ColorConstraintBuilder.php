<?php

namespace Fastwf\Form\Build\Constraints\String;

use Fastwf\Form\Constraints\String\ColorFormat;
use Fastwf\Form\Build\Constraints\String\StringConstraintBuilder;

/**
 * Builder for input[color] form control.
 */
class ColorConstraintBuilder extends StringConstraintBuilder
{

    protected function resetFrom($control, $type, $constraints)
    {
        parent::resetFrom($control, $type, $constraints);

        // Add the color constraint
        \array_push($this->constraints, new ColorFormat());
    }

}
