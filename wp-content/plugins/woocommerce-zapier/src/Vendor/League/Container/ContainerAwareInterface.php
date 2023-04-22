<?php

declare (strict_types=1);
namespace OM4\WooCommerceZapier\Vendor\League\Container;

interface ContainerAwareInterface
{
    public function getContainer() : DefinitionContainerInterface;
    public function setContainer(DefinitionContainerInterface $container) : ContainerAwareInterface;
}
