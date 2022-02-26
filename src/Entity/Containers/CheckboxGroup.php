<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Html\Checkbox;
use Fastwf\Form\Entity\Containers\EntityGroup;

/**
 * Container for checkbox that allows to select multiple values.
 */
class CheckboxGroup extends EntityGroup
{

    /// IMPLEMENT METHODS

    public function setValue($value)
    {
        // Each checkbox must be checked when the value is found in incomming array
        /** @var Checkbox */
        foreach ($this->controls as $checkbox) {
            $index = \array_search($checkbox->getValueAttribute(), $value);
            if ($index !== false)
            {
                // Remove the value from the array and check the checkbox
                \array_splice($value, $index, 1);
                $checkbox->setChecked(true);
            }
            else
            {
                $checkbox->setChecked(false);
            }
        }
    }

    public function getValue()
    {
        // Collect the value of checkbox checked
        $value = [];

        /** @var Checkbox */
        foreach ($this->controls as $checkbox) {
            if ($checkbox->isChecked())
            {
                \array_push($value, $checkbox->getValue());
            }
        }

        return $value;
    }

    public function getData()
    {
        return $this->getValue();
    }

}
