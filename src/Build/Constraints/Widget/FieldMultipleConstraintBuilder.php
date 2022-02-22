<?php

namespace Fastwf\Form\Build\Constraints\Widget;

use Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder;

/**
 * Builder for select or input[file] form control.
 */
class FieldMultipleConstraintBuilder extends AOptionMultipleConstraintBuilder
{

    public function __construct()
    {
        parent::__construct();

        // Register the factory to allows to update the $multiple factory
        $this->setFactory('multiple', [$this, 'multipleFactory']);
    }

    /**
     * The factory callback to use that allows to set the multiple value from given parameters.
     *
     * @param string $_1 (ignored) the control.
     * @param string|null $_2 (ignored) the control type.
     * @param boolean $multiple true to set as multiple, false otherwise.
     * @return array
     */
    public function multipleFactory($_1, $_2, $multiple)
    {
        $this->multiple = $multiple;

        return [
            self::ATTRS => ['multiple' => true],
        ];
    }

}
