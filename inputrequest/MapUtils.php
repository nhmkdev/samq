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

class MapUtils
{
    /**
     * @param $requestMap array of arrays representing the map of requests
     * @param $x int x position
     * @param $y int y position
     * @return MoveInputRequest the request at the location, or NULL if none exists.
     */
    public static function getXY($requestMap, $x, $y)
    {
        // this is critical because the maps are arrays of arrays with the outer array being y
        if(!isset($requestMap[$y][$x])){
            Logger::error(__FUNCTION__.": Attempted to access invalid map coordinate ".$x.','.$y);
            return NULL;
        }
        return $requestMap[$y][$x];
    }

    /**
     * @param $idGrid array of layout information (array of arrays)
     * @param $prefix string prefix to prepend to all ids
     * @return array string[][] resulting array with updated ids
     */
    public static function generateIdMap($idGrid, $prefix)
    {
        $height = count($idGrid);
        $width = count($idGrid[0]);
        $prefixMap = array();

        // first confirm all arrays are equal in length
        for ($y = 1; $y < $height; $y++) {
            if(count($idGrid[$y]) != $width) {
                Logger::error(__FUNCTION__.": All rows must be the same length. ".$y." row was not ".$width);
                return NULL;
            }
        }

        for ($y = 0; $y < $height; $y++) {
            $rowArray = array();
            for ($x = 0; $x < $width; $x++) {
                $id = $idGrid[$y][$x];
                // this is the right way to check for null (others will indicate '' is NULL)
                if (is_null($id)) {
                    //echo '('.$id.')['.$x.','.$y.']'.' adding null<br>';
                    array_push($rowArray, NULL);
                }
                else {
                    $newName = $prefix.str_pad($x, 2, '0', STR_PAD_LEFT).'_'.str_pad($y, 2, '0', STR_PAD_LEFT);
                    //echo '('.$id.')['.$x.','.$y.']'.' adding '.$newName.'<br>';
                    array_push($rowArray, $newName);
                }
            }
            // this has to be done after items are added to the $rowArray
            array_push($prefixMap, $rowArray);
        }
        return $prefixMap;
    }

    /**
     * Builds a map of MoveInputRequest objects based on the specified rectangular array map of ids
     * @param $idGrid array of ids (array of arrays)
     * @param $layoutMap array of layout information (array of arrays)
     * @return array InputRequest[][] array of arrays containing the MoveInputRequests (text defaulted to 'X x Y' (upper left = 0,0).
     */
    public static function buildMap($idGrid, $layoutMap)
    {
        // build all the requests from the XxY map (not adding them so they can be adjusted)
        $height = count($idGrid);
        $width = count($idGrid[0]);
        $requestMap = array();

        // first confirm all arrays are equal in length
        for ($y = 1; $y < $height; $y++) {
            if(count($idGrid[$y]) != $width) {
                Logger::error(__FUNCTION__.": All rows must be the same length. ".$y." row was not ".$width);
                return NULL;
            }
        }
        //echo $width.','.$height.'<br>';

        // now actually process the rows
        for ($y = 0; $y < $height; $y++) {
            $rowArray = array();
            for ($x = 0; $x < $width; $x++) {
                $id = $idGrid[$y][$x];
                if($id == NULL)
                {
                    array_push($rowArray, NULL);
                    continue;
                }

                $northId = $y == 0 ? NULL : $idGrid[$y-1][$x];
                $eastId = $x == $width - 1 ? NULL : $idGrid[$y][$x+1];
                $southId = $y == $height - 1 ? NULL : $idGrid[$y+1][$x];
                $westId = $x == 0 ? NULL : $idGrid[$y][$x-1];

                $layoutItem = $layoutMap[$y][$x];
                // check the layout string for mapping overrides (if one is missing remove the connection for that direction)
                if(!empty($layoutItem)) {
                    $northId = MapUtils::nullIfMissing($layoutItem, 'n', $northId);
                    $eastId = MapUtils::nullIfMissing($layoutItem, 'e', $eastId);
                    $southId = MapUtils::nullIfMissing($layoutItem, 's', $southId);
                    $westId = MapUtils::nullIfMissing($layoutItem, 'w', $westId);
                }
                array_push($rowArray, new MoveInputRequest($x.",".$y, $northId, $westId, $eastId, $southId, NULL, NULL));
            }
            // this has to be done after items are added to the $rowArray
            array_push($requestMap, $rowArray);
        }
        return $requestMap;
    }

    /**
     * Adds the requests for a map constructed with buildMap
     * @param $samqCore SAMQCore object to add the requests to
     * @param $idMap string[][] array of arrays containing the ids to add
     * @param $requestMap InputRequest[][] array of arrays containing the InputRequests to add
     */
    public static function addRequests($samqCore, $idMap, $requestMap)
    {
        $height = count($idMap);
        $width = count($idMap[0]);

        // TODO: array size checks?

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $id = $idMap[$y][$x];
                if($id == NULL) continue;
                $samqCore->addRequest($id, $requestMap[$y][$x]);
            }
        }
    }

    /**
     * Checks for a string within a string, returning a default if found, otherwise NULL
     * @param $input string
     * @param $seek string
     * @param $value mixed
     * @return string|NULL
     */
    private static function nullIfMissing($input, $seek, $value)
    {
        return strpos($input, $seek) === FALSE ? NULL : $value;
    }
}