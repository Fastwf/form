<?php

namespace Fastwf\Form\Entity\Options;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Options\AOption;

class OptionGroup extends AOption
{

    /**
     * The section name.
     *
     * @var string
     */
    protected $label;

    /**
     * The list of options hold by this group.
     *
     * @var array
     */
    protected $options;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->label = (string) ArrayUtil::getSafe($parameters, 'label', null);
        $this->options = ArrayUtil::get($parameters, 'options');
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getTag()
    {
        return 'optgroup';
    }

}
