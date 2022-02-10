<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Data\Violation;
use Fastwf\Form\Entity\Containers\Container;
use Fastwf\Form\Entity\Containers\EntityGroup;

/**
 * Container for radio buttons.
 */
class RadioGroup extends EntityGroup
{

    /// IMPLEMENT METHODS

    public function setValue($value)
    {
        // Apply the value to each radio input
        //  The internal data synchronisation allows to check the right radio or none
        foreach ($this->controls as $radio) {
            $radio->setValue($value);
        }
    }

    public function getValue()
    {
        // Search for a checked radio input and return its value
        foreach ($this->controls as $radio) {
            if ($radio->isChecked())
            {
                return $radio->getData();
            }
        }

        // else (no radio checked) return null
        return null;
    }

    public function getData()
    {
        return $this->getValue();
    }

}
