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
        verify(isset($request), testId(__FILE__, __FUNCTION__), 'The request on the map is missing.');
        if(isset($request)) {
            verify($request->getText() == '0,0', testId(__FILE__, __FUNCTION__), 'The request on the map is missing');
            verify($request->getNorthResponse()->getRequestId() == null, testId(__FILE__, __FUNCTION__), 'North should not be set');
            verify($request->getEastResponse()->getRequestId() == null, testId(__FILE__, __FUNCTION__), 'East should not be set');
            verify($request->getSouthResponse()->getRequestId() == null, testId(__FILE__, __FUNCTION__), 'South should not be set');
            verify($request->getWestResponse()->getRequestId() == null, testId(__FILE__, __FUNCTION__), 'West should not be set');
        }
    }
}