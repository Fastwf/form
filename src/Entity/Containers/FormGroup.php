<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Entity\Containers\AFormGroup;
use Fastwf\Constraint\Constraints\Objects\Schema;
use Fastwf\Constraint\Constraints\Type\ObjectType;
use Fastwf\Constraint\Data\Violation;

class FormGroup extends AFormGroup
{

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

    public function setValue($value)
    {
        if ($value)
        {
            // When the value exists or is not empty it's required to associate the value for each controls
            foreach ($this->controls as $control) {
                $name = $control->getName();
    
                if (\array_key_exists($name, $value))
                {
                    $control->setValue($value[$name]);
                }
                else
                {
                    $control->setValue(null);
                }
            }
        }
        else
        {
            // This branch can be used when an array is not initialized or data is not set
            //  So reset to null each control values
            foreach ($this->controls as $control)
            {
                $control->setValue(null);
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
            new Schema(['properties' => $properties])
        );
    }

    public function setViolation($violation)
    {
        if ($violation !== null)
        {
            // For group the children violation must be set
            $children = $violation->getChildren();
    
            foreach ($this->controls as $control) {
                $name = $control->getName();
    
                if (\array_key_exists($name, $children))
                {
                    $control->setViolation($children[$name]);
                }
                else
                {
                    $control->setViolation(null);
                }
            }
        }
        else
        {
            // Reset the violation on each child
            foreach ($this->controls as $control)
            {
                $control->setViolation(null);
            }
        }
    }

    public function getViolation()
    {
        // For each children extract the violation if exists
        $children = [];
        foreach ($this->controls as $control) {
            $violation = $control->getViolation();

            if ($violation !== null) {
                $children[$control->getName()] = $violation;
            }
        }

        // Recreate the violation from children data and violations
        return new Violation($this->getData(), [], $children);
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
