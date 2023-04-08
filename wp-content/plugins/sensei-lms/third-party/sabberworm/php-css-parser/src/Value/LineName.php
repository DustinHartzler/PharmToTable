<?php

namespace Sensei\ThirdParty\Sabberworm\CSS\Value;

use Sensei\ThirdParty\Sabberworm\CSS\OutputFormat;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\ParserState;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\UnexpectedEOFException;
use Sensei\ThirdParty\Sabberworm\CSS\Parsing\UnexpectedTokenException;
class LineName extends \Sensei\ThirdParty\Sabberworm\CSS\Value\ValueList
{
    /**
     * @param array<int, RuleValueList|CSSFunction|CSSString|LineName|Size|URL|string> $aComponents
     * @param int $iLineNo
     */
    public function __construct(array $aComponents = [], $iLineNo = 0)
    {
        parent::__construct($aComponents, ' ', $iLineNo);
    }
    /**
     * @return LineName
     *
     * @throws UnexpectedTokenException
     * @throws UnexpectedEOFException
     */
    public static function parse(\Sensei\ThirdParty\Sabberworm\CSS\Parsing\ParserState $oParserState)
    {
        $oParserState->consume('[');
        $oParserState->consumeWhiteSpace();
        $aNames = [];
        do {
            if ($oParserState->getSettings()->bLenientParsing) {
                try {
                    $aNames[] = $oParserState->parseIdentifier();
                } catch (\Sensei\ThirdParty\Sabberworm\CSS\Parsing\UnexpectedTokenException $e) {
                    if (!$oParserState->comes(']')) {
                        throw $e;
                    }
                }
            } else {
                $aNames[] = $oParserState->parseIdentifier();
            }
            $oParserState->consumeWhiteSpace();
        } while (!$oParserState->comes(']'));
        $oParserState->consume(']');
        return new \Sensei\ThirdParty\Sabberworm\CSS\Value\LineName($aNames, $oParserState->currentLine());
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
        return '[' . parent::render(\Sensei\ThirdParty\Sabberworm\CSS\OutputFormat::createCompact()) . ']';
    }
}
