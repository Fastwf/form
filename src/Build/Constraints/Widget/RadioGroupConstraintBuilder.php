<?php

namespace Fastwf\Form\Build\Constraints\Widget;

use Fastwf\Constraint\Constraints\Nullable;
use Fastwf\Form\Build\Constraints\AConstraintBuilder;

/**
 * Builder for radio-group control.
 */
class RadioGroupConstraintBuilder extends AConstraintBuilder
{

    protected function buildEntryConstraint()
    {
        // Build all constraints (for radio group, normally, it requires only enum constraint)
        $chainedConstraints = $this->buildConstraints();

        return new Nullable(!$this->required, $chainedConstraints);
    }

}
