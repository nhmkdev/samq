<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/9/2018
 * Time: 7:22 AM
 */

// todo test various maps... and the response setup... yikes

new MapUtilsTest();

class MapUtilsTest extends TestAutoRunner
{
    public function testGridOfOne(){
        $mapLayout = array('nesw');
        $idGrid = MapUtils::generateIdMap($mapLayout, 'testMap');
        $requestMap = MapUtils::buildMap($idGrid, $mapLayout);
        $request = MapUtils::getXY($requestMap, 0, 0);
        $this->verifyMoveInputRequest($request, 'testMap_', '0,0', null, null, null, null, testId(__FILE__, __FUNCTION__));
    }



    public function testGridOfFour(){
        $testId = testId(__FILE__, __FUNCTION__);
        $mapLayout = array(
            array('nesw', 'nesw'),
            array('nesw', 'nesw')
        );
        $idGrid = MapUtils::generateIdMap($mapLayout, 'testMap_');
        $requestMap = MapUtils::buildMap($idGrid, $mapLayout);
        $this->verifyMoveInputRequest(MapUtils::getXY($requestMap, 0, 0), 'testMap_', '0,0', null, '01_00', '00_01', null, $testId);
        $this->verifyMoveInputRequest(MapUtils::getXY($requestMap, 1, 0), 'testMap_', '1,0', null, null, '01_01', '00_00', $testId);
        $this->verifyMoveInputRequest(MapUtils::getXY($requestMap, 0, 1), 'testMap_', '0,1', '00_00', '01_01', null, null, $testId);
        $this->verifyMoveInputRequest(MapUtils::getXY($requestMap, 1, 1), 'testMap_', '1,1', '01_00', null, null, '00_01', $testId);
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
        $expectedCombined = ($expected == null ? null : $prefix.$expected);
        verify($response->getRequestId() == $expectedCombined, $testId, $direction.' should be '.$expectedCombined.' actual '.$response->getRequestId());
    }
}