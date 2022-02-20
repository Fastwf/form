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
 * Builder for select form control.
 */
class SelectConstraintBuilder extends ASelectConstraintBuilder
{

    public function __construct()
    {
        // Register the factory to allows to update the $multiple factory
        $this->setFactory('multiple', [$this, 'multipleFactory']);
    }

    /**
     * The factory callback to use that allows to set the multiple value from given parameters.
     *
     * @param string $_1 (ignored) the control.
     * @param string|null $_2 (ignored) the control type.
     * @param boolean $multiple true to set as multiple, false otherwise.
     * @return void
     */
    public function multipleFactory($_1, $_2, $multiple)
    {
        $this->multiple = $multiple;

        return [
            self::ATTRS => ['multiple' => true],
        ];
    }

}
