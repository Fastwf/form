<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Data\Violation;
use Fastwf\Form\Entity\Containers\AFormGroup;

/**
 * Base class for specific form group implementation that require to be defined as HTML entity.
 */
abstract class EntityGroup extends AFormGroup
{

    /**
     * The constraint to use to validate values
     *
     * @var Constraint
     */
    private $constraint;

    /**
     * The violation object representing the radio group error.
     *
     * @var Violation
     */
    private $violation;

    /**
     * {@inheritDoc}
     *
     * @param array{
     *      constraint?:Constraint,
     *      violation?:Violation
     * } $parameters The entity group parameters that extends {@see AFormGroup::__construct} parameters.
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->constraint = ArrayUtil::getSafe($parameters, 'constraint');
        $this->violation = ArrayUtil::getSafe($parameters, 'violation');
    }

    /**
     * {@inheritDoc}
     *
     * EntityGroup is used to hold input[radio] and input[checkbox] using specific group implementation.  
     * Radio and Checkbox Group have the same name of it's control, because to create the group we use form builder.  
     * 
     * So, to prevent to have doubled name in full name return directly the parent name.  
     * > "parent[NAME][NAME]" will be "parent[NAME]"
     */
    public function getFullName()
    {
        return $this->parent->getFullName();
    }

    /// IMPLEMENT METHODS

    public function getContainerType()
    {
        return 'widget';
    }

    public function getConstraint()
    {
        return $this->constraint;
    }

    public function setViolation($violation)
    {
        return $this->violation = $violation;
    }

    /// PUBLIC METHODS

    /**
     * Set the constraint to be used by entity group.
     *
     * @param Constraint $constraint the new constraint.
     * @return void
     */
    public function setConstraint($constraint)
    {
        $this->constraint = $constraint;
    }

    /**
     * Get violation object attached to this group.
     *
     * @return Violation|null the violation object when validation failed or null otherwise.
     */
    public function getViolation()
    {
        return $this->violation;
    }

}
