<?php

/**
 * AnotherName
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by woocommerce on 27-March-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1\Maps;

use Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1;

/**
 * AnotherName
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class AnotherName
{
    const MAP = [
        'type' => ASN1::TYPE_SEQUENCE,
        'children' => [
            'type-id' => ['type' => ASN1::TYPE_OBJECT_IDENTIFIER],
            'value' => [
                'type' => ASN1::TYPE_ANY,
                'constant' => 0,
                'optional' => true,
                'explicit' => true
            ]
        ]
    ];
}