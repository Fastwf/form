<?php

namespace Fastwf\Form\Entity\Containers;

interface Container
{

    /**
     * Allows to identity the container type of inputs.
     * 
     * Warning: this method makes sense only when getTag() return null.
     *
     * @return string the container name (array or object)
     */
    public function getContainerType();

}
