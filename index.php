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

// Start the session (if needed)
session_start();

define("SAMQ_POST_PATH", substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])).'/');
define("VALIDATE_REQUEST_IDS", false);

include_once dirname(__FILE__) . '/core/core_include.php';

SAMQSettings::setEnableObfuscationHash(false);
//SAMQSettings::enableFullDebug();

$samqCore = new SAMQCore('salty!', 'sample_01', SAMQ_POST_PATH);

// jump codes
define("REQUEST_CODE", 'this is a code');

// add the jump codes
$samqCore->addRequestCode(REQUEST_CODE, 'sample_01');

// include all the php files that add requests to SAMQ
include_once dirname(__FILE__) . '/sample_input_requests.php';

define('FONT_COLOR', 'FFFFFF');

function render()
{
    global $samqCore;
    $destinationInfo = $samqCore->getDestinationInfo();

    echo '<!DOCTYPE html>'.DBG_EOL
        .'<html>'.DBG_EOL
        .'<head>'.DBG_EOL
        .'<title>SAMQ Sample</title>'.DBG_EOL;


    if(!($destinationInfo instanceof InvalidRequestCode))
    {
        $destinationInfo->getDestinationRequest()->preProcess();
    }

    echo '</head>'.DBG_EOL;

    echo '<body><hr>'.DBG_EOL;

    if(!($destinationInfo instanceof InvalidRequestCode))
    {
        $destinationInfo->getDestinationRequest()->render($samqCore);
        if(VALIDATE_REQUEST_IDS) {
            echo $destinationInfo->getDestinationRequest()->getRequestIdentifier() . '<br>';
        }
        $destinationInfo->getDestinationRequest()->postProcess();
    }
    else
    {
        echo 'Invalid request code: '.$destinationInfo->getRequestCode();
    }

    $formPostAction = '<form action="'.$samqCore->getPostPath().'" method="POST">';
    
    echo '<hr><p>'.$formPostAction.DBG_EOL
        .'Jump Code: <input type="text" size="20" name="'.SAMQ_REQUESTCODE.'">&nbsp;&nbsp;&nbsp;'.DBG_EOL
        .'<input type="submit" value="Submit"></form></p>'.DBG_EOL;

    if(VALIDATE_REQUEST_IDS) {
        echo '<hr><p>'.$formPostAction.DBG_EOL
            . 'Request Code: <input type="text" size="20" name="'.SAMQ_REQUESTID.'">&nbsp;&nbsp;&nbsp;'.DBG_EOL
            . '<input type="submit" value="Submit"></form></p>'.DBG_EOL;
    }

    echo '<p><span class="standard"><a href='.$samqCore->getPostPath().'>Start Over</a></span></p>'.DBG_EOL;

    if(VALIDATE_REQUEST_IDS) {
        var_dump($_SESSION);
    }

    echo '</body>'.DBG_EOL;
}

$samqCore->initializeSequenceTable();

render();
