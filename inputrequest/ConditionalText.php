<?php

include_once dirname(__FILE__) . '/../core/core.php';
include_once dirname(__FILE__) . '/../condition/iCondition.php';

class ConditionalText
{
    public $text;
    private $conditions;

    function __construct($text, $conditions) {
        $this->text = $text;
        $this->conditions = ConditionalText::getArrayFromArg($conditions, NULL);
    }

    public function getConditionalText(){
        if(isset($this->conditions)){
            foreach($this->conditions as $condition){
                if(!$condition->isMet()){
                    return '';
                }
            }
        }
        return $this->text;
    }

    // TODO: this is duplicated now
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
}