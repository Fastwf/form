<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Entity\FormControl;
use Fastwf\Form\Parsing\ParserInterface;

/**
 * Abstract parser that allows to convert sequence to value or inverse without crash when the value is already as string or already parsed.
 * 
 * @template V
 * @inherits ParserInterface<V>
 */
abstract class AParser implements ParserInterface
{

    public function strigify($value, $control)
    {
        if ($value === null)
        {
            return '';
        }
        else
        {
            return \gettype($value) !== 'string'
                ? $this->valToStr($value, $control)
                : $value;
        }
    }

    /**
     * Strigify the given value as printable string.
     * 
     * @param V $value the value to strigify.
     * @param FormControl $control the control that call the parser interface.
     * @return string the string representation.
     */
    protected abstract function valToStr($value, $control);

    public function parse($sequence, $control)
    {
        return \gettype($sequence) === 'string'
            ? $this->strToVal($sequence, $control)
            : $sequence;
    }

    /**
     * Parse the given sequence to obtain a value.
     *
     * @param string $sequence the sequence to parse.
     * @param FormControl $control the control that call the parser interface.
     * @return V the value parsed.
     */
    protected abstract function strToVal($sequence, $control);

}
