<?php

namespace Fastwf\Form\Constraints\String;

use Fastwf\Constraint\Data\Node;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Constraints\Arrays\Items;

/**
 * This constraint can be used to modify the value by splitting the incomming value.
 * After modification, child constraint validate each items like Items constraint.
 * 
 * No verification are performed on input value, so chain this constraint to be sure to have string.
 */
class SplitModifier extends Items
{

    /**
     * The char delimiter to use to split the data.
     *
     * @var string
     */
    private $delimiter;

    /**
     * flag that indicate if it's required to trim each values splitted.
     *
     * @var boolean
     */
    private $trim;

    /**
     * Constructor.
     *
     * @param string $delimiter the delimiter to ues for split.
     * @param boolean $trim true (the default) if it's required to trim each splitted values.
     * @param Constraint $constraint the constraint to apply to each splitted values.
     */
    public function __construct($delimiter, $trim, $constraint)
    {
        parent::__construct($constraint);

        $this->delimiter = $delimiter;
        $this->trim = $trim;
    }

    public function validate($node, $context)
    {
        // Considering that is a string
        $array = \explode(
            $this->delimiter,
            $node->get()
        );

        // Trim values when it's required
        if ($this->trim)
        {
            $array = \array_map("\\trim", $array);
        }

        // Validate each items using the constraint
        return parent::validate(Node::from(['value' => $array]), $context);
    }

}
