<?php

declare (strict_types=1);
namespace OM4\WooCommerceZapier\Vendor\League\Container\Argument\Literal;

use OM4\WooCommerceZapier\Vendor\League\Container\Argument\LiteralArgument;
class CallableArgument extends LiteralArgument
{
    public function __construct(callable $value)
    {
        parent::__construct($value, LiteralArgument::TYPE_CALLABLE);
    }
}
