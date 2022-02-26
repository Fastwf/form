<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Data\Violation;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Entity\Containers\Container;
use Fastwf\Constraint\Constraints\Arrays\Items;
use Fastwf\Constraint\Constraints\Type\ArrayType;
use Fastwf\Form\Exceptions\KeyError;

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

    /**
     * The minimum size of the form array (by default 1).
     *
     * @var integer
     */
    protected $minSize;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        // For form array, the name is required -> verify and throw an error when it is absent
        if ($this->name === null)
        {
            throw new KeyError("The key 'name' is required for " . FormArray::class);
        }

        $this->control = ArrayUtil::get($parameters, 'control');
        $this->value = ArrayUtil::getSafe($parameters, 'value', []);
        $this->violation = ArrayUtil::getSafe($parameters, 'violation');

        $this->minSize = ArrayUtil::getSafe($parameters, 'min_size', 1);

        $this->setupControl();
    }

    /**
     * Allows to finalize setup controls inside this container.
     *
     * @return void
     */
    private function setupControl()
    {
        if ($this->control)
        {
            $this->control->setParent($this);
        }
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

        $this->setupControl();
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

    public function getViolation()
    {
        return $this->violation;
    }

    public function getData()
    {
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

    /**
     * Get the expected size of the form array.
     *
     * @return integer
     */
    public function getSize()
    {
        // Return the minimum size if value is not set or the number of values set in the array
        return $this->value === null
            ? $this->minSize
            : \max($this->minSize, \count($this->value));
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Traversable
    {
        return new FormArrayIterator($this);
    }

}
