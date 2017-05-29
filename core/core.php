<?php

define('SAMQ_DESTINATION', 'destination');
define('SAMQ_REQUESTCODE', 'jumpcode');
define("DEBUG_MODE", true);

if(DEBUG_MODE)
{
    define('DBG_EOL', PHP_EOL);
}
else
{
    define('DBG_EOL', '');
}

class InvalidRequestCode
{
    public $requestCode;
    function __construct($jumpCode) {
        $this->requestCode = $jumpCode;
    }
}

class DestinationInquiry
{
    public $destinationId;
    public $destinationRequest;

    function __construct($destinationId, $destinationRequest) {
        $this->destinationId = $destinationId;
        $this->destinationRequest = $destinationRequest;
    }
}

class SAMQCore
{
    private $requestAliases = array(); // request alias (generally a hash) -> request id (NOT yet used)
    private $requestTable = array(); // request id -> request
    private $requestCodeTable = array(); // request code -> request id
    private $responseTable = array(); // response id R + hash(requestId + index) -> response
    private $hashSalt;
    private $defaultDestinationId;

    function __construct($hashSalt, $defaultDestinationId) {
        $this->hashSalt = $hashSalt;
        $this->defaultDestinationId = $defaultDestinationId;
    }

    public function addRequest($id, $request)
    {
        $this->requestTable[$id] = $request;
        $request->processId($id, $this);
    }

    public function addRequestCode($id, $requestCode)
    {
        $this->requestCodeTable[$id] = $requestCode;
    }

    public function addResponse($id, $response)
    {
        // track the responses so adjustments can be made
        $this->responseTable[$id] = $response;
    }

    public function createAlias($id) {
        // build a new alias
        $alias = $id.$this->aliasCounter;

        // assign the alias to the actual id
        $this->requestAliases[$alias] = $id;

        // bump up the counter
        $this->aliasCounter++;

        return $alias;
    }

    public function getDestinationInfo() {
        $requestCodeId = $_POST[SAMQ_REQUESTCODE];

        // check for a request code
        if(isset($requestCodeId)) {
            $requestCode = $this->getRequestCode($requestCodeId);
            if(isset($requestCode)) {
                var_dump($requestCode);
                $destinationId = $this->hash($requestCode);
                echo '....';
                var_dump($destinationId);
            }
            else{
                return new InvalidRequestCode($requestCodeId);
            }
        }
        else{
            $destinationId =  $_POST[SAMQ_DESTINATION];

            // eval if this is a response
            // note: this is a hacky approach due to the R prefix
            if(str_startswith($destinationId, "R"))
            {
                $response = $this->responseTable[$destinationId];
                $response->makeAdjustments();
                $destinationId = $this->hash($response->requestId);
            }
        }

        // if all else fails...
        if(!isset($destinationId)){
            $destinationId = $this->hash($this->defaultDestinationId);
        }

        return new DestinationInquiry($destinationId, $this->requestTable[$destinationId]);
    }

    public function initializeSequenceTable() {

        //$sequenceAliases[$id.$suffix] = $id;
        foreach($this->requestAliases as $alias => $sequence) {
            $sequenceTable[$alias] = $this->requestTable[$sequence];
        }

        $newTable = array();

        // validate and mutate the keys into hashes so the post data cannot be guessed
        $validData = true;
        foreach($this->requestTable as $key => $request)
        {
//            updateInquiryBackground($key, $inquiry);
            $newTable[$this->hash($key)] = $request;
            foreach ($request->responses as $response)
            {
                if(isset($response->sequenceResult) &&
                    !isset($this->requestTable[$response->sequenceResult]))
                {
                    echo '<br>Found a missing sequenceResult:'.$response->sequenceResult;
                    $validData = false;
                }
            }
        }

        if(!$validData){
            echo '<br>Validation Errors!';
        }

        $this->requestTable = $newTable;
    }

    public function hash($str) {
        return md5($this->hashSalt.$str);
    }

    private function getRequestCode($requestCode) {
        if(!isset($requestCode)) {
            return NULL;
        }

        $requestCode = strtolower($requestCode);

        if(isset($this->requestCodeTable[$requestCode])) {
            return $this->requestCodeTable[$requestCode];
        }
        return NULL;
    }
}

function str_startswith($source, $prefix)
{
    return strncmp($source, $prefix, strlen($prefix)) == 0;
}
