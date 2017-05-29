<?php

include_once dirname(__FILE__).'/../core/core.php';
include_once dirname(__FILE__).'/InputRequest.php';

class MoveInputRequest extends InputRequest
{
    public $northResponse;
    public $westResponse;
    public $eastResponse;
    public $southResponse;
    public $commandResponse;

    function __construct($text, $northResult, $westResult, $eastResult, $southResult, $commandResponse) {
        $this->northResponse = MoveInputRequest::createMoveResponse("Move North", $northResult);
        $this->westResponse = MoveInputRequest::createMoveResponse("Move West", $westResult);
        $this->eastResponse = MoveInputRequest::createMoveResponse("Move East", $eastResult);
        $this->southResponse = MoveInputRequest::createMoveResponse("Move South", $southResult);
        $this->commandResponse = $commandResponse;
        // TODO: multiple command responses?

        parent::__construct(
            $text,
            array(
                $this->northResponse,
                $this->westResponse,
                $this->eastResponse,
                $this->southResponse,
                $this->commandResponse));
    }

    private static function createMoveResponse($text, $requestId){
        return new Response($text, $requestId);
    }

    public function render()
    {
        echo '<p><form action="/samq/sample/" method="POST">'.DBG_EOL;

        // Build a table with 4 columns, the 3x3 grid holds the directions and the command.
        // the last row contains the request
        echo '<table border="0">'.DBG_EOL;

        // TODO: style should be driven elsewhere...
        echo '<tr>'.DBG_EOL;
        echo '<td style="width:100px"></td>'.DBG_EOL;
        echo '<td style="width:100px;text-align:center">'.$this->getChoiceButton($this->northResponse).'</td>'.DBG_EOL;
        echo '<td style="width:100px"></td>'.DBG_EOL;
        echo '<td></td>'.DBG_EOL;
        echo '</tr>'.DBG_EOL;

        echo '<tr>'.DBG_EOL;
        echo '<td style="text-align:center">'.$this->getChoiceButton($this->westResponse).'</td>'.DBG_EOL;
        echo '<td style="text-align:center">'.$this->getChoiceButton($this->commandResponse).'</td>'.DBG_EOL;
        echo '<td style="text-align:center">'.$this->getChoiceButton($this->eastResponse).'</td>'.DBG_EOL;
        echo '<td></td>'.DBG_EOL;
        echo '</tr>'.DBG_EOL;

        echo '<tr>'.DBG_EOL;
        echo '<td></td>'.DBG_EOL;
        echo '<td style="text-align:center">'.$this->getChoiceButton($this->southResponse).'</td>'.DBG_EOL;
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
        echo '</form></p>'.DBG_EOL;
    }

    public function getChoiceButton($responseObject)
    {
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

        if($rendered) {
           return '<button type="submit" width="60" name="' . SAMQ_DESTINATION . '" value="'
                .$responseObject->getPostValue()
                .'"'
                .($enabled ? '' : ' disabled')
                .'>'
                .$responseObject->text
                .'</button><br><br>' . DBG_EOL;
        }
    }
}