<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 6:59 AM
 */

new IncrementAdjustmentTest();

define('ADJUSTMENT_INCREMENT_FIELD', 'ADJUSTMENT_INCREMENT_FIELD');

class IncrementAdjustmentTest extends TestAutoRunner
{
    function setupTest()
    {
        session_unset();
    }

    function checkValue($adjustment, $expectedValue, $function){
        $actualValue = $_SESSION[ADJUSTMENT_DEFAULT_STORE][ADJUSTMENT_INCREMENT_FIELD];
        verify($actualValue == $expectedValue, $function,
            'Actual value:'.$actualValue.' should be '.$expectedValue);
    }

    public function createAndPerformAdjustment($incrementAmount){
        $adjustment = IncrementAdjustment::with(ADJUSTMENT_INCREMENT_FIELD, $incrementAmount);
        $adjustment->performAdjustment();
        return $adjustment;
    }

    public function testUnsetIncrement(){
        $this->checkValue(
            $this->createAndPerformAdjustment(1),
            1,
            testId(__FILE__, __FUNCTION__));
    }

    public function testSetIncrementAtZero(){
        $this->createAndPerformAdjustment(0);
        $this->checkValue(
            $this->createAndPerformAdjustment(1),
            1,
            testId(__FILE__, __FUNCTION__));
    }

    public function testSetIncrementAtOne(){
        $this->createAndPerformAdjustment(1);
        $this->checkValue(
            $this->createAndPerformAdjustment(1),
            2,
            testId(__FILE__, __FUNCTION__));
    }

    public function testUnsetIncrementTen(){
        $this->checkValue(
            $this->createAndPerformAdjustment(10),
            10,
            testId(__FILE__, __FUNCTION__));
    }

    public function testSetIncrementAtFive(){
        $this->createAndPerformAdjustment(5);
        $this->checkValue(
            $this->createAndPerformAdjustment(10),
            15,
            testId(__FILE__, __FUNCTION__));
    }

}