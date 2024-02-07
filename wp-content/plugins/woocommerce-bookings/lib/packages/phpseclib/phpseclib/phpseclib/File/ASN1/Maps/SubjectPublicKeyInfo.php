<?php

/**
 * SubjectPublicKeyInfo
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by woocommerce on 29-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1\Maps;

use Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1;

/**
 * SubjectPublicKeyInfo
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class SubjectPublicKeyInfo
{
    const MAP = [
        'type' => ASN1::TYPE_SEQUENCE,
        'children' => [
            'algorithm' => AlgorithmIdentifier::MAP,
            'subjectPublicKey' => ['type' => ASN1::TYPE_BIT_STRING]
        ]
    ];
}
