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

class InputRequest
{
    /**
     * @var string
     */
    protected $text;
    /**
     * @var array Response[]
     */
    protected $responses;
    /**
     * @var array Adjustment[]
     */
    private $adjustments;
    /**
     * @var array Adjustment[]
     */
    private $postAdjustments;
    /**
     * @var ConditionalText[]
     */
    private $conditionalText;
    /**
     * @var string
     */
    private $requestIdentifier;

    /**
     * InputRequest constructor.
     * @param $text string
     * @param $responses Response[]|Response
     */
    function __construct($text, $responses) {
        $this->text = $text;
        $this->responses = SAMQUtils::getArrayFromArg($responses, NULL);
    }

    /**
     * Convenience method for constructing an InputRequest
     * @param $text string
     * @param $responses Response[]|Response
     * @return InputRequest
     */
    public static function with($text, $responses){
        return new InputRequest($text, $responses);
    }

    /**
     * Processes the id value of this InputRequest
     * @param $id string
     * @param $samqCore SAMQCore
     */
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

    /**
     * @param $newText string
     */
    public function setText($newText)
    {
        $this->text = $newText;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param $adjustments Adjustment[]|Adjustment
     * @return InputRequest
     */
    public function setAdjustments($adjustments) {
        $this->adjustments = SAMQUtils::getArrayFromArg($adjustments, NULL);
        return $this;
    }

    /**
     * @param $adjustments Adjustment[]|Adjustment
     * @return InputRequest
     */
    public function setPostAdjustments($postAdjustments) {
        $this->postAdjustments = SAMQUtils::getArrayFromArg($postAdjustments, NULL);
        return $this;
    }

    /**
     * @param $conditionalText ConditionalText[]|ConditionalText
     * @return InputRequest
     */
    public function setConditionalText($conditionalText){
        $this->conditionalText = SAMQUtils::getArrayFromArg($conditionalText, NULL);
        return $this;
    }

    /**
     * @return array Response[]
     */
    public function getResponses(){
        return $this->responses;
    }

    /**
     * @param $samqCore SAMQCore
     */
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
    }

    // NOTE: these are intentionally seperated from render so an adjustment can be used to control other
    // components of rendering or otherwise in the $_SESSION (preProcess -> render -> postProcess)

    public function preProcess(){
        $this->makeAdjustments();
    }

    public function postProcess(){
        $this->makePostAdjustments();
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

    /**
     * @return string
     */
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
     * @return string
     */
    public function getRequestIdentifier()
    {
        return $this->requestIdentifier;
    }

    /**
     * @param $requestIdentifier string
     */
    public function setRequestIdentifier($requestIdentifier)
    {
        $this->requestIdentifier = $requestIdentifier;
    }
}