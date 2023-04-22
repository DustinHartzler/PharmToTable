<?php

declare (strict_types=1);
namespace OM4\WooCommerceZapier\Vendor\League\Container\Argument;

interface ResolvableArgumentInterface extends ArgumentInterface
{
    public function getValue() : string;
}
