<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 6:59 AM
 */

define('LEQ_CONDITION_FIELD', 'LEQ_CONDITION_FIELD');

new LessOrEqualConditionTest();

class LessOrEqualConditionTest extends TestAutoRunner
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

    function getCondition($valueToBeGreaterOrEqualTo){
        return LessOrEqualCondition::with(LEQ_CONDITION_FIELD, $valueToBeGreaterOrEqualTo);
    }

    public function performAdjustment($newValue){
        Adjustment::with(LEQ_CONDITION_FIELD, $newValue)->performAdjustment();
    }

    public function testConditionPassEqual(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getCondition(1),
            true,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionPassLess(){
        $this->performAdjustment(0);
        $this->checkValue(
            $this->getCondition(1),
            true,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionFailUnset(){
        $this->checkValue(
            $this->getCondition(0),
            false,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionFail(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getCondition(0),
            false,
            testId(__FILE__, __FUNCTION__)
        );
    }
}