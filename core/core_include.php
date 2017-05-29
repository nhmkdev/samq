<?php

// the kitchen sink of includes

include_once dirname(__FILE__) . '/core.php';

include_once dirname(__FILE__) . '/../adjustment/Adjustment.php';
include_once dirname(__FILE__) . '/../adjustment/ClearAllAdjustment.php';

include_once dirname(__FILE__) . '/../condition/EqualityCondition.php';
include_once dirname(__FILE__) . '/../condition/GreaterOrEqualCondition.php';

include_once dirname(__FILE__) . '/../inputrequest/ConditionalText.php';
include_once dirname(__FILE__) . '/../inputrequest/InputRequest.php';
include_once dirname(__FILE__) . '/../inputrequest/MoveInputRequest.php';
include_once dirname(__FILE__) . '/../inputrequest/MoveOnlyInputRequest.php';

include_once dirname(__FILE__) . '/../response/Response.php';
