<?php

namespace Sensei\ThirdParty\Sabberworm\CSS\RuleSet;

use Sensei\ThirdParty\Sabberworm\CSS\OutputFormat;
use Sensei\ThirdParty\Sabberworm\CSS\Property\AtRule;
/**
 * A RuleSet constructed by an unknown at-rule. `@font-face` rules are rendered into AtRuleSet objects.
 */
class AtRuleSet extends \Sensei\ThirdParty\Sabberworm\CSS\RuleSet\RuleSet implements \Sensei\ThirdParty\Sabberworm\CSS\Property\AtRule
{
    /**
     * @var string
     */
    private $sType;
    /**
     * @var string
     */
    private $sArgs;
    /**
     * @param string $sType
     * @param string $sArgs
     * @param int $iLineNo
     */
    public function __construct($sType, $sArgs = '', $iLineNo = 0)
    {
        parent::__construct($iLineNo);
        $this->sType = $sType;
        $this->sArgs = $sArgs;
    }
    /**
     * @return string
     */
    public function atRuleName()
    {
        return $this->sType;
    }
    /**
     * @return string
     */
    public function atRuleArgs()
    {
        return $this->sArgs;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render(new \Sensei\ThirdParty\Sabberworm\CSS\OutputFormat());
    }
    /**
     * @return string
     */
    public function render(\Sensei\ThirdParty\Sabberworm\CSS\OutputFormat $oOutputFormat)
    {
        $sArgs = $this->sArgs;
        if ($sArgs) {
            $sArgs = ' ' . $sArgs;
        }
        $sResult = "@{$this->sType}{$sArgs}{$oOutputFormat->spaceBeforeOpeningBrace()}{";
        $sResult .= parent::render($oOutputFormat);
        $sResult .= '}';
        return $sResult;
    }
}
