<?php

namespace Fastwf\Form\Entity;

/**
 * Interface that indicate if an object is a printable html element or not.
 */
interface Element
{

    /**
     * Indicate the tag of HTML element or null when it is not printable.
     *
     * @return string|null the html tag or null.
     */
    public function getTag();

}
