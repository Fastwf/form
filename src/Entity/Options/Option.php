<?php

namespace Fastwf\Form\Entity\Options;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Options\AOption;

class Option extends AOption
{

    /**
     * Indicate if the option is selected or not.
     *
     * @var boolean
     */
    protected $selected;

    /**
     * The option value.
     *
     * @var string
     */
    protected $value;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        // By default the selected option is false (selection is delegated to Select entity)
        $this->selected = false;
        $this->value = ArrayUtil::get($parameters, 'value');
    }

    public function setSelected($selected)
    {
        $this->selected = $selected;
    }

    public function isSelected()
    {
        return $this->selected;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getTag()
    {
        return 'option';
    }

}
