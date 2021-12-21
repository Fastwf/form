<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Textarea;

class TextareaTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Textarea
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testGetTag()
    {
        $html = new Textarea();

        $this->assertEquals('textarea', $html->getTag());
    }

}
