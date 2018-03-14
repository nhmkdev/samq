<?php
// TODO: use phpsession variables if desired
// Start the session
session_start();
?>

<?php

// load the engine
include_once dirname(__FILE__) . '/../core/core_include.php';

// pre-configure some test stuff
$failCount = 0;
$passCount = 0;

$samqCore = new SAMQCore('salty!',
    /*'intro_start_00'*/'intro_start_00'
    , SAMQ_POST_PATH);

// Test infrastructure
include_once dirname(__FILE__) . '/Test.php';
include_once dirname(__FILE__) . '/TestAutoRunner.php';

// TESTS!
include_once dirname(__FILE__) . '/adjustment/AdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/ClearSessionAdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/ClearAllAdjustmentTest.php';
include_once dirname(__FILE__) . '/adjustment/IncrementAdjustmentTest.php';
include_once dirname(__FILE__) . '/condition/EqualConditionTest.php';
include_once dirname(__FILE__) . '/condition/GreaterOrEqualConditionTest.php';
include_once dirname(__FILE__) . '/condition/NotEqualConditionTest.php';
include_once dirname(__FILE__) . '/core/CoreTest.php';
include_once dirname(__FILE__) . '/inputrequest/ConditionalElseTextTest.php';
include_once dirname(__FILE__) . '/inputrequest/ConditionalTextTest.php';
include_once dirname(__FILE__) . '/inputrequest/InputRequestTest.php';
include_once dirname(__FILE__) . '/inputrequest/MapUtilsTest.php';
include_once dirname(__FILE__) . '/response/ResponseTest.php';

// log results
if($failCount > 0){
    echo '<hr>ERRORS: '.$failCount.'<br><hr>';
}
else{
    echo '<hr>All passed: '.$passCount.'<br><hr>';
}