<?php

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

    // todo text based on conditions...

    function __construct($text, $requestId) {
        $this->text = $text;
        $this->requestId = $requestId;
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