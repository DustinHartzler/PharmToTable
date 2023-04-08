<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sensei\ThirdParty\Symfony\Component\CssSelector\Node;

/**
 * Represents a "<selector>:not(<identifier>)" node.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class NegationNode extends \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\AbstractNode
{
    private $selector;
    private $subSelector;
    public function __construct(\Sensei\ThirdParty\Symfony\Component\CssSelector\Node\NodeInterface $selector, \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\NodeInterface $subSelector)
    {
        $this->selector = $selector;
        $this->subSelector = $subSelector;
    }
    public function getSelector() : \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\NodeInterface
    {
        return $this->selector;
    }
    public function getSubSelector() : \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\NodeInterface
    {
        return $this->subSelector;
    }
    /**
     * {@inheritdoc}
     */
    public function getSpecificity() : \Sensei\ThirdParty\Symfony\Component\CssSelector\Node\Specificity
    {
        return $this->selector->getSpecificity()->plus($this->subSelector->getSpecificity());
    }
    public function __toString() : string
    {
        return \sprintf('%s[%s:not(%s)]', $this->getNodeName(), $this->selector, $this->subSelector);
    }
}
