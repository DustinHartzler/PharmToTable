<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sensei\ThirdParty\Symfony\Component\CssSelector\XPath;

use Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException;
use Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode;
use Sensei\ThirdParty\Symfony\Component\CssSelector\Node\NodeInterface;
use Sensei\ThirdParty\Symfony\Component\CssSelector\Node\SelectorNode;
use Sensei\ThirdParty\Symfony\Component\CssSelector\Parser\Parser;
use Sensei\ThirdParty\Symfony\Component\CssSelector\Parser\ParserInterface;
/**
 * XPath expression translator interface.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class Translator implements \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\TranslatorInterface
{
    private $mainParser;
    /**
     * @var ParserInterface[]
     */
    private $shortcutParsers = [];
    /**
     * @var Extension\ExtensionInterface[]
     */
    private $extensions = [];
    private $nodeTranslators = [];
    private $combinationTranslators = [];
    private $functionTranslators = [];
    private $pseudoClassTranslators = [];
    private $attributeMatchingTranslators = [];
    public function __construct(\Sensei\ThirdParty\Symfony\Component\CssSelector\Parser\ParserInterface $parser = null)
    {
        $this->mainParser = $parser ?? new \Sensei\ThirdParty\Symfony\Component\CssSelector\Parser\Parser();
        $this->registerExtension(new \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\NodeExtension())->registerExtension(new \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\CombinationExtension())->registerExtension(new \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\FunctionExtension())->registerExtension(new \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\PseudoClassExtension())->registerExtension(new \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\AttributeMatchingExtension());
    }
    public static function getXpathLiteral(string $element) : string
    {
        if (!\str_contains($element, "'")) {
            return "'" . $element . "'";
        }
        if (!\str_contains($element, '"')) {
            return '"' . $element . '"';
        }
        $string = $element;
        $parts = [];
        while (\true) {
            if (\false !== ($pos = \strpos($string, "'"))) {
                $parts[] = \sprintf("'%s'", \substr($string, 0, $pos));
                $parts[] = "\"'\"";
                $string = \substr($string, $pos + 1);
            } else {
                $parts[] = "'{$string}'";
                break;
            }
        }
        return \sprintf('concat(%s)', \implode(', ', $parts));
    }
    /**
     * {@inheritdoc}
     */
    public function cssToXPath(string $cssExpr, string $prefix = 'descendant-or-self::') : string
    {
        $selectors = $this->parseSelectors($cssExpr);
        /** @var SelectorNode $selector */
        foreach ($selectors as $index => $selector) {
            if (null !== $selector->getPseudoElement()) {
                throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException('Pseudo-elements are not supported.');
            }
            $selectors[$index] = $this->selectorToXPath($selector, $prefix);
        }
        return \implode(' | ', $selectors);
    }
    /**
     * {@inheritdoc}
     */
    public function selectorToXPath(\Sensei\ThirdParty\Symfony\Component\CssSelector\Node\SelectorNode $selector, string $prefix = 'descendant-or-self::') : string
    {
        return ($prefix ?: '') . $this->nodeToXPath($selector);
    }
    /**
     * @return $this
     */
    public function registerExtension(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\ExtensionInterface $extension) : self
    {
        $this->extensions[$extension->getName()] = $extension;
        $this->nodeTranslators = \array_merge($this->nodeTranslators, $extension->getNodeTranslators());
        $this->combinationTranslators = \array_merge($this->combinationTranslators, $extension->getCombinationTranslators());
        $this->functionTranslators = \array_merge($this->functionTranslators, $extension->getFunctionTranslators());
        $this->pseudoClassTranslators = \array_merge($this->pseudoClassTranslators, $extension->getPseudoClassTranslators());
        $this->attributeMatchingTranslators = \array_merge($this->attributeMatchingTranslators, $extension->getAttributeMatchingTranslators());
        return $this;
    }
    /**
     * @throws ExpressionErrorException
     */
    public function getExtension(string $name) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\Extension\ExtensionInterface
    {
        if (!isset($this->extensions[$name])) {
            throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException(\sprintf('Extension "%s" not registered.', $name));
        }
        return $this->extensions[$name];
    }
    /**
     * @return $this
     */
    public function registerParserShortcut(\Sensei\ThirdParty\Symfony\Component\CssSelector\Parser\ParserInterface $shortcut) : self
    {
        $this->shortcutParsers[] = $shortcut;
        return $this;
    }
    /**
     * @throws ExpressionErrorException
     */
    public function nodeToXPath(\Sensei\ThirdParty\Symfony\Component\CssSelector\Node\NodeInterface $node) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        if (!isset($this->nodeTranslators[$node->getNodeName()])) {
            throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException(\sprintf('Node "%s" not supported.', $node->getNodeName()));
        }
        return $this->nodeTranslators[$node->getNodeName()]($node, $this);
    }
    /**
     * @throws ExpressionErrorException
     */
    public function addCombination(string $combiner, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\NodeInterface $xpath, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\NodeInterface $combinedXpath) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        if (!isset($this->combinationTranslators[$combiner])) {
            throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException(\sprintf('Combiner "%s" not supported.', $combiner));
        }
        return $this->combinationTranslators[$combiner]($this->nodeToXPath($xpath), $this->nodeToXPath($combinedXpath));
    }
    /**
     * @throws ExpressionErrorException
     */
    public function addFunction(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\FunctionNode $function) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        if (!isset($this->functionTranslators[$function->getName()])) {
            throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException(\sprintf('Function "%s" not supported.', $function->getName()));
        }
        return $this->functionTranslators[$function->getName()]($xpath, $function);
    }
    /**
     * @throws ExpressionErrorException
     */
    public function addPseudoClass(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $pseudoClass) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        if (!isset($this->pseudoClassTranslators[$pseudoClass])) {
            throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException(\sprintf('Pseudo-class "%s" not supported.', $pseudoClass));
        }
        return $this->pseudoClassTranslators[$pseudoClass]($xpath);
    }
    /**
     * @throws ExpressionErrorException
     */
    public function addAttributeMatching(\Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr $xpath, string $operator, string $attribute, ?string $value) : \Sensei\ThirdParty\Symfony\Component\CssSelector\XPath\XPathExpr
    {
        if (!isset($this->attributeMatchingTranslators[$operator])) {
            throw new \Sensei\ThirdParty\Symfony\Component\CssSelector\Exception\ExpressionErrorException(\sprintf('Attribute matcher operator "%s" not supported.', $operator));
        }
        return $this->attributeMatchingTranslators[$operator]($xpath, $attribute, $value);
    }
    /**
     * @return SelectorNode[]
     */
    private function parseSelectors(string $css) : array
    {
        foreach ($this->shortcutParsers as $shortcut) {
            $tokens = $shortcut->parse($css);
            if (!empty($tokens)) {
                return $tokens;
            }
        }
        return $this->mainParser->parse($css);
    }
}
