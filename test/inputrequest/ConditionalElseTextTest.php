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
        return ConditionalText::withElse(CONDITIONAL_ELSE_TEXT_TEXT, CONDITIONAL_ELSE_TEXT_ELSE_TEXT, $conditions);
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