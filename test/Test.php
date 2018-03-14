<?php

function verify($passOnTrue, $function, $message)
{
    if($passOnTrue) {
        pass($function);
    }
    else{
        fail($function, $message);
    }
}

function pass($function)
{
    global $passCount;
    $passCount++;
    echo 'PASS: '.$function.'<br>';
}

function fail($function, $message)
{
    global $failCount;
    $failCount++;
    echo 'FAIL: '.$function.' '.$message.'<br>';
}

function testId($file, $function){
    return basename($file).':'.$function;
}