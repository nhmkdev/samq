<?php

class Adjustment
{
    protected $variable;
    protected $newValue;

    function __construct($variable, $newValue) {
        $this->variable = $variable;
        $this->newValue = $newValue;
    }

    public function performAdjustment()
    {
        echo 'setting '.$this->variable.' to '.$this->newValue;
        $_SESSION[$this->variable] = $this->newValue;
    }
}