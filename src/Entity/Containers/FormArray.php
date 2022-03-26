<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Form\Entity\Control;
use Fastwf\Api\Exceptions\KeyError;
use Fastwf\Constraint\Data\Violation;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Entity\Containers\Container;
use Fastwf\Constraint\Constraints\Arrays\Items;
use Fastwf\Constraint\Constraints\Type\ArrayType;

/**
 * Form group that allows to create a collection of same control.
 * 
 * To create an array of pseudo that represent a team member in a form like next exemple:
 * ```html
 * <!-- ... -->
 * <form action="" method="post">
 *   <input type="text" name="members[0]" />
 *   <input type="text" name="members[1]" />
 *   <input type="text" name="members[2]" />
 *   <input type="text" name="members[3]" />
 *   <button>Save</button>
 * </form>
 * ```
 * 
 * Use this class with an Input control named members.
 */
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

    /**
     * {@inheritDoc}
     *
     * @param array{
     *      control:Control,
     *      value?:array,
     *      violation?:Violation,
     *      min_size?:integer
     * } $parameters The form array parameters that extends {@see Control::__construct} parameters.
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        // For form array, the name is required -> verify and throw an error when it is absent
        if ($this->name === null)
        {
            throw new KeyError("The key 'name' is required for " . FormArray::class);
        }

        $this->control = ArrayUtil::get($parameters, 'control');
        $this->setValue(ArrayUtil::getSafe($parameters, 'value', []));
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
        $this->value = $value === null ? [] : $value;
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
        if ($constraint !== null)
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
        return empty($this->value)
            ? $this->minSize
            : \max($this->minSize, \count($this->value));
    }

    /**
     * {@inheritDoc}
     * 
     * @return \Iterator An iterator that form each control with name, value and violation. 
     */
    public function getIterator(): \Traversable
    {
        return new FormArrayIterator($this);
    }

}
