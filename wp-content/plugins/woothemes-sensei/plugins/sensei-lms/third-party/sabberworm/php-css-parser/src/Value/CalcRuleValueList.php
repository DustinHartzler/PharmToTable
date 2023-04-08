<?php

namespace Sensei\ThirdParty\Sabberworm\CSS\Value;

use Sensei\ThirdParty\Sabberworm\CSS\OutputFormat;
class CalcRuleValueList extends \Sensei\ThirdParty\Sabberworm\CSS\Value\RuleValueList
{
    /**
     * @param int $iLineNo
     */
    public function __construct($iLineNo = 0)
    {
        parent::__construct(',', $iLineNo);
    }
    /**
     * @return string
     */
    public function render(\Sensei\ThirdParty\Sabberworm\CSS\OutputFormat $oOutputFormat)
    {
        return $oOutputFormat->implode(' ', $this->aComponents);
    }
}
