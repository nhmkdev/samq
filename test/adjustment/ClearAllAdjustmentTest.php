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

define('TEST_STORE', 'TEST_STORE');
define('TEST_STORE_2', 'TEST_STORE_2');
define('TEST_FIELD', 'testField');
define('TEST_VALUE', 'testValue');

new ClearAllAdjustmentTest();

class ClearAllAdjustmentTest extends TestAutoRunner
{
    public function setupTest(){
        session_unset();
    }

    function testClearAllAdjustment(){
        Adjustment::withStore(TEST_FIELD, TEST_VALUE, TEST_STORE)->performAdjustment();
        Adjustment::withStore(TEST_FIELD, TEST_VALUE, TEST_STORE_2)->performAdjustment();

        $adjustment = ClearAllAdjustment::ofStore(TEST_STORE);
        $adjustment->performAdjustment();

        verify(!isset($_SESSION[TEST_STORE]), testId(__FILE__, __FUNCTION__), 'Clear all failed to clear.');
        verify(isset($_SESSION[TEST_STORE_2][TEST_FIELD]), testId(__FILE__, __FUNCTION__), 'Clear all affected the wrong store.');
        verify($_SESSION[TEST_STORE_2][TEST_FIELD] == TEST_VALUE, testId(__FILE__, __FUNCTION__), 'Clear all affected a value in another store.');
    }
}
