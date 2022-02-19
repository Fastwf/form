<?php

namespace Fastwf\Form\Build\Constraints\String;

use Fastwf\Form\Constraints\StringField;
use Fastwf\Constraint\Constraints\String\Pattern;
use Fastwf\Constraint\Constraints\String\MaxLength;
use Fastwf\Constraint\Constraints\String\MinLength;
use Fastwf\Form\Build\Constraints\TransformConstraintBuilder;

/**
 * Base builder for all text base form control (textarea, input[text,password,tel,...]).
 */
class StringConstraintBuilder extends TransformConstraintBuilder
{

    public function __construct()
    {
        $this->setFactory('minLength', function ($_1, $_2, $length, $_3) {
                // Expect a length as options
                return [self::CSTRT => new MinLength($length), self::ATTRS => ["minlength" => $length]];
            })
            ->setFactory('maxLength', function ($_1, $_2, $length, $_3) {
                // Expect a length as options
                return [self::CSTRT => new MaxLength($length), self::ATTRS => ["maxlength" => $length]];
            })
            ->setFactory('pattern', function ($_1, $_2, $pattern, $_3) {
                // Expect a pattern as options
                return [self::CSTRT => new Pattern($pattern), self::ATTRS => ["pattern" => $pattern]];
            })
            ;
    }

    protected function getTransformConstraint($constraints)
    {
        return new StringField();
    }

}
