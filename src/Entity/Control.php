<?php

namespace Fastwf\Form\Entity;

use Fastwf\Form\Entity\Element;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Data\Violation;

/**
 * Base behavior of controls in form.
 */
abstract class Control implements Element
{

    /**
     * The control node parent's.
     *
     * @var Control|null
     */
    protected $parent;

    /**
     * The name of the control.
     *
     * @var string
     */
    protected $name;

    /**
     * The associative array that define all key/value attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * The label value associated to the form control.
     *
     * @var string
     */
    protected $label;

    /**
     * The help message or description of this current form control.
     *
     * @var string
     */
    protected $help;

    /**
     * Constructor.
     *
     * @param array{
     *      parent?:Control,
     *      name?:string,
     *      attributes?:array<string,string|string[]|boolean>,
     *      value?:mixed,
     *      help?:string
     * } $parameters the form control parameters.
     */
    public function __construct($parameters = [])
    {
        $this->parent = ArrayUtil::getSafe($parameters, 'parent');
        $this->name = ArrayUtil::getSafe($parameters, 'name');
        $this->attributes = ArrayUtil::getSafe($parameters, 'attributes', []);
        $this->label = ArrayUtil::getSafe($parameters, 'label', '');
        $this->help = ArrayUtil::getSafe($parameters, 'help');
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setHelp($help)
    {
        $this->help = $help;
    }

    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Identify the full name fo the control according of its position in the form.
     * 
     * It requires to have a control parent correctly set for the entire form.
     *
     * @return string|null the full name in the form context.
     */
    public function getFullName()
    {
        // By default return the current name
        $fullName = $this->name;

        if ($this->parent !== null)
        {
            // Try to compute the full name using the parent control name
            $parentFullName = $this->parent->getFullName();

            if ($parentFullName !== null)
            {
                $fullName = $this->name === null
                    // $this->name can be null for parent group
                    ? $parentFullName
                    // else use the composition of the parent name and the control node name
                    : "{$parentFullName}[{$this->name}]";
            }
        }

        return $fullName;
    }

    /**
     * Get the constraints attached to this control node.
     *
     * @return Constraint|null
     */
    public abstract function getConstraint();

    /**
     * Set violation on this control node.
     *
     * @param Violation $violation the violation object
     * @return void
     */
    public abstract function setViolation($violation);

    /**
     * Get the violation set for the control node.
     *
     * @return Violation|null the violation set on node or null.
     */
    public abstract function getViolation();

    /**
     * Set the value attached to this control.
     *
     * @param mixed $value
     * @return void
     */
    public abstract function setValue($value);

    /**
     * Get the value attached to this control.
     *
     * @return mixed the value
     */
    public abstract function getValue();

    /**
     * Get the value attached to the control element after data type conversion.
     *
     * @return mixed the converted value.
     */
    public abstract function getData();

}
