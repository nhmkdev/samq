<?php

////////////////////////////////////////////////////////////////////////////////
// The MIT License (MIT)
//
// Copyright (c) 2018 Tim Stair
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.
////////////////////////////////////////////////////////////////////////////////

// the kitchen sink of includes

define("ADJUSTMENT_DEFAULT_STORE", "default");

include_once dirname(__FILE__) . '/core.php';
include_once dirname(__FILE__) . '/DestinationInquiry.php';
include_once dirname(__FILE__) . '/InvalidRequestCode.php';
include_once dirname(__FILE__) . '/SAMQCore.php';
include_once dirname(__FILE__) . '/SAMQUtils.php';

include_once dirname(__FILE__) . '/../adjustment/Adjustment.php';
include_once dirname(__FILE__) . '/../adjustment/ClearAllAdjustment.php';
include_once dirname(__FILE__) . '/../adjustment/ClearSessionAdjustment.php';
include_once dirname(__FILE__) . '/../adjustment/IncrementAdjustment.php';

include_once dirname(__FILE__) . '/../condition/EqualCondition.php';
include_once dirname(__FILE__) . '/../condition/NotEqualCondition.php';
include_once dirname(__FILE__) . '/../condition/GreaterOrEqualCondition.php';
include_once dirname(__FILE__) . '/../condition/LessOrEqualCondition.php';

include_once dirname(__FILE__) . '/../inputrequest/ConditionalText.php';
include_once dirname(__FILE__) . '/../inputrequest/ConditionalElseText.php';
include_once dirname(__FILE__) . '/../inputrequest/InputRequest.php';
include_once dirname(__FILE__) . '/../inputrequest/MoveInputRequest.php';
include_once dirname(__FILE__) . '/../inputrequest/MapUtils.php';
include_once dirname(__FILE__) . '/../inputrequest/MoveOnlyInputRequest.php';

include_once dirname(__FILE__) . '/../response/Response.php';
