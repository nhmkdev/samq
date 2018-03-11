<?php

define('TEST_STORE', 'TEST_STORE');
define('TEST_STORE_2', 'TEST_STORE_2');
define('TEST_FIELD', 'testField');
define('TEST_VALUE', 'testValue');

new ClearAllAdjustmentTest();

class ClearAllAdjustmentTest extends TestAutoRunner
{

    function testClearAllAdjustment()
    {
        $_SESSION[TEST_STORE] = array();
        $_SESSION[TEST_STORE][TEST_FIELD] = TEST_VALUE;
        $_SESSION[TEST_STORE_2] = array();
        $_SESSION[TEST_STORE_2][TEST_FIELD] = TEST_VALUE;

        $adjustment = ClearAllAdjustment::ofStore(TEST_STORE);
        $adjustment->performAdjustment();

        verify(!isset($_SESSION[TEST_STORE]), __FUNCTION__, __FUNCTION__ . " in " . __FILE__ . " at " . __LINE__);
        verify(isset($_SESSION[TEST_STORE_2][TEST_FIELD]), __FUNCTION__, __FUNCTION__ . " in " . __FILE__ . " at " . __LINE__);
        verify($_SESSION[TEST_STORE_2][TEST_FIELD] == TEST_VALUE, __FUNCTION__, __FUNCTION__ . " in " . __FILE__ . " at " . __LINE__);
    }
}
