<?php

namespace Fastwf\Form\Entity;

use Fastwf\Form\Entity\Element;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Data\Violation;

abstract class Control implements Element
{

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

    public function __construct($parameters = [])
    {
        $this->name = ArrayUtil::getSafe($parameters, 'name');
        $this->attributes = ArrayUtil::getSafe($parameters, 'attributes', []);
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

}
