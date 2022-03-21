<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Form\Parsing\ParserInterface;

/**
 * Input radio data parser.
 * 
 * @inherits AParser<string|null>
 */
class RadioParser implements ParserInterface
{

    public function strigify($value, $control)
    {
        /** @var Radio $control */
        return $control->getValueAttribute();
    }

    public function parse($sequence, $control)
    {
        /** @var Radio $control */
        return $control->isChecked() ? $control->getValueAttribute() : null;
    }

}
