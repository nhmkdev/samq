<?php

////////////////////////////////////////////////////////////////////////////////
// The MIT License (MIT)
//
// Copyright (c) 2017 Tim Stair
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

include_once dirname(__FILE__) . '/../core/core.php';
include_once dirname(__FILE__) . '/ResponseState.php';
include_once dirname(__FILE__) . '/../condition/iCondition.php';

class Response
{
    public $text;
    public $requestId;

    private $adjustments;
    private $disabledConditions;
    private $enabledConditions;
    private $hiddenConditions;

    private $id;
    private $postValue;

    // todo text based on conditions... (seems like that's just another response...)

    function __construct($text, $requestId) {
        $this->text = $text;
        $this->requestId = $requestId;
    }

    public static function with($text, $requestId) {
        return new Response($text, $requestId);
    }

    public function setAdjustments($adjustments) {
        $this->adjustments = Response::getArrayFromArg($adjustments, NULL);
        return $this;
    }

    public function setHiddenConditions($conditions){
        $this->hiddenConditions = Response::getArrayFromArg($conditions, NULL);
        return $this;
    }

    public function setDisabledConditions($conditions){
        $this->disabledConditions = Response::getArrayFromArg($conditions, NULL);
        return $this;
    }

    public function setEnabledConditions($conditions){
        $this->enabledConditions = Response::getArrayFromArg($conditions, NULL);
        return $this;
    }

    private static function getArrayFromArg($arg, $default) {
        if(isset($arg)) {
            if (is_array($arg)) {
                return $arg;
            }
            else{
                return array($arg);
            }
        }
        return $default;
    }

    public function render()
    {
        $state = $this->getResponseState();

        $rendered = true;
        $enabled = true;

        switch($state){
            case ResponseState::Hidden:
                $rendered = false;
                break;
            case ResponseState::Disabled:
                $enabled = false;
                break;
        }

        if($rendered) {
            echo '<button type="submit" name="' . SAMQ_DESTINATION . '" value="'
                .$this->getPostValue()
                .'"'
                .($enabled ? '' : ' disabled')
                .'>'
                .$this->text
                .'</button><br><br>'
                .DBG_EOL;
        }
    }

    public function getPostValue()
    {
        return $this->postValue;
    }

    public function processId($requestId, $idx, $samqCore)
    {
        $this->id = $requestId.$idx;

        //echo 'processId: '.$requestId.' :: '.$idx.'<br>';

        $this->postValue =
            'R'.
            $samqCore->hash($requestId.$idx);
        $samqCore->addResponse($this->postValue, $this);
    }

    public function getResponseState() {
        // hidden/disabled are ANY conditions pass
        if(Response::anyConditionsPass($this->hiddenConditions, false)){
            return ResponseState::Hidden;
        }
        elseif(Response::anyConditionsPass($this->disabledConditions, false)){
            return ResponseState::Disabled;
        }
        // enabled is ALL conditions must pass
        elseif(Response::allConditionsPass($this->enabledConditions, true)){
            return ResponseState::Enabled;
        }
        return ResponseState::Hidden;
    }

    private static function allConditionsPass($conditions, $default){
        if(isset($conditions))
        {
            foreach ($conditions as $condition){
                if(!$condition->isMet()){
                    return false;
                }
            }
        }
        return $default;
    }

    private static function anyConditionsPass($conditions, $default){
        if(isset($conditions))
        {
            foreach ($conditions as $condition){
                if($condition->isMet()){
                    return true;
                }
            }
        }
        return $default;
    }

    public function makeAdjustments()
    {
        if(!isset($this->adjustments)) {
            return;
        }

        foreach($this->adjustments as $adjustment) {
            $adjustment->performAdjustment();
        }
    }
}