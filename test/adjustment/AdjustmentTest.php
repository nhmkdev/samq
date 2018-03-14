<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 6:59 AM
 */

new AdjustmentTest();

define('ADJUSTMENT_FIELD', 'ADJUSTMENT_FIELD');

class AdjustmentTest extends TestAutoRunner
{
    function setupTest()
    {
        session_unset();
    }

    function checkValue($adjustment, $expectedValue, $function){
        $actualValue = $_SESSION[ADJUSTMENT_DEFAULT_STORE][ADJUSTMENT_FIELD];
        verify($actualValue == $expectedValue, $function,
            'Actual value:'.$actualValue.' should be '.$expectedValue);
    }

    public function createAndPerformAdjustment($newValue){
        $adjustment = Adjustment::with(ADJUSTMENT_FIELD, $newValue);
        $adjustment->performAdjustment();
        return $adjustment;
    }

    public function testUnsetIncrement(){
        $this->checkValue(
            $this->createAndPerformAdjustment(1),
            1,
            testId(__FILE__, __FUNCTION__));
    }

    public function testSetIncrement(){
        $this->createAndPerformAdjustment(0);
        $this->checkValue(
            $this->createAndPerformAdjustment(1),
            1,
            testId(__FILE__, __FUNCTION__));
    }

    public function testSetIncrementString(){
        $this->createAndPerformAdjustment('initialValue');
        $this->checkValue(
            $this->createAndPerformAdjustment('newValue'),
            'newValue',
            testId(__FILE__, __FUNCTION__));
    }
}