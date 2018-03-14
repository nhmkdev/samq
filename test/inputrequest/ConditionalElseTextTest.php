<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 7:22 AM
 */

//  test pass, fail if and else

define('CONDITIONAL_ELSE_TEXT_FIELD', 'CONDITIONAL_ELSE_TEXT_FIELD');
//define('CONDITIONAL_ELSE_TEXT_VALUE', 1);
define('CONDITIONAL_ELSE_TEXT_TEXT', 'Test Text');
// haha, this define name...
define('CONDITIONAL_ELSE_TEXT_ELSE_TEXT', 'Else Text');

new ConditionalElseTextTest();

class ConditionalElseTextTest extends TestAutoRunner
{
    public function setupTest()
    {
        session_unset();
    }

    function checkValue($conditionalText, $expectedResult, $function){
        $actualResult = $conditionalText->getConditionalText();
        verify($actualResult == $expectedResult, $function,
            'Actual value:'.$actualResult.' should be '.$expectedResult);
    }

    function getConditionalText($conditions){
        return ConditionalElseText::withElse(CONDITIONAL_ELSE_TEXT_TEXT, CONDITIONAL_ELSE_TEXT_ELSE_TEXT, $conditions);
    }

    public function performAdjustment($newValue){
        Adjustment::with(CONDITIONAL_ELSE_TEXT_FIELD, $newValue)->performAdjustment();
    }

    public function testConditionPass(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getConditionalText(
                EqualCondition::with(CONDITIONAL_ELSE_TEXT_FIELD, 1)
            ),
            CONDITIONAL_ELSE_TEXT_TEXT,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionFail(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getConditionalText(
                EqualCondition::with(CONDITIONAL_ELSE_TEXT_FIELD, 0)
            ),
            CONDITIONAL_ELSE_TEXT_ELSE_TEXT,
            testId(__FILE__, __FUNCTION__)
        );
    }
}