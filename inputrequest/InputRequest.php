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

include_once dirname(__FILE__).'/../core/core.php';
include_once dirname(__FILE__) . '/../response/Response.php';

class InputRequest
{
    protected $text;
    public $responses;
    private $adjustments;
    private $postAdjustments;
    private $conditionalText;
    private $requestIdentifier;

    function __construct($text, $responses) {
        $this->text = $text;
        $this->responses = InputRequest::getArrayFromArg($responses, NULL);
    }

    public static function with($text, $responseSet){
        return new InputRequest($text, $responseSet);
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

    public function setText($newText)
    {
        $this->text = $newText;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setAdjustments($adjustments) {
        $this->adjustments = InputRequest::getArrayFromArg($adjustments, NULL);
        return $this;
    }

    public function setPostAdjustments($postAdjustments) {
        $this->postAdjustments = InputRequest::getArrayFromArg($postAdjustments, NULL);
        return $this;
    }

    public function setConditionalText($conditionalText){
        $this->conditionalText = InputRequest::getArrayFromArg($conditionalText, NULL);
        return $this;
    }

    // THIS IS OVERRIDEN IN MOVEINPUTREQUEST!! (probably need a wrapper method or something )
    public function render($samqCore)
    {
        echo'<p>'
            .$this->text
            .$this->getConditionalText()
            .'</p>'.DBG_EOL;
        echo '<p><form action="'.$samqCore->getPostPath().'" method="POST">'.DBG_EOL;

        foreach ($this->responses as $response)
        {
            if(isset($response)) {
                $response->render();
            }
        }

        echo '</form></p>'.DBG_EOL;

        $this->makePostAdjustments();
    }

    public function preProcess(){
        $this->makeAdjustments();
    }

    protected function makeAdjustments()
    {
        if(!isset($this->adjustments)) {
            return;
        }

        //var_dump($this->adjustments);

        foreach($this->adjustments as $adjustment) {
            $adjustment->performAdjustment();
        }
    }

    protected function makePostAdjustments()
    {
        if(!isset($this->postAdjustments)) {
            return;
        }

        //var_dump($this->postAdjustments);

        foreach($this->postAdjustments as $adjustment) {
            $adjustment->performAdjustment();
        }
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

    /**
     * @return mixed
     */
    public function getRequestIdentifier()
    {
        return $this->requestIdentifier;
    }

    /**
     * @param mixed $requestIdentifier
     */
    public function setRequestIdentifier($requestIdentifier)
    {
        $this->requestIdentifier = $requestIdentifier;
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