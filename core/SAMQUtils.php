<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/10/2018
 * Time: 5:03 PM
 */

class SAMQUtils {

    public static function getArrayFromArg($arg, $default) {
        if(isset($arg)) {
            if (is_array($arg)) {
                return $arg;
            }
            else{
                return array($arg);
            }
        }
        return $default;
    }

    public static function str_startswith($source, $prefix)
    {
        return strncmp($source, $prefix, strlen($prefix)) == 0;
    }
}

