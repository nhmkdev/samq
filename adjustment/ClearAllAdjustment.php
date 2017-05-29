<?php

include_once dirname(__FILE__).'/Adjustment.php';

class ClearAllAdjustment extends Adjustment
{
    function __construct() {
        parent::__construct(NULL, NULL);
    }

    public function performAdjustment()
    {
        session_unset();
    }
}