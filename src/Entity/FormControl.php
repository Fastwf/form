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
     * The label value associated to the form control.
     *
     * @var string
     */
    protected $label;

    /**
     * The value of the form control.
     *
     * @var string
     */
    protected $value;

    /**
     * The help message or description of this current form control.
     *
     * @var string
     */
    protected $help;

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

        $this->label = ArrayUtil::getSafe($parameters, 'label', '');
        $this->value = ArrayUtil::getSafe($parameters, 'value');
        $this->help = ArrayUtil::getSafe($parameters, 'help');
        $this->constraint = ArrayUtil::getSafe($parameters, 'constraint');
        $this->violation = ArrayUtil::getSafe($parameters, 'violation');
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setHelp($help)
    {
        $this->help = $help;
    }

    public function getHelp()
    {
        return $this->help;
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
