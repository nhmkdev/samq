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

class MoveInputRequestUtils
{
    /**
     * Builds a map of MoveInputRequest objects based on the specified rectangular array map of ids
     * @param $idGrid array of ids (array of arrays)
     * @return array array of arrays containing the MoveInputRequests.
     */
    public static function buildMap($idGrid)
    {
        // build all the requests from the XxY map (not adding them so they can be adjusted
        //)
        $height = count($idGrid);
        $width = count($idGrid[0]);
        $requestMap = array();

        // first confirm all arrays are equal in length
        for ($y = 1; $y < $height; $y++) {
            if(count($idGrid[$y]) != $width)
            {
                error_log("All rows must be the same length. ".$y." row was not ".$width);
            }
        }
        //echo 'wtf:'.$width.','.$height.'<br>';

        // now actually process the rows
        for ($y = 0; $y < $height; $y++) {
            $rowArray = array();
            for ($x = 0; $x < $width; $x++) {
                $id = $idGrid[$y][$x];
                if($id == null)
                {
                    array_push($rowArray, null);
                    continue;
                }
                $northId = $y == 0 ? null : $idGrid[$y-1][$x];
                $eastId = $x == $width - 1 ? null : $idGrid[$y][$x+1];
                $southId = $y == $height - 1 ? null : $idGrid[$y+1][$x];
                $westId = $x == 0 ? null : $idGrid[$y][$x-1];
                //echo $x.",".$y.":".$northId.'::'.$eastId.'::'.$southId.'::'.$westId.'<br>';
                array_push($rowArray, new MoveInputRequest($x.",".$y, $northId, $westId, $eastId, $southId, null, null));
            }
            //var_dump($rowArray);
            // EW this has to be done after items are added to the $rowArray (guess it's not a reference???)
            array_push($requestMap, $rowArray);
        }
        return $requestMap;
    }

    /**
     * Adds the requests for a map constructed with buildMap
     * @param $samqCore SAMQCore object to add the requests to
     * @param $idGrid array of arrays containing the ids to add
     * @param $requestMap array of arrays containing the InputRequests to add
     */
    public static function addRequests($samqCore, $idGrid, $requestMap)
    {
        $height = count($idGrid);
        $width = count($idGrid[0]);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $id = $idGrid[$y][$x];
                if($id == null) continue;
                $samqCore->addRequest($id, $requestMap[$y][$x]);
            }
        }
    }
}