<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension;

use Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException;
use Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode;
use Sensei\ThirdParty\Symfony\Component\CssSelector\Parser\Parser;
use Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator;
use Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr;
/**
 * XPath expression translator function extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class FunctionExtension extends \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctionTranslators() : array
    {
        return ['nth-child' => [$this, 'translateNthChild'], 'nth-last-child' => [$this, 'translateNthLastChild'], 'nth-of-type' => [$this, 'translateNthOfType'], 'nth-last-of-type' => [$this, 'translateNthLastOfType'], 'contains' => [$this, 'translateContains'], 'lang' => [$this, 'translateLang']];
    }
    /**
     * @throws ExpressionErrorException
     */
    public function translateNthChild(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode $function, bool $last = \false, bool $addNameTest = \true) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        try {
            [$a, $b] = \Sensei\ThirdParty\Symfony\Component\CssSelector\Parser\Parser::parseSeries($function->getArguments());
        } catch (\Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\SyntaxErrorException $e) {
            throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException(\sprintf('Invalid series: "%s".', \implode('", "', $function->getArguments())), 0, $e);
        }
        $xpath->addStarPrefix();
        if ($addNameTest) {
            $xpath->addNameTest();
        }
        if (0 === $a) {
            return $xpath->addCondition('position() = ' . ($last ? 'last() - ' . ($b - 1) : $b));
        }
        if ($a < 0) {
            if ($b < 1) {
                return $xpath->addCondition('false()');
            }
            $sign = '<=';
        } else {
            $sign = '>=';
        }
        $expr = 'position()';
        if ($last) {
            $expr = 'last() - ' . $expr;
            --$b;
        }
        if (0 !== $b) {
            $expr .= ' - ' . $b;
        }
        $conditions = [\sprintf('%s %s 0', $expr, $sign)];
        if (1 !== $a && -1 !== $a) {
            $conditions[] = \sprintf('(%s) mod %d = 0', $expr, $a);
        }
        return $xpath->addCondition(\implode(' and ', $conditions));
        // todo: handle an+b, odd, even
        // an+b means every-a, plus b, e.g., 2n+1 means odd
        // 0n+b means b
        // n+0 means a=1, i.e., all elements
        // an means every a elements, i.e., 2n means even
        // -n means -1n
        // -1n+6 means elements 6 and previous
    }
    public function translateNthLastChild(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode $function) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $this->translateNthChild($xpath, $function, \true);
    }
    public function translateNthOfType(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode $function) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $this->translateNthChild($xpath, $function, \false, \false);
    }
    /**
     * @throws ExpressionErrorException
     */
    public function translateNthLastOfType(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode $function) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        if ('*' === $xpath->getElement()) {
            throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException('"*:nth-of-type()" is not implemented.');
        }
        return $this->translateNthChild($xpath, $function, \true, \false);
    }
    /**
     * @throws ExpressionErrorException
     */
    public function translateContains(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode $function) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        $arguments = $function->getArguments();
        foreach ($arguments as $token) {
            if (!($token->isString() || $token->isIdentifier())) {
                throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException('Expected a single string or identifier for :contains(), got ' . \implode(', ', $arguments));
            }
        }
        return $xpath->addCondition(\sprintf('contains(string(.), %s)', \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($arguments[0]->getValue())));
    }
    /**
     * @throws ExpressionErrorException
     */
    public function translateLang(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode $function) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        $arguments = $function->getArguments();
        foreach ($arguments as $token) {
            if (!($token->isString() || $token->isIdentifier())) {
                throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException('Expected a single string or identifier for :lang(), got ' . \implode(', ', $arguments));
            }
        }
        return $xpath->addCondition(\sprintf('lang(%s)', \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($arguments[0]->getValue())));
    }
    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return 'function';
    }
}
