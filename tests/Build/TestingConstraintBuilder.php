<?php

namespace Fastwf\Tests\Build;

use Fastwf\Form\Build\Constraints\AConstraintBuilder;

/**
 * Testing class to allows to cover chainConstraints method
 */
class TestingConstraintBuilder extends AConstraintBuilder
{

    protected function buildConstraints()
    {
        return self::chainConstraints(null, false);
    }

}
