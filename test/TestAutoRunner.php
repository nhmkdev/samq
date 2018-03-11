<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/11/2018
 * Time: 1:38 PM
 */

class TestAutoRunner
{
    public function __construct()
    {
        // execute all methods with the 'test' prefix
        $methods = get_class_methods($this);

        forEach($methods as $method)
        {
            if(SAMQUtils::str_startswith($method, 'test'))
            {
                $this->setupTest();
                $this->{$method}();
            }
        }
    }

    public function setupTest(){

    }
}