<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 6:59 AM
 */

define('NOT_EQUAL_CONDITION_FIELD', 'NOT_EQUAL_CONDITION_FIELD');
define('NOT_EQUAL_CONDITION_VALUE', 1);

new NotEqualConditionTest();

class NotEqualConditionTest extends TestAutoRunner
{
    public function setupTest()
    {
        session_unset();
    }

    function checkValue($condition, $expectedResult, $function){
        $actualResult = $condition->isMet();
        verify($actualResult == $expectedResult, $function,
            'Actual value:'.$actualResult.' should be '.$expectedResult);
    }

    function getCondition($mustNotEqualexpectedValue){
        return NotEqualCondition::with(NOT_EQUAL_CONDITION_FIELD, $mustNotEqualexpectedValue);
    }

    public function performAdjustment($newValue){
        Adjustment::with(NOT_EQUAL_CONDITION_FIELD, $newValue)->performAdjustment();
    }

    public function testConditionPass(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getCondition(0),
            true,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionPassUnset(){
        $this->checkValue(
            $this->getCondition(0),
            true,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionFail(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getCondition(1),
            false,
            testId(__FILE__, __FUNCTION__)
        );
    }
}