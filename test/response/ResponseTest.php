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

define('PASS_CONDITION_ONE', 'PASS_CONDITION_ONE');
define('PASS_CONDITION_ONE_VALUE', '1');
define('FAIL_CONDITION_ONE_VALUE', '0');

new ResponseTest();

class ResponseTest extends TestAutoRunner
{

    function setupTest(){
        $this->adjust(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE);
    }

    function adjust($name, $value){
        Adjustment::with($name, $value)->performAdjustment();
    }

    function getResponse(){
        return Response::with('', NULL);
    }

    function checkState($response, $expectedState, $function){
        $responseState = $response->getResponseState();
        verify($responseState == $expectedState, $function,
            'Response state:'.$responseState.' should be '.$expectedState);
    }

////// Hidden Tests

    function testResponseStateHidden_Any(){
        $response = $this->getResponse()
            ->setHiddenOnAnyConditions(EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE));
        $this->checkState($response, ResponseState::Hidden, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateHidden_Any_Mixed(){
        $response = $this->getResponse()
            ->setHiddenOnAnyConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, FAIL_CONDITION_ONE_VALUE)
            ]);
        $this->checkState($response, ResponseState::Hidden, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateHidden_All(){
        $response = $this->getResponse()
            ->setHiddenOnAllConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE)
            ]);
        $this->checkState($response, ResponseState::Hidden, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateHidden_All_Mixed(){
        $response = $this->getResponse()
            ->setHiddenOnAllConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, FAIL_CONDITION_ONE_VALUE)
            ]);
        $this->checkState($response, ResponseState::Enabled, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateDisabled_HiddenAll_Mixed_DefaultStateDisabled(){
        $response = $this->getResponse()
            ->setHiddenOnAllConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, FAIL_CONDITION_ONE_VALUE)
            ])
            ->setDefaultState(ResponseState::Disabled);
        $this->checkState($response, ResponseState::Disabled, testId(__FILE__, __FUNCTION__));
    }

////// Enabled Tests

    function testResponseStateEnabled_Any(){
        $response = $this->getResponse()
            ->setEnabledOnAnyConditions(EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE))
            ->setDefaultState(ResponseState::Hidden);
        $this->checkState($response, ResponseState::Enabled, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateEnabled_Any_Mixed(){
        $response = $this->getResponse()
            ->setEnabledOnAnyConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, FAIL_CONDITION_ONE_VALUE)
            ])
            ->setDefaultState(ResponseState::Hidden);
        $this->checkState($response, ResponseState::Enabled, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateEnabled_All(){
        $response = $this->getResponse()
            ->setEnabledOnAllConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE)
            ])
            ->setDefaultState(ResponseState::Hidden);
        $this->checkState($response, ResponseState::Enabled, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateEnabled_All_Mixed(){
        $response = $this->getResponse()
            ->setEnabledOnAllConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, FAIL_CONDITION_ONE_VALUE)
            ])
            ->setDefaultState(ResponseState::Hidden);
        $this->checkState($response, ResponseState::Hidden, testId(__FILE__, __FUNCTION__));
    }

////// Disabled Tests

    function testResponseStateDisabled_Any(){
        $response = $this->getResponse()
            ->setDisabledOnAnyConditions(EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE))
            ->setDefaultState(ResponseState::Hidden);
        $this->checkState($response, ResponseState::Disabled, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateDisabled_Any_Mixed(){
        $response = $this->getResponse()
            ->setDisabledOnAnyConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, FAIL_CONDITION_ONE_VALUE)
            ])
            ->setDefaultState(ResponseState::Hidden);
        $this->checkState($response, ResponseState::Disabled, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateDisabled_All(){
        $response = $this->getResponse()
            ->setDisabledOnAllConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE)
            ])
            ->setDefaultState(ResponseState::Hidden);
        $this->checkState($response, ResponseState::Disabled, testId(__FILE__, __FUNCTION__));
    }

    function testResponseStateDisabled_All_Mixed(){
        $response = $this->getResponse()
            ->setEnabledOnAllConditions([
                EqualCondition::with(PASS_CONDITION_ONE, PASS_CONDITION_ONE_VALUE),
                EqualCondition::with(PASS_CONDITION_ONE, FAIL_CONDITION_ONE_VALUE)
            ])
            ->setDefaultState(ResponseState::Hidden);
        $this->checkState($response, ResponseState::Hidden, testId(__FILE__, __FUNCTION__));
    }

////// Special tests (complex sequences)

    function testGateWithKeyAndMove(){
        $key = 'key';
        $keyUsed = 'keyUsed';

        $lockResponse = $this->getResponse()
            ->setDefaultState(ResponseState::Disabled)
            ->setEnabledOnAllConditions(
                EqualCondition::with($key, 1)
            )
            ->setHiddenOnAllConditions(
                EqualCondition::with($keyUsed, 1)
            );

        $moveResponse = $this->getResponse()
            ->setDefaultState(ResponseState::Disabled)
            ->setEnabledOnAllConditions(
                EqualCondition::with($keyUsed, 1)
            );

        // confirm lock is disabled, move disabled
        $this->checkState($lockResponse, ResponseState::Disabled, testId(__FILE__, __FUNCTION__));
        $this->checkState($moveResponse, ResponseState::Disabled, testId(__FILE__, __FUNCTION__));

        // get key, confirm lock is enabled, move disabled
        $this->adjust($key, 1);
        $this->checkState($lockResponse, ResponseState::Enabled, testId(__FILE__, __FUNCTION__));
        $this->checkState($moveResponse, ResponseState::Disabled, testId(__FILE__, __FUNCTION__));

        // use key, confirm lock is hidden, move enabled
        $this->adjust($key, 0);
        $this->adjust($keyUsed, 1);
        $this->checkState($lockResponse, ResponseState::Hidden, testId(__FILE__, __FUNCTION__));
        $this->checkState($moveResponse, ResponseState::Enabled, testId(__FILE__, __FUNCTION__));
    }
}