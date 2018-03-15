<?php

////////////////////////////////////////////////////////////////////////////////
// The MIT License (MIT)
//
// Copyright (c) 2018 Tim Stair
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.
////////////////////////////////////////////////////////////////////////////////

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