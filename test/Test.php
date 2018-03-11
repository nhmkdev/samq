<?php

function verify($passOnTrue, $function, $message)
{
    if($passOnTrue) {
        pass($function);
    }
    else{
        fail($message);
    }
}

function pass($function)
{
    global $passCount;
    $passCount++;
    echo 'PASS: '.$function.'<br>';
}

function fail($message)
{
    global $failCount;
    $failCount++;
    echo 'FAIL: '.$message.'<br>';
}