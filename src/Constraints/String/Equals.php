<?php

namespace Fastwf\Form\Constraints\String;

use Fastwf\Constraint\Api\Constraint;

/**
 * Constraint that validate that the value is the same as the reference value.
 */
class Equals implements Constraint
{

    /**
     * The reference value.
     *
     * @var mixed
     */
    private $reference;

    /**
     * Constructor.
     *
     * @param mixed $reference
     */
    public function __construct($reference)
    {
        $this->reference = $reference;
    }

    public function validate($node, $context)
    {
        $value = $node->get();

        return $value === $this->reference
            ? null
            : $context->violation($value, 'equals', ['reference' => $this->reference]);
    }

}
