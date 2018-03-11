<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/10/2018
 * Time: 5:32 PM
 */

// highly questionable on the location of this file
class InvalidRequestCode
{
    public $requestCode;
    function __construct($jumpCode) {
        $this->requestCode = $jumpCode;
    }
}
