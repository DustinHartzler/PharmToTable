<?php

declare (strict_types=1);
namespace OM4\WooCommerceZapier\Vendor\League\Container\Argument\Literal;

use OM4\WooCommerceZapier\Vendor\League\Container\Argument\LiteralArgument;
class IntegerArgument extends LiteralArgument
{
    public function __construct(int $value)
    {
        parent::__construct($value, LiteralArgument::TYPE_INT);
    }
}
