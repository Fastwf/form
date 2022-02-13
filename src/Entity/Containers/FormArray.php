<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Data\Violation;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Entity\Containers\Container;
use Fastwf\Constraint\Constraints\Arrays\Items;
use Fastwf\Constraint\Constraints\Type\ArrayType;

class FormArray extends Control implements Container
{

    /**
     * The control to use for the collection
     *
     * @var Control
     */
    protected $control;

    /**
     * The array of values to apply to each controls of the collection.
     *
     * @var array
     */
    protected $value = [];

    /**
     * The violtion on array value
     *
     * @var Violation|null
     */
    protected $violation;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->control = ArrayUtil::get($parameters, 'control');
        $this->value = ArrayUtil::getSafe($parameters, 'value', []);
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

    public function setControl($control)
    {
        $this->control = $control;
    }

    public function getControl()
    {
        return $this->control;
    }

    public function getTag()
    {
        return null;
    }

    public function getContainerType()
    {
        return 'array';
    }

    public function getConstraint()
    {
        $constraints = [new ArrayType()];

        $constraint = $this->control->getConstraint();
        if ($constraint === null)
        {
            \array_push($constraints, new Items($constraint));
        }

        return new Chain(true, ...$constraints);
    }

    public function setViolation($violation)
    {
        $this->violation = $violation;
    }

    public function getData() {
        // Assign values to the control and perform data conversion.
        $data = [];

        foreach ($this->value as $item) {
            // Set the value
            $this->control->setValue($item);
            // Get value converted
            \array_push($data, $this->control->getData());
        }

        return $data;
    }

}
