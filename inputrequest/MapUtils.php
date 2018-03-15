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
    // layout map (null, '', or 'nesw')
    // IdMap (basically null is ignored, everything else has an id generated)
    // build map uses both the layout and id map (layout is used for control)

    /**
     * @param $requestMap array of arrays representing the map of requests
     * @param $x int x position
     * @param $y int y position
     */
    public static function getXY($requestMap, $x, $y)
    {
        // this is critical because the maps are arrays of arrays with the outer array being y
        //echo 'seeking '.$x.','.$y;
        //var_dump($requestMap);
        return $requestMap[$y][$x];
    }

    /**
     * @param $layoutGrid array of layout information (array of arrays)
     * @param $prefix string prefix to prepend to all ids
     * @return array resulting array with updated ids
     */
    public static function generateIdMap($idGrid, $prefix)
    {
        $height = count($idGrid);
        $width = count($idGrid[0]);
        $prefixMap = array();

        // first confirm all arrays are equal in length
        for ($y = 1; $y < $height; $y++) {
            if(count($idGrid[$y]) != $width) {
                // this is useless (appears elsewhere
                error_log("All rows must be the same length. ".$y." row was not ".$width);
            }
        }

        //var_dump($idGrid);
        //echo '<br><br>';

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
            // EW this has to be done after items are added to the $rowArray (guess it's not a reference???)
            array_push($prefixMap, $rowArray);
        }
        //var_dump($prefixMap);
        //echo '<br><br>';
        return $prefixMap;
    }

    /**
     * Builds a map of MoveInputRequest objects based on the specified rectangular array map of ids
     * @param $idGrid array of ids (array of arrays)
     * @param $layoutMap array of layout information (array of arrays)
     * @return array array of arrays containing the MoveInputRequests (text defaulted to 'X x Y' (upper left = 0,0).
     */
    public static function buildMap($idGrid, $layoutMap)
    {
        // build all the requests from the XxY map (not adding them so they can be adjusted
        //)
        $height = count($idGrid);
        $width = count($idGrid[0]);
        $requestMap = array();

        // first confirm all arrays are equal in length
        for ($y = 1; $y < $height; $y++) {
            if(count($idGrid[$y]) != $width) {
                // TODO: useless logging
                error_log("All rows must be the same length. ".$y." row was not ".$width);
            }
        }
        //echo 'wtf:'.$width.','.$height.'<br>';

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

                //echo $x.",".$y.":".$northId.'::'.$eastId.'::'.$southId.'::'.$westId.'<br>';
                array_push($rowArray, new MoveInputRequest($x.",".$y, $northId, $westId, $eastId, $southId, NULL, NULL));
            }
            //var_dump($rowArray);
            // EW this has to be done after items are added to the $rowArray (guess it's not a reference???)
            array_push($requestMap, $rowArray);
        }
        return $requestMap;
    }

    private static function nullIfMissing($input, $seek, $value)
    {
        return strpos($input, $seek) === FALSE ? NULL : $value;
    }

    /**
     * Adds the requests for a map constructed with buildMap
     * @param $samqCore SAMQCore object to add the requests to
     * @param $idMap array of arrays containing the ids to add
     * @param $requestMap array of arrays containing the InputRequests to add
     */
    public static function addRequests($samqCore, $idMap, $requestMap)
    {
        $height = count($idMap);
        $width = count($idMap[0]);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $id = $idMap[$y][$x];
                if($id == NULL) continue;
                $samqCore->addRequest($id, $requestMap[$y][$x]);
            }
        }
    }
}