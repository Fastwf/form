<?php

namespace Fastwf\Tests\Entity\Html;

use Fastwf\Form\Entity\Html\InputFile;
use PHPUnit\Framework\TestCase;

class InputFileTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\InputFile
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testGetFullName()
    {
        $control = new InputFile(['name' => 'file']);

        $this->assertEquals('file', $control->getFullName());

        $control->setMultiple(true);
        $this->assertEquals('file[]', $control->getFullName());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\InputFile
     * @covers Fastwf\Form\Utils\EntityUtil
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testSynchronizeMultiple()
    {
        $control = new InputFile(['name' => 'file']);

        $control->setMultiple(true);
        $this->assertEquals(true, $control->getAttributes()['multiple']);

        $control->setMultiple(false);
        $this->assertFalse(\array_key_exists('multiple', $control->getAttributes()));
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Entity\Html\InputFile
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testIsMultiple()
    {
        $control = new InputFile(['name' => 'file', 'multiple' => true]);

        $control->setMultiple(true);
        $this->assertTrue($control->isMultiple());
    }

}
