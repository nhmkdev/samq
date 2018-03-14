<?php

new ClearSessionAdjustmentTest();

class ClearSessionAdjustmentTest extends TestAutoRunner
{
    public function setupTest(){
        session_unset();
    }

    function testClearSession(){
        $_SESSION['test'] = 'testValue';
        $adjustment = new ClearSessionAdjustment();
        $adjustment->performAdjustment();
        verify(!isset($_SESSION['test']), testId(__FILE__, __FUNCTION__), 'Session clear failed.');
    }
}
