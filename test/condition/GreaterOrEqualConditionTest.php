<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 6:59 AM
 */

define('GEQ_CONDITION_FIELD', 'GEQ_CONDITION_FIELD');

new GreaterOrEqualConditionTest();

class GreaterOrEqualConditionTest extends TestAutoRunner
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
        return GreaterOrEqualCondition::with(GEQ_CONDITION_FIELD, $valueToBeGreaterOrEqualTo);
    }

    public function performAdjustment($newValue){
        Adjustment::with(GEQ_CONDITION_FIELD, $newValue)->performAdjustment();
    }

    public function testConditionPassEqual(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getCondition(1),
            true,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionPassGreater(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getCondition(0),
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
        $this->performAdjustment(0);
        $this->checkValue(
            $this->getCondition(1),
            false,
            testId(__FILE__, __FUNCTION__)
        );
    }
}