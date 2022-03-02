<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\FormControl;

/**
 * Entity definition for "button" html element.
 */
class Button extends FormControl
{

    public const TYPE_SUBMIT = 'submit';
    public const TYPE_RESET = 'reset';
    public const TYPE_BUTTON = 'button';

    /**
     * The button type (see TYPE_SUBMIT, TYPE_RESET and TYPE_BUTTON const).
     *
     * @var string
     */
    protected $type;

    /**
     * {@inheritDoc}
     *
     * @param array{type?:string} $parameters The button parameters that extends {@see FormControl::__construct} parameters.
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->type = ArrayUtil::getSafe($parameters, 'type', self::TYPE_SUBMIT);
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTag()
    {
        return 'button';
    }

}
