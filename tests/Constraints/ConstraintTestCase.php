<?php

namespace Fastwf\Tests\Constraints;

use PHPUnit\Framework\TestCase;
use Fastwf\Constraint\Api\ValidationContext;

abstract class ConstraintTestCase extends TestCase
{

    protected $context;

    protected function setup(): void
    {
        $this->context = new ValidationContext(null, null);
    }

}
