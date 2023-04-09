<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit50c32885f6c5ed40ae9661d67037ef70
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\Caching\\SimpleStringCache' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/Caching/SimpleStringCache.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\CssInliner' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/CssInliner.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\Css\\CssDocument' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/Css/CssDocument.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\Css\\StyleRule' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/Css/StyleRule.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\HtmlProcessor\\AbstractHtmlProcessor' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/HtmlProcessor/AbstractHtmlProcessor.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\HtmlProcessor\\CssToAttributeConverter' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/HtmlProcessor/CssToAttributeConverter.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\HtmlProcessor\\HtmlNormalizer' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/HtmlProcessor/HtmlNormalizer.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\HtmlProcessor\\HtmlPruner' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/HtmlProcessor/HtmlPruner.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\Utilities\\ArrayIntersector' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/Utilities/ArrayIntersector.php',
        'Sensei\\ThirdParty\\Pelago\\Emogrifier\\Utilities\\CssConcatenator' => __DIR__ . '/../..' . '/third-party/pelago/emogrifier/src/Utilities/CssConcatenator.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\CSSList\\AtRuleBlockList' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/CSSList/AtRuleBlockList.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\CSSList\\CSSBlockList' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/CSSList/CSSBlockList.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\CSSList\\CSSList' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/CSSList/CSSList.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\CSSList\\Document' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/CSSList/Document.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\CSSList\\KeyFrame' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/CSSList/KeyFrame.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Comment\\Comment' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Comment/Comment.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Comment\\Commentable' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Comment/Commentable.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\OutputFormat' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/OutputFormat.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\OutputFormatter' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/OutputFormatter.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Parser' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Parser.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Parsing\\OutputException' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Parsing/OutputException.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Parsing\\ParserState' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Parsing/ParserState.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Parsing\\SourceException' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Parsing/SourceException.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Parsing\\UnexpectedEOFException' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Parsing/UnexpectedEOFException.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Parsing\\UnexpectedTokenException' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Parsing/UnexpectedTokenException.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Property\\AtRule' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Property/AtRule.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Property\\CSSNamespace' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Property/CSSNamespace.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Property\\Charset' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Property/Charset.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Property\\Import' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Property/Import.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Property\\KeyframeSelector' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Property/KeyframeSelector.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Property\\Selector' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Property/Selector.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Renderable' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Renderable.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\RuleSet\\AtRuleSet' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/RuleSet/AtRuleSet.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\RuleSet\\DeclarationBlock' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/RuleSet/DeclarationBlock.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\RuleSet\\RuleSet' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/RuleSet/RuleSet.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Rule\\Rule' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Rule/Rule.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Settings' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Settings.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\CSSFunction' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/CSSFunction.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\CSSString' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/CSSString.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\CalcFunction' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/CalcFunction.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\CalcRuleValueList' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/CalcRuleValueList.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\Color' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/Color.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\LineName' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/LineName.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\PrimitiveValue' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/PrimitiveValue.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\RuleValueList' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/RuleValueList.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\Size' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/Size.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\URL' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/URL.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\Value' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/Value.php',
        'Sensei\\ThirdParty\\Sabberworm\\CSS\\Value\\ValueList' => __DIR__ . '/../..' . '/third-party/sabberworm/php-css-parser/src/Value/ValueList.php',
        'Sensei\\ThirdParty\\Stringable' => __DIR__ . '/../..' . '/third-party/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\CssSelectorConverter' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/CssSelectorConverter.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Exception\\ExceptionInterface' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Exception/ExceptionInterface.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Exception\\ExpressionErrorException' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Exception/ExpressionErrorException.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Exception\\InternalErrorException' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Exception/InternalErrorException.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Exception\\ParseException' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Exception/ParseException.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Exception\\SyntaxErrorException' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Exception/SyntaxErrorException.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\AbstractNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/AbstractNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\AttributeNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/AttributeNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\ClassNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/ClassNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\CombinedSelectorNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/CombinedSelectorNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\ElementNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/ElementNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\FunctionNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/FunctionNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\HashNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/HashNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\NegationNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/NegationNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\NodeInterface' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/NodeInterface.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\PseudoNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/PseudoNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\SelectorNode' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/SelectorNode.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Node\\Specificity' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Node/Specificity.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Handler\\CommentHandler' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Handler/CommentHandler.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Handler/HandlerInterface.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Handler\\HashHandler' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Handler/HashHandler.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Handler\\IdentifierHandler' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Handler/IdentifierHandler.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Handler\\NumberHandler' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Handler/NumberHandler.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Handler\\StringHandler' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Handler/StringHandler.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Handler\\WhitespaceHandler' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Handler/WhitespaceHandler.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Parser' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Parser.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\ParserInterface' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/ParserInterface.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Reader' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Reader.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Shortcut\\ClassParser' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Shortcut/ClassParser.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Shortcut\\ElementParser' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Shortcut/ElementParser.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Shortcut\\EmptyStringParser' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Shortcut/EmptyStringParser.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Shortcut\\HashParser' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Shortcut/HashParser.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Token' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Token.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\TokenStream' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/TokenStream.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\Tokenizer' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Tokenizer/Tokenizer.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\TokenizerEscaping' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Tokenizer/TokenizerEscaping.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\TokenizerPatterns' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/Parser/Tokenizer/TokenizerPatterns.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Extension\\AbstractExtension' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Extension/AbstractExtension.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Extension\\AttributeMatchingExtension' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Extension/AttributeMatchingExtension.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Extension\\CombinationExtension' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Extension/CombinationExtension.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Extension\\ExtensionInterface' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Extension/ExtensionInterface.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Extension\\FunctionExtension' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Extension/FunctionExtension.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Extension\\HtmlExtension' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Extension/HtmlExtension.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Extension\\NodeExtension' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Extension/NodeExtension.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Extension\\PseudoClassExtension' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Extension/PseudoClassExtension.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\Translator' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/Translator.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\TranslatorInterface' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/TranslatorInterface.php',
        'Sensei\\ThirdParty\\Symfony\\Component\\CssSelector\\XPath\\XPathExpr' => __DIR__ . '/../..' . '/third-party/symfony/css-selector/XPath/XPathExpr.php',
        'Sensei\\ThirdParty\\Symfony\\Polyfill\\Php80\\Php80' => __DIR__ . '/../..' . '/third-party/symfony/polyfill-php80/Php80.php',
        'Sensei\\ThirdParty\\ValueError' => __DIR__ . '/../..' . '/third-party/symfony/polyfill-php80/Resources/stubs/ValueError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit50c32885f6c5ed40ae9661d67037ef70::$classMap;

        }, null, ClassLoader::class);
    }
}