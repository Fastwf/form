<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Entity\Containers\Container;
use Fastwf\Constraint\Constraints\Objects\Schema;
use Fastwf\Constraint\Constraints\Type\ObjectType;

class FormGroup extends Control implements Container
{

    /**
     * The array of controls.
     *
     * @var array
     */
    protected $controls;
    
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->controls = ArrayUtil::get($parameters, 'controls');
    }

    public function setControls($controls)
    {
        $this->controls = $controls;
    }

    public function getControls()
    {
        return $this->controls;
    }

    /**
     * Add a new control node at the end of the container.
     *
     * @param Control $control
     * @return void
     */
    public function addControl($control)
    {
        \array_push($this->controls, $control);
    }

    public function setControlAt($index, $control)
    {
        $this->controls[$index] = $control;
    }

    public function getControlAt($index)
    {
        return ArrayUtil::get($this->controls, $index);
    }

    /**
     * Search the control associated to the $name.
     *
     * @param string $name the name of the control node
     * @return Control|null the first node matching or null 
     */
    public function findControl($name)
    {
        // Search in the control array while the name is not found
        foreach ($this->controls as $control) {
            if ($control->getName() === $name)
            {
                return $control;
            }
        }

        return null;
    }

    public function getTag()
    {
        return null;
    }

    public function setValue($value)
    {
        foreach ($this->controls as $control) {
            $name = $control->getName();

            if (\array_key_exists($name, $value))
            {
                $control->setValue($value[$name]);
            }
        }
    }

    public function getValue()
    {
        $value = [];

        foreach ($this->controls as $control) {
            $value[$control->getName()] = $control->getValue();
        }

        return $value;
    }

    public function getContainerType()
    {
        return 'object';
    }

    public function getConstraint()
    {
        // A group is an object of properties, create Schema constraint
        $properties = [];

        foreach ($this->controls as $control) {
            $constraint = $control->getConstraint();
            if ($constraint !== null)
            {
                $properties[$control->getName()] = $constraint;
            }
        }

        return new Chain(
            true,
            new ObjectType(),
            new Schema(['properties' => $properties]),
        );
    }

    public function setViolation($violation)
    {
        // For group the children violation must be set
        $children = $violation->getChildren();

        foreach ($this->controls as $control) {
            $name = $control->getName();

            if (\array_key_exists($name, $children))
            {
                $control->setViolation($children[$name]);
            }
        }
    }

    /**
     * Collect data from each controls and return it as array of key/value.
     *
     * @return array the array of data
     */
    public function getData() {
        // Collect data from each controls elements.
        $data = [];

        foreach ($this->controls as $control) {
            if (\array_key_exists($control->name, $data))
            {
                $controlData = $control->getData();
                if ($controlData !== null)
                {
                    $data[$control->name] = $controlData;
                }
            }
            else
            {
                $data[$control->name] = $control->getData();
            }
        }

        return $data;
    }

}
