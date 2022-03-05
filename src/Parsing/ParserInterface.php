<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Entity\FormControl;

/**
 * Interface that define ability to strigify and parse input value.
 * 
 * @template T
 */
interface ParserInterface
{
    
    /**
     * Strigify the given value as printable string.
     * 
     * @param T|string $value the value to strigify.
     * @param FormControl $control the control that call the parser interface.
     * @return string the string representation.
     */
    public function strigify($value, $control);

    /**
     * Parse the given sequence to obtain a value.
     *
     * @param T|string $sequence the sequence to parse.
     * @param FormControl $control the control that call the parser interface.
     * @return T the value parsed.
     */
    public function parse($sequence, $control);

}
