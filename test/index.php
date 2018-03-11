<?php
// TODO: use phpsession variables if desired
// Start the session
session_start();
?>

<?php

//// Work log
/// Pre - 6 hours
/// 2/26 - 2
/// 2/27 - 2
/// 2/28 - 2
/// 3/1 - 1
/// 3/2 - 3
/// 3/3 - 2 (so far)

// TODO: just make a getter on samqcore (for the other spots)
define("SAMQ_POST_PATH", "/tale2/");
define("VALIDATE_REQUEST_IDS", false);

include_once dirname(__FILE__) . '/../core/core_include.php';

$failCount = 0;
$passCount = 0;

$samqCore = new SAMQCore('salty!',
    /*'intro_start_00'*/'intro_start_00'
    , SAMQ_POST_PATH);

include_once dirname(__FILE__) . '/Test.php';
include_once dirname(__FILE__) . '/TestAutoRunner.php';
include_once dirname(__FILE__) . '/adjustment/AdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/ClearSessionAdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/ClearAllAdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/IncrementAdjustmentTest.php';
include_once dirname(__FILE__) . '/condition/ConditionTest.php';
include_once dirname(__FILE__) . '/condition/EqualConditionTest.php';
include_once dirname(__FILE__) . '/condition/GreaterOrEqualConditionTest.php';
include_once dirname(__FILE__) . '/condition/NotEqualConditionTest.php';
include_once dirname(__FILE__) . '/core/CoreTest.php';
include_once dirname(__FILE__) . '/inputrequest/ConditionalElseTextTest.php';
include_once dirname(__FILE__) . '/inputrequest/ConditionalTextTest.php';
include_once dirname(__FILE__) . '/inputrequest/InputRequestTest.php';
include_once dirname(__FILE__) . '/inputrequest/MapUtilsTest.php';
include_once dirname(__FILE__) . '/response/ResponseTest.php';

if($failCount > 0){
    echo '<hr>ERRORS: '.$failCount.'<br><hr>';
}
else{
    echo '<hr>All passed: '.$passCount.'<br><hr>';
}