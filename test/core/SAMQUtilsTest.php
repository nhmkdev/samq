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

new SAMQUtilsTest();

class SAMQUtilsTest extends TestAutoRunner
{
    public function testGetArrayFromArgNoClassCheck(){
        $response = new Response('sample', null);
        $result = SAMQUtils::getArrayFromArg($response, null);
        verify(
            $result != null
            && is_array($result)
            && 1 == count($result)
            && $result[0] = $response,
            __FUNCTION__,
            '');
    }

    public function testGetArrayFromArgVerifyNoClassCheck(){
        $response = new Response('sample', null);
        $result = SAMQUtils::getArrayFromArgVerify($response, null, null);
        verify(
            $result != null
            && is_array($result)
            && 1 == count($result)
            && $result[0] = $response,
            __FUNCTION__,
            '');
    }

    public function testGetArrayFromArgVerifyValidClass(){
        $response = new Response('sample', null);
        $result = SAMQUtils::getArrayFromArgVerify($response, null, Response::class);
        verify(
            $result != null
            && is_array($result)
            && 1 == count($result)
            && $result[0] = $response,
            __FUNCTION__,
            '');
    }

    public function testGetArrayFromArgVerifyInvalidClass(){
        $result = SAMQUtils::getArrayFromArgVerify(new Response('sample', null), null, InputRequest::class);
        verify($result == null,
            __FUNCTION__,
            'The class mismatch check should have resulted in an error.');
    }
}