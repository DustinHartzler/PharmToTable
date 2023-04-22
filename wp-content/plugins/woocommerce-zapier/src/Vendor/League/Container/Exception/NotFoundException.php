<?php

declare (strict_types=1);
namespace OM4\WooCommerceZapier\Vendor\League\Container\Exception;

use OM4\WooCommerceZapier\Vendor\Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;
class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
