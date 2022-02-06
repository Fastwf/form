<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Input;

/**
 * Base class for checkbox and radio inputs.
 */
abstract class CheckableInput extends Input
{

    /**
     * The constant that indicate to synchronize using construct strategy.
     */
    protected const SYNC_CONSTRUCT = 0;

    /**
     * The constant that indicate to synchronize using attribute priority.
     */
    protected const SYNC_ATTRIBUTES = 1;

    /**
     * The constant that indicate to synchronize using value priority.
     */
    protected const SYNC_VALUE = 2;

    /**
     * The constant that indicate to synchronize using check priority.
     */
    protected const SYNC_CHECKED = 3;

    /**
     * True when the input must be checked.
     *
     * @var boolean
     */
    protected $checked;

    /**
     * This value is the value to associate to the checkbox.
     * 
     * ...input value="$valueAttribute"...
     *
     * @var string
     */
    protected $valueAttribute;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->checked = ArrayUtil::getSafe($parameters, 'checked', false);

        $this->synchronizeValue(self::SYNC_CONSTRUCT);
    }

    public function setAttributes($attributes)
    {
        parent::setAttributes($attributes);

        $this->synchronizeValue(self::SYNC_ATTRIBUTES);
    }

    public function setValue($value)
    {
        parent::setValue($value);

        $this->synchronizeValue(self::SYNC_VALUE);
    }

    public function setChecked($checked)
    {
        $this->checked = $checked;

        $this->synchronizeValue(self::SYNC_CHECKED);
    }

    public function isChecked()
    {
        return $this->checked;
    }

    /**
     * Synchronise internal state to match the real state (correct checked and value properties).
     *
     * @param integer $priority the priority to use for synchronisation.
     * @return void
     */
    protected abstract function synchronizeValue($priority);

}
