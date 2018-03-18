<?php

define("REGION", "");
define("______", null);
define("KEY_USED", "KEY_USED");
define("KEY", "KEY");

$samqCore->addRequest('sample_01', InputRequest::with(
    'Welcome to the SAMQ (Stop Asking Me Questions) sample.',
    [
        Response::with('Choice 1', 'sample_02a'),
        Response::with('Choice 2', 'sample_02b'),
    ])
    ->setPostAdjustments(
        ClearSessionAdjustment::create()
    )
);

$samqCore->addRequest('sample_02a', InputRequest::with(
    'After selecting a response the engine transitions to another InputRequest. You chose 1!',
    [
        Response::with('Continue to the map.', 'sample_map_02_04'),
    ])
);

$samqCore->addRequest('sample_02b', InputRequest::with(
    'After selecting a response the engine transitions to another InputRequest. You chose 2!',
    [
        Response::with('Continue to the map.', 'sample_map_02_04'),
    ])
);

/////////////////////////////////////
// Sample Map

$sampleMapLayout = array(
    //    0       1       2       3       4
    array(______, ______, REGION, "xxxw", "xxsx"),//0
    array(______, ______, REGION, ______, REGION),//1
    array(______, REGION, REGION, REGION, REGION),//2
    array(______, ______, REGION, ______, REGION),//3
    array(______, ______, REGION, ______, REGION),//4
);

$sampleMapIds = MapUtils::generateIdMap($sampleMapLayout, 'sample_map_');
$sampleRequestMap = MapUtils::buildMap($sampleMapIds, $sampleMapLayout);

MapUtils::getXY($sampleRequestMap, 2, 2)
    ->setNorthResponse(
        MoveInputRequest::createMoveResponse('n', 'sample_map_02_01')
            ->setEnabledOnAnyConditions(EqualCondition::with(KEY_USED, 1))
            ->setDefaultState(ResponseState::Disabled))
    ->setCommandResponse(
        Response::with('Unlock', 'sample_map_02_02')
            ->setEnabledOnAllConditions(EqualCondition::with(KEY, 1))
            ->setHiddenOnAnyConditions([
                EqualCondition::with(KEY_USED, 1),
                NotEqualCondition::with(KEY, 1)
            ])
            ->setDefaultState(ResponseState::Disabled)
            ->setAdjustments(Adjustment::with(KEY_USED, 1)))
    ->setConditionalText(ConditionalText::withElse(
        '<br>There is a locked gate here. Try to find the key.',
        '<br>The gate is unlocked. The passage north is open.',
        NotEqualCondition::with(KEY_USED, 1)));

MapUtils::getXY($sampleRequestMap, 4, 4)
    ->setCommandResponse(
        Response::with('Pick up key', 'sample_map_04_04')
            ->setHiddenOnAnyConditions([
                EqualCondition::with(KEY, 1),
                EqualCondition::with(KEY_USED, 1)
            ])
            ->setAdjustments(Adjustment::with(KEY, 1))
    );

MapUtils::getXY($sampleRequestMap, 3, 0)
    ->setNorthResponse(
        MoveInputRequest::createMoveResponse('n', 'sample_01')
            ->setEnabledOnAnyConditions(EqualCondition::with(KEY_USED, 1))
            ->setDefaultState(ResponseState::Disabled)
    );

MapUtils::addRequests($samqCore, $sampleMapIds, $sampleRequestMap);