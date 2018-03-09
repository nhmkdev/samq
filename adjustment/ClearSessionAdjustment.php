<?php

include_once dirname(__FILE__).'/Adjustment.php';

class ClearSessionAdjustment extends Adjustment
{
    function __construct() {
        parent::__construct(NULL, NULL);
    }

    public function performAdjustment()
    {
        $this->logAdjusment();
        session_unset();
    }

    protected function logAdjusment()
    {
        global $samqCore;
        if($samqCore->isDebugLogAdjustments())
        {
            echo 'Clearing Entire Session!<br>';
        }
    }
}