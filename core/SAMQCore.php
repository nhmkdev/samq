<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/10/2018
 * Time: 5:31 PM
 */

class SAMQCore
{
    private $requestAliases = array(); // request alias (generally a hash) -> request id (NOT yet used)
    private $requestTable = array(); // request id -> request
    private $requestCodeTable = array(); // request code -> request id
    private $responseTable = array(); // response id R + hash(requestId + index) -> response
    private $hashSalt;
    private $defaultDestinationId;
    private $postPath;

    // debug/dev functionality
    private $debug_validateRequestIds = false;
    private $debug_logAdjustments = false;

    function __construct($hashSalt, $defaultDestinationId, $postPath) {
        $this->hashSalt = $hashSalt;
        $this->defaultDestinationId = $defaultDestinationId;
        $this->postPath = $postPath;
    }

    public function addRequest($id, $request)
    {
        if(isset($this->requestTable[$id]))
        {
            echo '<br>Duplicate request being added: '.$id;
        }
        $this->requestTable[$id] = $request;
        $request->setRequestIdentifier($id);
        $request->processId($id, $this);
    }

    /**
     * @param $requestCode The code to jump to the request
     * @param $requestId The destination request identifier
     */
    public function addRequestCode($requestCode, $requestId)
    {
        $this->requestCodeTable[$requestCode] = $requestId;
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
        $requestId = $_POST[SAMQ_REQUESTID];
        $requestCodeId = $_POST[SAMQ_REQUESTCODE];

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
            // note: this is a hacky approach due to the R prefix
            if(SAMQUtils::str_startswith($destinationId, "R"))
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
                if(isset($response->requestId) &&
                    !isset($this->requestTable[$response->requestId]))
                {
                    echo '<br>Found a missing requestId:'.$response->requestId.' on Request: '.$request->getRequestIdentifier();
                    $validData = false;
                }
            }
        }

        if(!$validData){
            echo '<br>Validation Errors!';
        }

        $this->requestTable = $newTable;

        //echo "Request Table Size: ".sizeof($this->requestTable).'<br>';

        // confirm that every requestId is used in a response.
        if($this->debug_validateRequestIds)
        {
            $allRequestsIdsReferencedByResponses = array();
            foreach($this->requestTable as $key => $request)
            {
                foreach ($request->responses as $response)
                {
                    if (isset($response->requestId))
                    {
                        $allRequestsIdsReferencedByResponses[$response->requestId] = true;
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
    }

    public function hash($str) {
        return md5($this->hashSalt.$str);
    }

    private function getRequestId($requestCode) {
        if(!isset($requestCode)) {
            return NULL;
        }

        $requestCode = strtolower($requestCode);
        //var_dump($this->requestCodeTable);
        if(isset($this->requestCodeTable[$requestCode])) {
            return $this->requestCodeTable[$requestCode];
        }
        return NULL;
    }

    public function getPostPath()
    {
        return $this->postPath;
    }

    /**
     * @return bool
     */
    public function isDebugValidateRequestIds()
    {
        return $this->debug_validateRequestIds;
    }

    /**
     * @param bool $debug_validateRequestIds
     */
    public function setDebugValidateRequestIds($debug_validateRequestIds)
    {
        $this->debug_validateRequestIds = $debug_validateRequestIds;
    }

    /**
     * @return bool
     */
    public function isDebugLogAdjustments()
    {
        return $this->debug_logAdjustments;
    }

    /**
     * @param bool $debug_logAdjustments
     */
    public function setDebugLogAdjustments($debug_logAdjustments)
    {
        $this->debug_logAdjustments = $debug_logAdjustments;
    }
}