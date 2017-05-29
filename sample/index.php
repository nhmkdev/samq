<?php
// TODO: use phpsession variables if desired
// Start the session
session_start();
?>

<?php

include_once dirname(__FILE__) . '/../core/core_include.php';

$samqCore = new SAMQCore('salt!', 'intro');

// not working yet...
$samqCore->addRequestCode('the request code', 'intro');

$samqCore->addRequest('intro', new InputRequest(
    'Welcome to the sample.',
    [
        (new Response('Continue to the movement sample', 'move_sample_center'))
            ->setAdjustments(array(new ClearAllAdjustment()))
    ]));

$samqCore->addRequest('move_sample_center', (new MoveInputRequest(
    'Location Marker: Center<br>',
    'move_sample_north',
    'move_sample_west',
    'move_sample_east',
    'move_sample_south',
    (new Response('Unlock door', 'sample_complete'))
        ->setHiddenConditions(new EqualityCondition('door_unlocked', true))
        ->setDisabledConditions(new EqualityCondition('key', NULL))
        ->setEnabledConditions(new EqualityCondition('key', true))
        ->setAdjustments(array(
            new Adjustment('key', false),
            new Adjustment('door_unlocked', true)))
    )
    )
    ->setConditionalText(new ConditionalText('There is a locked door',
        new EqualityCondition('door_unlocked', NULL)))
);

$samqCore->addRequest('sample_complete', new InputRequest(
    'You unlock the door and discard the key',
    [
        new Response('Return to the movement sample', 'move_sample_center', null),
    ]));

$samqCore->addRequest('move_sample_north', (new MoveInputRequest(
    'Location Marker: North<br>',
    NULL,
    NULL,
    NULL,
    'move_sample_center',
    (new Response('Pick up key', 'move_sample_north'))
        ->setEnabledConditions(new EqualityCondition('key_taken', NULL))
        ->setAdjustments(array(
            new Adjustment('key', true),
            new Adjustment('key_taken', true)))
    ))
    ->setConditionalText(new ConditionalText('There is a key here.',
        new EqualityCondition('key_taken', NULL)))
);

$samqCore->addRequest('move_sample_west', new MoveOnlyInputRequest(
    'Location Marker: West<br>',
    NULL,
    NULL,
    'move_sample_center',
    NULL
));

$samqCore->addRequest('move_sample_east', new MoveOnlyInputRequest(
    'Location Marker: East<br>',
    NULL,
    'move_sample_center',
    NULL,
    NULL
));

$samqCore->addRequest('move_sample_south', new MoveOnlyInputRequest(
    'Location Marker: South<br>',
    'move_sample_center',
    NULL,
    NULL,
    NULL
));

function render()
{
    echo '<!DOCTYPE html>'.DBG_EOL
        .'<html>'.DBG_EOL
        .'<head>'.DBG_EOL
        .'<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">'.DBG_EOL
        .'<title>SAMQ Sample</title>'.DBG_EOL;
//    createStyle($bgColor);

    /// somewhat block back button
    /*
        echo '<script>
        window.location.hash="x";
        window.location.hash="y";//again because google chrome don\'t insert first hash into history
        window.onhashchange=function(){window.location.hash="z";}
        </script>';
    */
    ///

    echo '</head>'.DBG_EOL;

    echo '<body><hr>'.DBG_EOL;

    global $samqCore;
    $destinationInfo = $samqCore->getDestinationInfo();
    if($destinationInfo instanceof InvalidRequestCode) {
        echo 'Invalid request code supplied: '.$destinationInfo->requestCode;
    }
    else {
        $destinationInfo->destinationRequest->render($samqCore);
    }

    echo '<hr><p><form action="/samq/sample/" method="POST">'.DBG_EOL
        .'Jump Code: <input type="text" size="20" name="'.SAMQ_REQUESTCODE.'">&nbsp;&nbsp;&nbsp;'.DBG_EOL
        .'<input type="submit" value="Submit"></form></p>'.DBG_EOL;

    echo '<p><span class="standard"><a href="/samq/sample/">Start Over</a></span></p>'.DBG_EOL;

    var_dump($_SESSION);
    echo '</body>'.DBG_EOL;
}

$samqCore->initializeSequenceTable();
render();