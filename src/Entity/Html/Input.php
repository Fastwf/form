<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\FormControl;

class Input extends FormControl
{

    /**
     * The input type.
     *
     * @var string
     */
    protected $type;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->type = ArrayUtil::get($parameters, 'type');
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
        return 'input';
    }

}
