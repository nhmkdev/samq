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