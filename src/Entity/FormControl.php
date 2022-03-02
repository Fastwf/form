<?php

namespace Fastwf\Form\Entity;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Data\Violation;

/**
 * Entity that define common properties of form control.
 */
abstract class FormControl extends Control
{

    /**
     * The value of the form control.
     *
     * @var string
     */
    protected $value;

    /**
     * The constraint to use to validate the value.
     *
     * @var Constraint
     */
    protected $constraint;

    /**
     * The violation to apply to this form control.
     *
     * @var Violation
     */
    protected $violation = [];

    /**
     * {@inheritDoc}
     *
     * @param array{
     *      value?:mixed,
     *      constraint?:Constraint,
     *      violation?:Violation
     * } $parameters the form control parameters that extends {@see Control::__construct} parameters.
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->value = ArrayUtil::getSafe($parameters, 'value');
        $this->constraint = ArrayUtil::getSafe($parameters, 'constraint');
        $this->violation = ArrayUtil::getSafe($parameters, 'violation');
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setConstraint($constraint)
    {
        $this->constraint = $constraint;
    }

    public function getConstraint()
    {
        return $this->constraint;
    }

    public function setViolation($violation)
    {
        $this->violation = $violation;
    }

    public function getViolation()
    {
        return $this->violation;
    }

    public function getData()
    {
        // By default the form control must return the associated string value (or null)
        return $this->value;
    }

}
