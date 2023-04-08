<?php

namespace Sensei\ThirdParty\Sabberworm\CSS;

use Sensei\ThirdParty\Sabberworm\CSS\CSSList\Document;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\ParserState;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\SourceException;
/**
 * This class parses CSS from text into a data structure.
 */
class Parser
{
    /**
     * @var ParserState
     */
    private $oParserState;
    /**
     * @param string $sText
     * @param Settings|null $oParserSettings
     * @param int $iLineNo the line number (starting from 1, not from 0)
     */
    public function __construct($sText, \Sensei\ThirdParty\Sabberworm\CSS\Settings $oParserSettings = null, $iLineNo = 1)
    {
        if ($oParserSettings === null) {
            $oParserSettings = \Sensei\ThirdParty\Sabberworm\CSS\Settings::create();
        }
        $this->oParserState = new \Sensei\ThirdParty\Sabberworm\CSS\Parsing\ParserState($sText, $oParserSettings, $iLineNo);
    }
    /**
     * @param string $sCharset
     *
     * @return void
     */
    public function setCharset($sCharset)
    {
        $this->oParserState->setCharset($sCharset);
    }
    /**
     * @return void
     */
    public function getCharset()
    {
        // Note: The `return` statement is missing here. This is a bug that needs to be fixed.
        $this->oParserState->getCharset();
    }
    /**
     * @return Document
     *
     * @throws SourceException
     */
    public function parse()
    {
        return \Sensei\ThirdParty\Sabberworm\CSS\CSSList\Document::parse($this->oParserState);
    }
}
