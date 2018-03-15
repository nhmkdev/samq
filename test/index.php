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

// Start the session
session_start();

// load the engine
include_once dirname(__FILE__) . '/../core/core_include.php';

// pre-configure some test stuff
$failCount = 0;
$passCount = 0;

$samqCore = new SAMQCore('salty!',
    /*'intro_start_00'*/'intro_start_00'
    , SAMQ_POST_PATH);

// Test infrastructure
include_once dirname(__FILE__) . '/Test.php';
include_once dirname(__FILE__) . '/TestAutoRunner.php';

// TESTS!
include_once dirname(__FILE__) . '/adjustment/AdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/ClearSessionAdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/ClearAllAdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/IncrementAdjustmentTest.php';
include_once dirname(__FILE__) . '/condition/EqualConditionTest.php';
include_once dirname(__FILE__) . '/condition/GreaterOrEqualConditionTest.php';
include_once dirname(__FILE__) . '/condition/LessOrEqualConditionTest.php';
include_once dirname(__FILE__) . '/condition/NotEqualConditionTest.php';
include_once dirname(__FILE__) . '/core/CoreTest.php';
include_once dirname(__FILE__) . '/inputrequest/ConditionalElseTextTest.php';
include_once dirname(__FILE__) . '/inputrequest/ConditionalTextTest.php';
include_once dirname(__FILE__) . '/inputrequest/InputRequestTest.php';
include_once dirname(__FILE__) . '/inputrequest/MapUtilsTest.php';
include_once dirname(__FILE__) . '/response/ResponseTest.php';

// log results
if($failCount > 0){
    echo '<hr>ERRORS: '.$failCount.'<br><hr>';
}
else{
    echo '<hr>All passed: '.$passCount.'<br><hr>';
}