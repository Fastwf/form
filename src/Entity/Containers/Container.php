<?php

namespace Fastwf\Form\Entity\Containers;

interface Container extends \IteratorAggregate
{

    /**
     * Allows to identity the container type of inputs.
     * 
     * Warning: this method makes sense only when getTag() return null.
     *
     * @return string the container name (array, object or widget)
     */
    public function getContainerType();

}
