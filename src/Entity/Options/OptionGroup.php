<?php

namespace Fastwf\Form\Entity\Options;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Options\Option;
use Fastwf\Form\Entity\Options\AOption;

class OptionGroup extends AOption
{

    /**
     * The list of options hold by this group.
     *
     * @var array<Option>
     */
    protected $options;

    /**
     * {@inheritDoc}
     *
     * @param array{options:array<Option>} $parameters The option group parameters that extends {@see AOption::__construct} parameters.
     */
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
