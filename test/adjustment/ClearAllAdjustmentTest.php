<?php

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
