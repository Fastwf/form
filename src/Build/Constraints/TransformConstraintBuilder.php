<?php

namespace Fastwf\Form\Build\Constraints;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Build\Constraints\AConstraintBuilder;

/**
 * Base constraint builder that include a validation and transform constraint builder like StringField, IntegerField, etc...
 */
abstract class TransformConstraintBuilder extends AConstraintBuilder
{

    protected function resetFrom($control, $type, $constraints)
    {
        parent::resetFrom($control, $type, $constraints);

        // Set as first constraint the transformation constraint
        \array_push($this->constraints, $this->getTransformConstraint($constraints));
    }

    /**
     * Allows to get the constraint transform object.
     *
     * @param array $constraints optional array of unique constraint name associated to it's options.
     * @return Constraint the constraint instance.
     */
    protected abstract function getTransformConstraint($constraints);

}
