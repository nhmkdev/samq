<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 6:59 AM
 */

define('EQUAL_CONDITION_FIELD', 'EQUAL_CONDITION_FIELD');
define('EQUAL_CONDITION_VALUE', 1);

new EqualConditionTest();

class EqualConditionTest extends TestAutoRunner
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

    function getCondition($expectedValue){
        return EqualCondition::with(EQUAL_CONDITION_FIELD, $expectedValue);
    }

    public function performAdjustment($newValue){
        Adjustment::with(EQUAL_CONDITION_FIELD, $newValue)->performAdjustment();
    }

    public function testConditionEqual(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getCondition(1),
            true,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionNotEqual(){
        $this->performAdjustment(1);
        $this->checkValue(
            $this->getCondition(0),
            false,
            testId(__FILE__, __FUNCTION__)
        );
    }

    public function testConditionNotEqualUnset(){
        $this->checkValue(
            $this->getCondition(0),
            false,
            testId(__FILE__, __FUNCTION__)
        );
    }

    // TODO: test the store?
}