<?php

namespace Fastwf\Form\Entity\Containers;

interface Container
{

    /**
     * Set the value on each controls of the container.
     *
     * @param mixed $value
     * @return void
     */
    public function setValue($value);

    /**
     * Get the value from each controls of the container.
     *
     * @return mixed the value
     */
    public function getValue();

    /**
     * Allows to identity the container type of inputs.
     * 
     * Warning: this method makes sense only when getTag() return null.
     *
     * @return string the container name (array or object)
     */
    public function getContainerType();

}
