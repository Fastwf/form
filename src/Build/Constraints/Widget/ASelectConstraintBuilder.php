<?php

namespace Fastwf\Form\Build\Constraints\Widget;

use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Constraint\Constraints\Nullable;
use Fastwf\Constraint\Constraints\Arrays\Items;
use Fastwf\Constraint\Constraints\Type\ArrayType;
use Fastwf\Constraint\Constraints\Arrays\MaxItems;
use Fastwf\Constraint\Constraints\Arrays\MinItems;
use Fastwf\Constraint\Constraints\Arrays\UniqueItems;
use Fastwf\Form\Build\Constraints\AConstraintBuilder;

/**
 * Base Builder for select/checkbox-group form control.
 */
class ASelectConstraintBuilder extends AConstraintBuilder
{

    /**
     * A flag that indicate if the implementation must create a 'multiple' constraint.
     *
     * @var boolean
     */
    protected $multiple = false;

    /**
     * The array containing constraints to apply at array level when multiple flag is set.
     *
     * @var array
     */
    protected $arrayConstraints = [];

    public function __construct()
    {
        // Register the factory to allows to update the $multiple factory
        $this->setFactory('array', [$this, 'arrayFactory']);
    }

    /**
     * The factory callback to use that allows to set the multiple value from given parameters.
     *
     * @param string $_1 (ignored) the control.
     * @param string|null $_2 (ignored) the control type.
     * @param boolean $array the array constraint description.
     * @return void
     */
    public function arrayFactory($_1, $_2, $array)
    {
        $htmlAttributes = [];

        // Add MinItems and set as required when the minItems > 0
        if (\array_key_exists('minItems', $array) && $array['minItems'] > 0)
        {
            \array_push($this->arrayConstraints, new MinItems($array['minItems']));

            // When the min items > 0, a value is required in the select
            $this->required = true;
            $htmlAttributes['required'] = true;
        }
        // Add MaxItems
        if (\array_key_exists('maxItems', $array))
        {
            \array_push($this->arrayConstraints, new MaxItems($array['maxItems']));
        }
        // Add UniqueItems when is set to true
        if (\array_key_exists('uniqueItems', $array) && $array['uniqueItems'])
        {
            \array_push($this->arrayConstraints, new UniqueItems());
        }

        return [self::ATTRS => $htmlAttributes];
    }

    /**
     * Analyse builder state to finalize array constraint setup.
     *
     * @return void
     */
    private function prepareArrayConstraints()
    {
        // When the required flag is set, verify that array constraint contains MinItems constraint else add ['minItems' => 1]
        if ($this->required)
        {
            $minItemNotFound = true;
            foreach ($this->arrayConstraints as $arrayConstraint) {
                if ($arrayConstraint instanceof MinItems)
                {
                    $minItemNotFound = false;
                    break;
                }
            }

            if ($minItemNotFound)
            {
                \array_push($this->arrayConstraints, new MinItems(1));
            }
        }

        // Insert the Items constraint when $this->constraint is not empty as first array constraint
        if ($this->constraints)
        {
            \array_splice(
                $this->arrayConstraints,
                0,
                0,
                [new Items(self::chainConstraints($this->constraints, false))]
            );
        }
    }

    /**
     * Reset the multiple flag (for select is set to flase, but for child class it can be true).
     *
     * @return void
     */
    protected function resetMultipleFlag()
    {
        $this->multiple = false;
    }

    /// OVERRIDE METHODS

    protected function resetFrom($control, $type, $constraints)
    {
        parent::resetFrom($control, $type, $constraints);

        // Reset the multiple property
        $this->resetMultipleFlag();
        $this->arrayConstraints = [];
    }

    protected function buildEntryConstraint()
    {
        if ($this->multiple)
        {
            // The value to control is an array of enum values
            $this->prepareArrayConstraints();

            // Build correctly the array constraint
            $constraint = new ArrayType();
            if (!empty($this->arrayConstraints))
            {
                $constraint = new Chain(
                    true,
                    $constraint,
                    new Chain(false, ...$this->arrayConstraints),
                );
            }

            // For required, check that the value is not null
            return new Nullable(!$this->required, $constraint);
        }
        else
        {
            // The select set a value as a string and must match one of the enum values
            //  So insert as first constraint a StringField constraint
            \array_splice($this->constraints, 0, 0, [new StringField()]);

            //  Next build the entry constraint like standard constraints
            $constraint = parent::buildEntryConstraint();
        }

        return $constraint;
    }

}
