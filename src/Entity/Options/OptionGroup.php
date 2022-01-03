<?php

namespace Fastwf\Form\Entity\Options;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Options\AOption;

class OptionGroup extends AOption
{

    /**
     * The list of options hold by this group.
     *
     * @var array
     */
    protected $options;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->options = ArrayUtil::get($parameters, 'options');
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
