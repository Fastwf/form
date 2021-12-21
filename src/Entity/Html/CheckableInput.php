<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Input;

class CheckableInput extends Input
{

    /**
     * True when the input must be checked.
     *
     * @var boolean
     */
    protected $checked;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->checked = ArrayUtil::getSafe($parameters, 'checked', false);
    }

    public function setChecked($checked)
    {
        $this->checked = $checked;
    }

    public function isChecked()
    {
        return $this->checked;
    }

}
