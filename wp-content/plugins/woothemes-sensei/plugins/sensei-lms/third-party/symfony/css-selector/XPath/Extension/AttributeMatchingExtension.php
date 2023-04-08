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

use Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator;
use Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr;
/**
 * XPath expression translator attribute extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class AttributeMatchingExtension extends \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getAttributeMatchingTranslators() : array
    {
        return ['exists' => [$this, 'translateExists'], '=' => [$this, 'translateEquals'], '~=' => [$this, 'translateIncludes'], '|=' => [$this, 'translateDashMatch'], '^=' => [$this, 'translatePrefixMatch'], '$=' => [$this, 'translateSuffixMatch'], '*=' => [$this, 'translateSubstringMatch'], '!=' => [$this, 'translateDifferent']];
    }
    public function translateExists(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($attribute);
    }
    public function translateEquals(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition(\sprintf('%s = %s', $attribute, \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)));
    }
    public function translateIncludes(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($value ? \sprintf('%1$s and contains(concat(\' \', normalize-space(%1$s), \' \'), %2$s)', $attribute, \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral(' ' . $value . ' ')) : '0');
    }
    public function translateDashMatch(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition(\sprintf('%1$s and (%1$s = %2$s or starts-with(%1$s, %3$s))', $attribute, \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value), \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value . '-')));
    }
    public function translatePrefixMatch(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($value ? \sprintf('%1$s and starts-with(%1$s, %2$s)', $attribute, \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)) : '0');
    }
    public function translateSuffixMatch(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($value ? \sprintf('%1$s and substring(%1$s, string-length(%1$s)-%2$s) = %3$s', $attribute, \strlen($value) - 1, \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)) : '0');
    }
    public function translateSubstringMatch(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition($value ? \sprintf('%1$s and contains(%1$s, %2$s)', $attribute, \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)) : '0');
    }
    public function translateDifferent(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        return $xpath->addCondition(\sprintf($value ? 'not(%1$s) or %1$s != %2$s' : '%s != %s', $attribute, \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Translator::getXpathLiteral($value)));
    }
    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return 'attribute-matching';
    }
}
