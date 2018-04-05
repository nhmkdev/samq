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

class SAMQCore
{
    /**
     * @var array InputRequest[]
     */
    private $requestTable = array(); // request id -> request
    /**
     * @var array string[]
     */
    private $requestCodeTable = array(); // request code -> request id
    /**
     * @var array Response[]
     */
    private $responseTable = array(); // response id R + hash(requestId + index) -> response
    /**
     * @var string
     */
    private $hashSalt;
    /**
     * @var string
     */
    private $defaultDestinationId;
    /**
     * @var string
     */
    private $postPath;

    /**
     * SAMQCore constructor.
     * @param $hashSalt string
     * @param $defaultDestinationId string
     * @param $postPath string
     */
    function __construct($hashSalt, $defaultDestinationId, $postPath) {
        $this->hashSalt = $hashSalt;
        $this->defaultDestinationId = $defaultDestinationId;
        $this->postPath = $postPath;
    }

    /**
     * Adds a request to the engine and processes the id.
     * @param $id string
     * @param $request InputRequest
     */
    public function addRequest($id, $request)
    {
        if(SAMQSettings::isLogDuplicateRequests() &&
            isset($this->requestTable[$id])) {
            echo '<br>Duplicate request being added: '.$id;
        }
        $this->requestTable[$id] = $request;
        $request->setRequestIdentifier($id);
        $request->processId($id, $this);
    }

    /**
     * Adds a request code
     * @param $requestCode string code to jump to the request
     * @param $requestId string destination request identifier
     */
    public function addRequestCode($requestCode, $requestId) {
        $this->requestCodeTable[$requestCode] = $requestId;
    }

    // TODO: this method is a bit weird, investigate a better way to perform this op
    /**
     * @param $id string
     * @param $response Response
     */
    public function addResponse($id, $response) {
        // track the responses so adjustments can be made
        $this->responseTable[$id] = $response;
    }

    /**
     * Get information about the destination InputRequest
     * @return DestinationInquiry|InvalidRequestCode
     */
    public function getDestinationInfo() {
        $requestId = $_POST[SAMQ_REQUESTID];
        $requestCodeId = $_POST[SAMQ_REQUESTCODE];

        $this->logRequestEval('POST '
            .SAMQ_REQUESTID.':['.$requestId.'] '
            .SAMQ_REQUESTCODE.':['.$requestCodeId.'] '
            .SAMQ_DESTINATION.':['.$_POST[SAMQ_DESTINATION].']');

        // direct request identifier
        if(isset($requestId))
        {
            $destinationId = $this->hash($requestId);
        }

        // check for a request code
        else if(isset($requestCodeId)) {
            $requestCode = $this->getRequestId($requestCodeId);
            if(isset($requestCode)) {
                //var_dump($requestCode);
                $destinationId = $this->hash($requestCode);
                //echo '....';
                //var_dump($destinationId);
            }
            else{
                return new InvalidRequestCode($requestCodeId);
            }
        }
        // standard destination
        else{
            $destinationId =  $_POST[SAMQ_DESTINATION];

            // eval if this is a response
            // todo/note: this is a hacky approach due to the R prefix
            if(SAMQUtils::str_startswith($destinationId, "R"))
            {
                if(isset($this->responseTable[$destinationId])){
                    $response = $this->responseTable[$destinationId];
                    $response->makeAdjustments();
                    $destinationId = $this->hash($response->getRequestId());
                }
                else{
                    // always log this
                    Logger::error('Failed to find response: '.$destinationId);
                }
            }
        }

        // if all else fails...
        if(!isset($destinationId)){
            $destinationId = $this->hash($this->defaultDestinationId);
        }

        return new DestinationInquiry($destinationId, $this->requestTable[$destinationId]);
    }

    private function hashRequestTable(){
        if(!SAMQSettings::isEnableObfuscationHash()) return;

        $hashedRequestTable = array();

        // validate and mutate the keys into hashes so the post data cannot be (easily) guessed
        $validData = true;
        foreach($this->requestTable as $key => $request)
        {
            $hashedRequestTable[$this->hash($key)] = $request;
            if(SAMQSettings::isLogMissingRequestIds()) {
                foreach ($request->getResponses() as $response) {
                    if (isset($response->requestId) &&
                        !isset($this->requestTable[$response->requestId])) {
                        echo '<br>Found a missing requestId:' . $response->requestId . ' on Request: ' . $request->getRequestIdentifier();
                        $validData = false;
                    }
                }
            }
        }

        if(!$validData){
            echo '<br>Validation Errors!';
        }

        $this->requestTable = $hashedRequestTable;
    }

    private function checkForUnreferencedRequestIds(){
        if(!SAMQSettings::isLogUnreferencedRequestIds()) return;

        // confirm that every requestId is used in a response.
        $allRequestsIdsReferencedByResponses = array();
        foreach($this->requestTable as $key => $request)
        {
            foreach ($request->getResponses() as $response)
            {
                $requestId = $response->getRequestId();
                if (isset($requestId))
                {
                    $allRequestsIdsReferencedByResponses[$requestId] = true;
                }
            }
        }
        foreach($this->requestTable as $key => $request)
        {
            if(!isset($allRequestsIdsReferencedByResponses[$request->getRequestIdentifier()]))
            {
                echo '<br>Found an unreferenced requestId:['.$request->getRequestIdentifier().']<br>';
            }
        }
    }

    public function initializeSequenceTable(){
        $this->hashRequestTable();
        $this->checkForUnreferencedRequestIds();
        if(SAMQSettings::isLogStatistics()){
            Logger::info("Request Table Size: ".sizeof($this->requestTable));
        }
    }

    private function logRequestEval($msg){
        if(SAMQSettings::isLogRequestEvaluation()) Logger::info($msg);
    }

    public function hash($str){
        return SAMQSettings::isEnableObfuscationHash()
            ? md5($this->hashSalt . $str)
            : $str;
    }

    private function getRequestId($requestCode)
    {
        if (!isset($requestCode)){
            return NULL;
        }

        $requestCode = strtolower($requestCode);
        if(isset($this->requestCodeTable[$requestCode])) {
            return $this->requestCodeTable[$requestCode];
        }
        return NULL;
    }

    public function getPostPath() {
        return $this->postPath;
    }
}