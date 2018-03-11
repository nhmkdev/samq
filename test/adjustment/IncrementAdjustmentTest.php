<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 6:59 AM
 */

new IncrementAdjustmentTest();

define('INCREMENT_FIELD', 'INCREMENT_FIELD');

class IncrementAdjustmentTest extends TestAutoRunner
{
    function setupTest()
    {
        session_unset();
    }

    function checkValue($adjustment, $expectedValue, $function){
        $actualValue = $_SESSION[ADJUSTMENT_DEFAULT_STORE][INCREMENT_FIELD];
        verify($actualValue == $expectedValue, $function,
            'Actual value:'.$actualValue.' should be '.$expectedValue.' '.$function);
    }

    /**
     * This method creates and applies a new adjustment
     * @param $incrementAmount int amount of the adjustment
     * @return Adjustment|IncrementAdjustment the adjustment
     */
    public function createAdjustment($incrementAmount){
        $adjustment = IncrementAdjustment::with(INCREMENT_FIELD, $incrementAmount);
        $adjustment->performAdjustment();
        return $adjustment;
    }

    public function testUnsetIncremenent(){
        $this->checkValue(
            $this->createAdjustment(1),
            1,
            __FUNCTION__);
    }

    public function testSetIncremenentAtZero(){
        $this->createAdjustment(0);
        $this->checkValue(
            $this->createAdjustment(1),
            1,
            __FUNCTION__);
    }

    public function testSetIncremenentAtOne(){
        $this->createAdjustment(1);
        $this->checkValue(
            $this->createAdjustment(1),
            2,
            __FUNCTION__);
    }

    public function testUnsetIncremenentTen(){
        $this->checkValue(
            $this->createAdjustment(10),
            10,
            __FUNCTION__);
    }

    public function testSetIncremenentAtFive(){
        $this->createAdjustment(5);
        $this->checkValue(
            $this->createAdjustment(10),
            15,
            __FUNCTION__);
    }

}