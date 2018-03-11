<?php

new ClearSessionAdjustmentTest();

class ClearSessionAdjustmentTest extends TestAutoRunner
{
    function testClearSession()
    {
        $_SESSION['test'] = 'testValue';
        $adjustment = new ClearSessionAdjustment();
        $adjustment->performAdjustment();
        verify(!isset($_SESSION['test']), __FUNCTION__, 'Session clear failed.' . __FUNCTION__ . " in " . __FILE__ . " at " . __LINE__);
    }
}
