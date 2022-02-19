<?php

namespace Fastwf\Form\Build\Constraints\String;

use Fastwf\Constraint\Constraints\String\UriFormat;
use Fastwf\Form\Build\Constraints\String\StringConstraintBuilder;

/**
 * Builder for input[url] form control.
 */
class UrlConstraintBuilder extends StringConstraintBuilder
{

    protected function resetFrom($control, $type, $constraints)
    {
        parent::resetFrom($control, $type, $constraints);

        // Add the uri constraint
        \array_push($this->constraints, new UriFormat());
    }

}
