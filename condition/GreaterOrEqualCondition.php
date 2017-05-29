<?php

include_once dirname(__FILE__) . '/../core/core.php';
include_once dirname(__FILE__) . '/iCondition.php';

class GreaterOrEqualCondition implements iCondition
{
    private $variable;
    private $requiredValue;

    function __construct($variable, $requiredValue) {
        $this->variable = $variable;
        $this->requiredValue = $requiredValue;
    }

    public function isMet()
    {
        return $_SESSION[$this->variable] > $this->requiredValue;
    }
}