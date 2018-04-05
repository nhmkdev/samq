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

new MapUtilsTest();

class MapUtilsTest extends TestAutoRunner
{
    public function testGridOfOne(){
        $mapLayout = array('nesw');
        $idGrid = MapUtils::generateIdMap($mapLayout, 'testMap');
        $requestMap = MapUtils::buildMap($idGrid, $mapLayout);
        $request = MapUtils::getXY($requestMap, 0, 0);
        $this->verifyMoveInputRequest($request, 'testMap_', '0,0', NULL, NULL, NULL, NULL, testId(__FILE__, __FUNCTION__));
    }



    public function testGridOfFour(){
        $testId = testId(__FILE__, __FUNCTION__);
        $mapLayout = array(
            array('nesw', 'nesw'),
            array('nesw', 'nesw')
        );
        $idGrid = MapUtils::generateIdMap($mapLayout, 'testMap_');
        $requestMap = MapUtils::buildMap($idGrid, $mapLayout);
        $this->verifyMoveInputRequest(MapUtils::getXY($requestMap, 0, 0), 'testMap_', '0,0', NULL, '01_00', '00_01', NULL, $testId);
        $this->verifyMoveInputRequest(MapUtils::getXY($requestMap, 1, 0), 'testMap_', '1,0', NULL, NULL, '01_01', '00_00', $testId);
        $this->verifyMoveInputRequest(MapUtils::getXY($requestMap, 0, 1), 'testMap_', '0,1', '00_00', '01_01', NULL, NULL, $testId);
        $this->verifyMoveInputRequest(MapUtils::getXY($requestMap, 1, 1), 'testMap_', '1,1', '01_00', NULL, NULL, '00_01', $testId);
    }

    protected function verifyMoveInputRequest($request, $prefix, $expectedText, $expectedNorth, $expectedEast, $expectedSouth, $expectedWest, $testId){
        //echo $expectedText.'<br>';
        verify(isset($request), $testId, 'The request on the map is missing '.$expectedText);
        if(isset($request)){
            verify($request->getText() == $expectedText, $testId, 'Incorrect text on the map');
            $this->verifyResponse($request->getNorthResponse(), $prefix, $expectedNorth, 'North', $testId);
            $this->verifyResponse($request->getEastResponse(), $prefix, $expectedEast, 'East', $testId);
            $this->verifyResponse($request->getSouthResponse(), $prefix, $expectedSouth, 'South', $testId);
            $this->verifyResponse($request->getWestResponse(), $prefix, $expectedWest, 'West', $testId);
        }
    }

    protected function verifyResponse($response, $prefix, $expected, $direction, $testId){
        $expectedCombined = ($expected == NULL ? NULL : $prefix.$expected);
        verify($response->getRequestId() == $expectedCombined, $testId, $direction.' should be '.$expectedCombined.' actual '.$response->getRequestId());
    }
}