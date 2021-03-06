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

class Adjustment
{
    protected $variable;
    protected $newValue;
    protected $session_store = ADJUSTMENT_DEFAULT_STORE;

    /**
     * Adjustment constructor.
     * @param $variable string The variable name
     * @param $newValue mixed The value to set
     */
    function __construct($variable, $newValue) {
        $this->variable = $variable;
        $this->newValue = $newValue;
    }

    /**
     * Convenience method for constructing an Adjustment
     * @param $variable string The variable name
     * @param $newValue mixed The value to set
     * @return Adjustment
     */
    public static function with($variable, $newValue)
    {
        return new Adjustment($variable, $newValue);
    }

    /**
     * Convenience method for constructing an Adjustment
     * @param $variable string The variable name
     * @param $newValue mixed The value to set
     * @param $session_store string The name of the store to make the adjustment in
     * @return Adjustment
     */
    public static function withStore($variable, $newValue, $session_store)
    {
        $adjustment = new Adjustment($variable, $newValue);
        $adjustment->session_store = $session_store;
        return $adjustment;
    }

    public function performAdjustment()
    {
        $this->logAdjusment();
        $this->initSessionStore();
        $_SESSION[$this->session_store][$this->variable] = $this->newValue;
    }

    /**
     * @param $session_store string The session store to use with this adjustment
     * @return $this Adjustment
     */
    public function setStore($session_store)
    {
        $this->session_store = $session_store;
        return $this;
    }

    protected function logAdjusment()
    {
        if(SAMQSettings::isLogAdjustments())
        {
            echo 'Setting ['.$this->session_store.'] '.$this->variable.' to '.$this->newValue.'<br>';
        }
    }

    protected function initSessionStore()
    {
        if(!isset($_SESSION[$this->session_store]))
        {
            $_SESSION[$this->session_store] = array();
        }
    }
}