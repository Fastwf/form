<?php

namespace Fastwf\Form\Constraints\String;

use Fastwf\Constraint\Constraints\String\PcreFormat;

/**
 * HTML color input constraint.
 */
class ColorFormat extends PcreFormat
{

    public function __construct()
    {
        parent::__construct('color', '/^#[0-9a-fA-F]{6}$/');
    }

    protected function getName()
    {
        return 'field-color';
    }

}
