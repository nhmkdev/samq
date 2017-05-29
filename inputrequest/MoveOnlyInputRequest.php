<?php

include_once dirname(__FILE__) . '/../inputrequest/MoveInputRequest.php';

class MoveOnlyInputRequest extends MoveInputRequest
{
    function __construct($question, $northResult, $westResult, $eastResult, $southResult) {
        // the responseSet is not needed for MoveInquiry
        parent::__construct($question, $northResult, $westResult, $eastResult, $southResult, NULL);
    }
}