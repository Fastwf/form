<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Parsing\ParserInterface;

/**
 * Common parser system for any input that handle only strings.
 */
class StringParser implements ParserInterface
{

    public function strigify($value, $_)
    {
        // null value must be stringified as empty string
        return $value === null ? '' : $value;
    }

    public function parse($sequence, $_)
    {
        return $sequence;
    }

}
