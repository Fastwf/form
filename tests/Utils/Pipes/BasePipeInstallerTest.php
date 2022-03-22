<?php

namespace Fastwf\Tests\Utils\Pipes;

use DateTime;
use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Form;
use Fastwf\Form\Build\FormBuilder;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Utils\FormTemplateProvider;
use Fastwf\Form\Utils\Pipes\BasePipeInstaller;
use Fastwf\Interpolation\LexInterpolator;

class BasePipeInstallerTest extends TestCase
{

    /**
     * Generate a validator from Form.
     *
     * @param Form $form the form to validate
     * @return Validator the validator to use
     */
    private function getValidator($form)
    {
        $interpolator = new LexInterpolator();
        (new BasePipeInstaller())->install($interpolator->getEnvironment());

        return new Validator($form->getConstraint(), new FormTemplateProvider(), $interpolator);
    }

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\AConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\DateConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\DateTimeConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\MonthConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\NumberConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\TimeConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\WeekConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\EmailConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\StringConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\TransformConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\TimeFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\Date\ADateTimeField
     * @covers Fastwf\Form\Constraints\Date\DateField
     * @covers Fastwf\Form\Constraints\Date\DateTimeField
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Constraints\Date\MonthField
     * @covers Fastwf\Form\Constraints\Date\WeekField
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\DateParser
     * @covers Fastwf\Form\Parsing\DateTimeParser
     * @covers Fastwf\Form\Parsing\MonthParser
     * @covers Fastwf\Form\Parsing\WeekParser
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     * @covers Fastwf\Form\Utils\FormTemplateProvider
     * @covers Fastwf\Form\Utils\Pipes\BasePipeInstaller
     * @covers Fastwf\Form\Utils\Pipes\FormatTimePipe
     * @covers Fastwf\Form\Utils\DateTimeUtil
     * @covers Fastwf\Form\Utils\Pipes\FormatDatePipe
     */
    public function testFmtDate()
    {
        $min = new DateTime('2000-01-01 00:00:00.000');

        $form = FormBuilder::new('test', '')
            ->addInput('date', 'date', ['assert' => ['min' => $min]])
            ->addInput('dateTime', 'datetime-local', ['assert' => ['min' => $min]])
            ->addInput('month', 'month', ['assert' => ['min' => $min]])
            ->addInput('week', 'week', ['assert' => ['min' => DateTimeUtil::getWeek('2000-W01')]])
            ->build()
            ;
        
        $form->setValue([
            'date' => '1970-01-01',
            'dateTime' => '1970-01-01T00:00:00',
            'month' => '1970-01',
            'week' => '1970-W01',
        ]);

        $form->validate(null, $this->getValidator($form));
        $violation = $form->getViolation();

        $this->assertEquals(
            [
                'date' => 'The value must be greater or equals to 2000-01-01',
                'dateTime' => 'The value must be greater or equals to 2000-01-01 00:00',
                'month' => 'The value must be greater or equals to 2000-01',
                'week' => 'The value must be greater or equals to 2000-W01',
            ],
            [
                'date' => $violation->getChildren()['date']->getViolations()[0]->getMessage(),
                'dateTime' => $violation->getChildren()['dateTime']->getViolations()[0]->getMessage(),
                'month' => $violation->getChildren()['month']->getViolations()[0]->getMessage(),
                'week' => $violation->getChildren()['week']->getViolations()[0]->getMessage(),
            ]
        );
    }

    /**
     * @covers Fastwf\Form\Build\AGroupBuilder
     * @covers Fastwf\Form\Build\ConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\AConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\DateConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\DateTimeConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\MonthConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\NumberConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\TimeConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Numeric\WeekConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\EmailConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\String\StringConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\TransformConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\ContainerBuilder
     * @covers Fastwf\Form\Build\ContainerGroupBuilder
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Build\Factory\TimeFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Build\FormBuilder
     * @covers Fastwf\Form\Constraints\RequiredField
     * @covers Fastwf\Form\Constraints\Time\MinTime
     * @covers Fastwf\Form\Constraints\Time\TimeField
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input
     * @covers Fastwf\Form\Parsing\AParser
     * @covers Fastwf\Form\Parsing\TimeParser
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     * @covers Fastwf\Form\Utils\FormTemplateProvider
     * @covers Fastwf\Form\Utils\Pipes\BasePipeInstaller
     * @covers Fastwf\Form\Utils\Pipes\FormatTimePipe
     */
    public function testFmtTime()
    {
        $form = FormBuilder::new('test', '')
            ->addInput('time', 'time', ['assert' => ['min' => '07:00:00']])
            ->build()
            ;
        
        $form->setValue([
            'time' => '06:00:00',
        ]);

        $form->validate(null, $this->getValidator($form));
        $violation = $form->getViolation();

        $this->assertEquals(
            "The time must be greater or equals to 07:00",
            $violation->getChildren()['time']->getViolations()[0]->getMessage()
        );
    }

}
