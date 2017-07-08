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
include_once dirname(__FILE__).'/InputRequest.php';

class MoveInputRequest extends InputRequest
{
    private $northResponse;
    private $westResponse;
    private $eastResponse;
    private $southResponse;
    private $commandResponse;
    private $additionalResponses;

    function __construct($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse, $additionalResponses) {
        $this->northResponse = MoveInputRequest::createMoveResponse("Move North", $northResult);
        $this->westResponse = MoveInputRequest::createMoveResponse("Move West", $westResult);
        $this->eastResponse = MoveInputRequest::createMoveResponse("Move East", $eastResult);
        $this->southResponse = MoveInputRequest::createMoveResponse("Move South", $southResult);
        $this->commandResponse = $commandResponse;
        $this->additionalResponses = MoveInputRequest::getArrayFromArg($additionalResponses, NULL);

        $constructorResponses = array(
            $this->northResponse,
            $this->westResponse,
            $this->eastResponse,
            $this->southResponse
        );
        if(isset($this->commandResponse)) {
            array_push($constructorResponses, $this->commandResponse);
        }
        if(isset($this->additionalResponses)) {
            $constructorResponses = array_merge($constructorResponses, $this->additionalResponses);
        }
        //var_dump($this->additionalResponses);

        parent::__construct($text, $constructorResponses);
    }

    public static function withCommand($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse){
        return new MoveInputRequest($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse, NULL);
    }

    public static function withAdditionalResponses($text, $northResult, $westResult, $eastResult, $southResult, $additionalResponses){
        return new MoveInputRequest($text, $northResult, $westResult, $eastResult, $southResult, NULL, $additionalResponses);
    }

    private static function createMoveResponse($text, $requestId){
        return new Response($text, $requestId);
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
        echo '<p><form action="/samq/sample/" method="POST">'.DBG_EOL;

        // Build a table with 4 columns, the 3x3 grid holds the directions and the command.
        // the last row contains the request
        echo '<table border="0">'.DBG_EOL;

        $moveButtonSuffix = '<br><br>';

        // TODO: style should be driven elsewhere...
        echo '<tr>'.DBG_EOL;
        echo '<td style="width:100px"></td>'.DBG_EOL;
        echo '<td style="width:100px;text-align:center">'.$this->getChoiceButton($this->northResponse, $moveButtonSuffix).'</td>'.DBG_EOL;
        echo '<td style="width:100px"></td>'.DBG_EOL;
        echo '<td></td>'.DBG_EOL;
        echo '</tr>'.DBG_EOL;

        echo '<tr>'.DBG_EOL;
        echo '<td style="text-align:center">'.$this->getChoiceButton($this->westResponse, $moveButtonSuffix).'</td>'.DBG_EOL;
        echo '<td style="text-align:center">'.$this->getChoiceButton($this->commandResponse, $moveButtonSuffix).'</td>'.DBG_EOL;
        echo '<td style="text-align:center">'.$this->getChoiceButton($this->eastResponse, $moveButtonSuffix).'</td>'.DBG_EOL;
        echo '<td></td>'.DBG_EOL;
        echo '</tr>'.DBG_EOL;

        echo '<tr>'.DBG_EOL;
        echo '<td></td>'.DBG_EOL;
        echo '<td style="text-align:center">'.$this->getChoiceButton($this->southResponse, $moveButtonSuffix).'</td>'.DBG_EOL;
        echo '<td></td>'.DBG_EOL;
        echo '<td></td>'.DBG_EOL;
        echo '</tr>'.DBG_EOL;

        echo '<tr>'.DBG_EOL;
        echo '<td colspan="4">'
            .$this->text
            .$this->getConditionalText()
            .'</td>'.DBG_EOL;
        echo '</tr>'.DBG_EOL;

        echo '</table>'.DBG_EOL;
        echo '<br>'.DBG_EOL;
        if(isset($this->additionalResponses)) {
            foreach ($this->additionalResponses as $response) {
                echo $this->getChoiceButton($response, NULL).' ';
            }
        }
        echo '<br>'.DBG_EOL;
        echo '</form></p>'.DBG_EOL;
    }

    public function getChoiceButton($responseObject, $suffix) {
        if(!isset($responseObject))
        {
            return '';
        }

        $state = $responseObject->getResponseState();
/*
        echo '<br>';
        var_dump($responseObject);
        echo '<br>';
*/
        $rendered = true;
        $enabled = true;

        switch($state){
            case ResponseState::Hidden:
                $rendered = false;
                break;
            case ResponseState::Enabled:
                // movement buttons use special settings to indicate the direction cannot be taken
                $enabled = isset($responseObject->requestId);
                break;
            case ResponseState::Disabled:
                $enabled = false;
                break;
        }

        //echo $responseObject->text.'::'.$responseObject->requestId.'::'.$state;

        if($rendered) {
           return '<button type="submit" width="60" name="' . SAMQ_DESTINATION . '" value="'
                .$responseObject->getPostValue()
                .'"'
                .($enabled ? '' : ' disabled')
                .'>'
                .$responseObject->text
                .'</button>'.$suffix.DBG_EOL;
        }
    }
}