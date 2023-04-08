<?php

namespace Sensei\ThirdParty\Sabberworm\CSS\Value;

use Sensei\ThirdParty\Sabberworm\CSS\OutputFormat;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\ParserState;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\SourceException;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\UnexpectedEOFException;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\UnexpectedTokenException;
class URL extends \Sensei\ThirdParty\Sabberworm\CSS\Value\PrimitiveValue
{
    /**
     * @var CSSString
     */
    private $oURL;
    /**
     * @param int $iLineNo
     */
    public function __construct(\Sensei\ThirdParty\Sabberworm\CSS\Value\CSSString $oURL, $iLineNo = 0)
    {
        parent::__construct($iLineNo);
        $this->oURL = $oURL;
    }
    /**
     * @return URL
     *
     * @throws SourceException
     * @throws UnexpectedEOFException
     * @throws UnexpectedTokenException
     */
    public static function parse(\Sensei\ThirdParty\Sabberworm\CSS\Parsing\ParserState $oParserState)
    {
        $bUseUrl = $oParserState->comes('url', \true);
        if ($bUseUrl) {
            $oParserState->consume('url');
            $oParserState->consumeWhiteSpace();
            $oParserState->consume('(');
        }
        $oParserState->consumeWhiteSpace();
        $oResult = new \Sensei\ThirdParty\Sabberworm\CSS\Value\URL(\Sensei\ThirdParty\Sabberworm\CSS\Value\CSSString::parse($oParserState), $oParserState->currentLine());
        if ($bUseUrl) {
            $oParserState->consumeWhiteSpace();
            $oParserState->consume(')');
        }
        return $oResult;
    }
    /**
     * @return void
     */
    public function setURL(\Sensei\ThirdParty\Sabberworm\CSS\Value\CSSString $oURL)
    {
        $this->oURL = $oURL;
    }
    /**
     * @return CSSString
     */
    public function getURL()
    {
        return $this->oURL;
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
        return "url({$this->oURL->render($oOutputFormat)})";
    }
}
