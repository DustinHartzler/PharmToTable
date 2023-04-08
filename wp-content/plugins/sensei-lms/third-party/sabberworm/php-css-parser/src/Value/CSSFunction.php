<?php

namespace Sensei\ThirdParty\Sabberworm\CSS\Value;

use Sensei\ThirdParty\Sabberworm\CSS\OutputFormat;
class CSSFunction extends \Sensei\ThirdParty\Sabberworm\CSS\Value\ValueList
{
    /**
     * @var string
     */
    protected $sName;
    /**
     * @param string $sName
     * @param RuleValueList|array<int, RuleValueList|CSSFunction|CSSString|LineName|Size|URL|string> $aArguments
     * @param string $sSeparator
     * @param int $iLineNo
     */
    public function __construct($sName, $aArguments, $sSeparator = ',', $iLineNo = 0)
    {
        if ($aArguments instanceof \Sensei\ThirdParty\Sabberworm\CSS\Value\RuleValueList) {
            $sSeparator = $aArguments->getListSeparator();
            $aArguments = $aArguments->getListComponents();
        }
        $this->sName = $sName;
        $this->iLineNo = $iLineNo;
        parent::__construct($aArguments, $sSeparator, $iLineNo);
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->sName;
    }
    /**
     * @param string $sName
     *
     * @return void
     */
    public function setName($sName)
    {
        $this->sName = $sName;
    }
    /**
     * @return array<int, RuleValueList|CSSFunction|CSSString|LineName|Size|URL|string>
     */
    public function getArguments()
    {
        return $this->aComponents;
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
        $aArguments = parent::render($oOutputFormat);
        return "{$this->sName}({$aArguments})";
    }
}
