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