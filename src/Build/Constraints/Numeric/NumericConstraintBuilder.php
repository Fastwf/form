<?php

namespace Fastwf\Form\Build\Constraints\Numeric;

use Fastwf\Form\Constraints\Date\DateField;
use Fastwf\Form\Build\Factory\NumericFactory;
use Fastwf\Form\Build\Constraints\TransformConstraintBuilder;

/**
 * Base builder for input form control that accept min/max/step.
 */
abstract class NumericConstraintBuilder extends TransformConstraintBuilder
{

    public function __construct()
    {
        $numericFactory = $this->getNumericFactory();

        // Register factory functions for min/max/step constraints
        $this->setFactory('min', function ($_1, $_2, $min, $_3) use ($numericFactory) {
                return $numericFactory->min($min);
            })
            ->setFactory('max', function ($_1, $_2, $max, $_3) use ($numericFactory) {
                return $numericFactory->max($max);
            })
            ->setFactory('step', function ($_1, $_2, $step, $constraints) use ($numericFactory) {
                return $numericFactory->step($step, $constraints);
            })
            ;
    }

    /**
     * Get the numeric constraint factory associated to the builder specification.
     *
     * @return NumericFactory the numeric factory instance.
     */
    protected abstract function getNumericFactory();

    /**
     * Verify if the constraint contains a step assert and the step is a double.
     *
     * @param array $constraints the array of constraints applied to the form control.
     * @return boolean true when the step is a double.
     */
    protected static function isDoubleConstraint($constraints)
    {
        $isDouble = false;

        if (\array_key_exists('step', $constraints))
        {
            $step = $constraints['step'];

            $type = \gettype($step);
            if ($type === 'string')
            {
                // Verify that the string is a double representation
                $matches = [];
                $isDouble = \preg_match("/^\\d+\\.(\\d+)$/", $constraints['step'], $matches) && ((int) $matches[1]) !== 0;
            }
            else if ($type === 'double')
            {
                // Check that the double is a real or an integer
                $isDouble = ((string) \ceil($step)) !== ((string) $step);
            }
        }

        return $isDouble;
    }

}
