<?php

/**
 * BasicConstraints
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
 * BasicConstraints
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class BasicConstraints
{
    const MAP = [
        'type' => ASN1::TYPE_SEQUENCE,
        'children' => [
            'cA' => [
                'type' => ASN1::TYPE_BOOLEAN,
                'optional' => true,
                'default' => false
            ],
            'pathLenConstraint' => [
                'type' => ASN1::TYPE_INTEGER,
                'optional' => true
            ]
        ]
    ];
}
