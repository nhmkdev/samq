<?php

include_once dirname(__FILE__).'/../core/core.php';
include_once dirname(__FILE__) . '/../response/Response.php';

class InputRequest
{
    public $text;
    public $responses;
    private $conditionalText;

    function __construct($text, $responseSet) {
        $this->text = $text;
        $this->responses = $responseSet;
    }

    public function processId($id, $samqCore)
    {
        $idx = 0;
        foreach($this->responses as $response){
            if(isset($response)) {
                $response->processId($id, $idx, $samqCore);
            }
            $idx++;
        }
    }

    public function setConditionalText($conditionalText){
        $this->conditionalText = InputRequest::getArrayFromArg($conditionalText, NULL);
        return $this;
    }

    public function render()
    {
        echo'<p>'
            .$this->text
            .$this->getConditionalText()
            .'</p>'.DBG_EOL;
        echo '<p><form action="/samq/sample/" method="POST">'.DBG_EOL;

        foreach ($this->responses as $response)
        {
            if(isset($response)) {
                $response->render();
            }
        }

        echo '</form></p>'.DBG_EOL;
    }

    protected function getConditionalText() {
        $str = '';
        if(isset($this->conditionalText)){
            foreach ($this->conditionalText as $conditionalText){
                $str = $str.$conditionalText->getConditionalText();
            }
        }
        return $str;
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