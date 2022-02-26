<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Containers\FormArray;

/**
 * Iterator that use a base FormArray group to iterate over form controls.
 */
class FormArrayIterator implements \Iterator {

    /**
     * The form array to use for iteration
     *
     * @var FormArray
     */
    private $group;

    /**
     * The internal array index
     *
     * @var integer
     */
    private $index = 0;

    /**
     * The internal array length of $this->group for control list.
     *
     * @var integer
     */
    private $length = 0;

    public function __construct($group)
    {
        $this->group = $group;
    }

    public function current() {
        $control = $this->group->getControl();

        // Set the control state using group informations
        $control->setValue($this->group->getValue()[$this->index]);
        
        $violation = $this->group->getViolation();
        $controlViolation = null;
        if ($violation !== null) {
            $children = $violation->getChildren();

            if (\array_key_exists($this->index, $children)) {
                $controlViolation = $children[$this->index];
            }
        }
        $control->setViolation($controlViolation);

        return $control;
    }

    public function key() {
        return $this->index;
    }

    public function next(): void {
        $this->index++;
    }

    public function rewind(): void {
        $this->index = 0;
        $this->length = \count($this->group->getValue());
    }

    public function valid(): bool {
        return $this->index < $this->length;
    }

}
