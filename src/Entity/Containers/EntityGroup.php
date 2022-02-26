<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Data\Violation;
use Fastwf\Form\Entity\Containers\AFormGroup;

/**
 * Base class for specific form group implementation that require to be defined as HTML entity.
 */
abstract class EntityGroup extends AFormGroup
{

    /**
     * The string that define the label of the group widget.
     *
     * @var string|null
     */
    private $label;

    /**
     * The string help associated to the radio group.
     *
     * @var string|null
     */
    private $help;

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

    public function __construct($parameters)
    {
        parent::__construct($parameters);

        $this->label = ArrayUtil::getSafe($parameters, 'label');
        $this->help = ArrayUtil::getSafe($parameters, 'help');
        $this->constraint = ArrayUtil::getSafe($parameters, 'constraint');
        $this->violation = ArrayUtil::getSafe($parameters, 'violation');
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
