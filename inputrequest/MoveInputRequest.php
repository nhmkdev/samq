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

include_once dirname(__FILE__).'/InputRequest.php';

class MoveInputRequest extends InputRequest
{
    /**
     * @var Response
     */
    private $northResponse;
    /**
     * @var Response
     */
    private $westResponse;
    /**
     * @var Response
     */
    private $eastResponse;
    /**
     * @var Response
     */
    private $southResponse;
    /**
     * @var Response
     */
    private $commandResponse;
    /**
     * @var Response[]
     */
    private $additionalResponses;

    /**
     * MoveInputRequest constructor.
     * @param $text string
     * @param $northResult Response|string
     * @param $westResult Response|string
     * @param $eastResult Response|string
     * @param $southResult Response|string
     * @param $commandResponse Response
     * @param $additionalResponses Response[]|Response
     */
    function __construct($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse, $additionalResponses) {

        $this->northResponse = $northResult instanceof Response ? $northResult : MoveInputRequest::createMoveResponse('n', $northResult);
        $this->westResponse = $westResult instanceof Response ? $westResult : MoveInputRequest::createMoveResponse('w', $westResult);
        $this->eastResponse = $eastResult instanceof Response ? $eastResult : MoveInputRequest::createMoveResponse('e', $eastResult);
        $this->southResponse = $southResult instanceof Response ? $southResult : MoveInputRequest::createMoveResponse('s', $southResult);
        $this->commandResponse = $commandResponse;
        $this->additionalResponses = SAMQUtils::getArrayFromArg($additionalResponses, NULL);

        //var_dump($this);

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

    /**
     * @param $text string
     * @param $northResult Response|string
     * @param $westResult Response|string
     * @param $eastResult Response|string
     * @param $southResult Response|string
     * @param $commandResponse Response
     * @param $additionalResponses Response[]|Response
     * @return MoveInputRequest
     */
    public static function withAll($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse, $additionalResponses){
        return new MoveInputRequest($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse, $additionalResponses);
    }

    /**
     * @param $text string
     * @param $northResult Response|string
     * @param $westResult Response|string
     * @param $eastResult Response|string
     * @param $southResult Response|string
     * @param $commandResponse Response
     * @return MoveInputRequest
     */
    public static function withCommand($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse){
        return new MoveInputRequest($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse, NULL);
    }

    /**
     * @param $text string
     * @param $northResult Response|string
     * @param $westResult Response|string
     * @param $eastResult Response|string
     * @param $southResult Response|string
     * @param $additionalResponses Response[]|Response
     * @return MoveInputRequest
     */
    public static function withAdditionalResponses($text, $northResult, $westResult, $eastResult, $southResult, $additionalResponses){
        return new MoveInputRequest($text, $northResult, $westResult, $eastResult, $southResult, NULL, $additionalResponses);
    }

    /**
     * MoveOnlyInputRequest constructor.
     * @param $text string
     * @param $northResult Response|string
     * @param $westResult Response|string
     * @param $eastResult Response|string
     * @param $southResult Response|string
     * @return MoveInputRequest
     */
    public static function withDirectionsOnly($text, $northResult, $westResult, $eastResult, $southResult) {
        // the responseSet is not needed for MoveInquiry
        return new MoveInputRequest($text, $northResult, $westResult, $eastResult, $southResult, NULL, NULL);
    }

    private function updateResponses()
    {
        $updatedResponses = array(
            $this->northResponse,
            $this->westResponse,
            $this->eastResponse,
            $this->southResponse
        );
        if(isset($this->commandResponse)) {
            array_push($updatedResponses, $this->commandResponse);
        }
        if(isset($this->additionalResponses)) {
            $updatedResponses = array_merge($updatedResponses, $this->additionalResponses);
        }
        // TODO: decide where to do validation like this... convenient here
        /*
        foreach ($updatedResponses as $response) {
            if(!$response instanceof Response){
                echo 'The response is not the expected type';
                debug_print_backtrace();
            }
        }*/

        $this->responses = $updatedResponses;
    }

    /**
     * @return Response
     */
    public function getNorthResponse()
    {
        return $this->northResponse;
    }

    /**
     * @return Response
     */
    public function getWestResponse()
    {
        return $this->westResponse;
    }

    /**
     * @return Response
     */
    public function getEastResponse()
    {
        return $this->eastResponse;
    }

    /**
     * @return Response
     */
    public function getSouthResponse()
    {
        return $this->southResponse;
    }

    /**
     * @param $response Response
     * @return MoveInputRequest
     */
    public function setNorthResponse($response)
    {
        $this->northResponse = $response;
        $this->updateResponses();
        return $this;
    }

    /**
     * @param $response Response
     * @return MoveInputRequest
     */
    public function setWestResponse($response)
    {
        $this->westResponse = $response;
        $this->updateResponses();
        return $this;
    }

    /**
     * @param $response Response
     * @return MoveInputRequest
     */
    public function setEastResponse($response)
    {
        $this->eastResponse = $response;
        $this->updateResponses();
        return $this;
    }

    /**
     * @param $response Response
     * @return MoveInputRequest
     */
    public function setSouthResponse($response)
    {
        $this->southResponse = $response;
        $this->updateResponses();
        return $this;
    }

    /**
     * @param $response Response
     * @return MoveInputRequest
     */
    public function setCommandResponse($response)
    {
        $this->commandResponse = $response;
        $this->updateResponses();
        return $this;
    }

    /**
     * @param $response Response[]|Response
     * @return MoveInputRequest
     */
    public function setAdditionalResponse($responses)
    {
        $this->additionalResponses = SAMQUtils::getArrayFromArg($responses, NULL);
        $this->updateResponses();
        return $this;
    }

    /**
     * @param $direction string
     * @param $requestId string
     * @return Response
     */
    public static function createMoveResponse($direction, $requestId){
        $text = 'unknown';
        switch($direction)
        {
            case 'n':
                $text = 'North';
                break;
            case 's':
                $text = 'South';
                break;
            case 'w':
                $text = 'West';
                break;
            case 'e':
                $text = 'East';
                break;
        }
        return new Response('Move '.$text, $requestId);
    }

    /**
     * @param $samqCore SAMQCore
     */
    public function render($samqCore)
    {
        echo '<p><form action="'.$samqCore->getPostPath().'" method="POST">'.DBG_EOL;

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

        // originally the conditional text went into the table... messes up the buttons badly
        /*
        echo '<tr>'.DBG_EOL;
        echo '<td colspan="4">'
            .$this->text
            .$this->getConditionalText()
            .'</td>'.DBG_EOL;
        echo '</tr>'.DBG_EOL;
*/
        echo '</table>'.DBG_EOL;
        echo $this->text
        .$this->getConditionalText();

        echo '<br>'.DBG_EOL;
        if(isset($this->additionalResponses)) {
            foreach ($this->additionalResponses as $response) {
                echo $this->getChoiceButton($response, NULL).' ';
            }
        }
        echo '<br>'.DBG_EOL;
        echo '</form></p>'.DBG_EOL;
    }

    /**
     * @param $responseObject Response
     * @param $suffix string
     * @return string
     */
    public function getChoiceButton($responseObject, $suffix) {
        if(!isset($responseObject))
        {
            return '';
        }

        $state = $responseObject->getResponseState();

        /*
        echo '<br>';
        var_dump($responseObject);
        echo '<br>'.$state;
        */

        $rendered = true;
        $enabled = true;

        switch($state){
            case ResponseState::Hidden:
                $rendered = false;
                break;
            case ResponseState::Enabled:
                // movement buttons use special settings to indicate the direction cannot be taken
                $requestId = $responseObject->getRequestId();
                $enabled = isset($requestId);
                break;
            case ResponseState::Disabled:
                $enabled = false;
                break;
        }

        //echo $responseObject->text.'::'.$responseObject->requestId.'::'.$state.'<br>';

        if($rendered) {
           return '<button type="submit" width="60" name="' . SAMQ_DESTINATION . '" value="'
                .$responseObject->getPostValue()
                .'"'
                .($enabled ? '' : ' disabled')
                .'>'
                .$responseObject->getText()
                .'</button>'.$suffix.DBG_EOL;
        }

        return '';
    }
}