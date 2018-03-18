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

include_once dirname(__FILE__).'/Adjustment.php';

class IncrementAdjustment extends Adjustment
{
    private $incrementAmount;
    private $maximum;

    /**
     * IncrementAdjustment constructor.
     * @param string $variable
     * @param mixed $incrementAmount
     */
    function __construct($variable, $incrementAmount) {
        parent::__construct($variable, NULL);
        $this->incrementAmount = $incrementAmount;
    }

    /**
     * Convenience method for constructing an IncrementAdjustment
     * @param $variable string
     * @param $incrementAmount int
     * @return IncrementAdjustment
     */
    public static function with($variable, $incrementAmount)
    {
        return new IncrementAdjustment($variable, $incrementAmount);
    }

    /**
     * Convenience method for constructing an IncrementAdjustment
     * @param $variable string
     * @param $incrementAmount int
     * @param $maximum int
     * @return IncrementAdjustment
     */
    public static function withMax($variable, $incrementAmount, $maximum)
    {
        $adjustment = new IncrementAdjustment($variable, $incrementAmount);
        $adjustment->maximum = $maximum;
        return $adjustment;
    }

    public function performAdjustment()
    {
        $this->newValue = $this->incrementAmount;
        $this->initSessionStore();
        // get the prior value and attempt to increment
        if(isset($_SESSION[$this->session_store][$this->variable]))
        {
            $this->newValue = $_SESSION[$this->session_store][$this->variable] + $this->incrementAmount;
            if(isset($this->maximum) && $this->newValue > $this->maximum){
                $this->newValue = $this->maximum;
            }
        }
        parent::performAdjustment();
    }
}