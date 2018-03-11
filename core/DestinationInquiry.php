<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/10/2018
 * Time: 5:33 PM
 */


// highly questionable on the location of this file
class DestinationInquiry
{
    public $destinationId;
    public $destinationRequest;

    function __construct($destinationId, $destinationRequest) {
        $this->destinationId = $destinationId;
        $this->destinationRequest = $destinationRequest;
    }
}