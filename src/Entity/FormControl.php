<?php

namespace Fastwf\Form\Entity;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Data\Violation;

abstract class FormControl extends Control
{

    /**
     * Allows to disable or not the form control.
     *
     * @var boolean
     */
    protected $disabled;

    /**
     * Allows to set the form control in readonly mode or not.
     *
     * @var boolean
     */
    protected $readonly;

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

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->disabled = ArrayUtil::getSafe($parameters, 'disabled', false);
        $this->readonly = ArrayUtil::getSafe($parameters, 'readonly', false);
        $this->value = ArrayUtil::getSafe($parameters, 'value');
        $this->constraint = ArrayUtil::getSafe($parameters, 'constraint');
        $this->violation = ArrayUtil::getSafe($parameters, 'violation');
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    public function isDisabled()
    {
        return $this->disabled;
    }

    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
    }

    public function isReadonly()
    {
        return $this->readonly;
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

}
