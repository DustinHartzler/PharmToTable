<?php

/**
 * RSASSA_PSS_params
 *
 * As defined in https://tools.ietf.org/html/rfc4055#section-3.1
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by woocommerce on 12-April-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1\Maps;

use Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1;

/**
 * RSASSA_PSS_params
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class RSASSA_PSS_params
{
    const MAP = [
        'type' => ASN1::TYPE_SEQUENCE,
        'children' => [
            'hashAlgorithm' => [
                'constant' => 0,
                'optional' => true,
                'explicit' => true,
                //'default'  => 'sha1Identifier'
            ] + HashAlgorithm::MAP,
            'maskGenAlgorithm' => [
                'constant' => 1,
                'optional' => true,
                'explicit' => true,
                //'default'  => 'mgf1SHA1Identifier'
            ] + MaskGenAlgorithm::MAP,
            'saltLength' => [
                'type' => ASN1::TYPE_INTEGER,
                'constant' => 2,
                'optional' => true,
                'explicit' => true,
                'default' => 20
            ],
            'trailerField' => [
                'type' => ASN1::TYPE_INTEGER,
                'constant' => 3,
                'optional' => true,
                'explicit' => true,
                'default' => 1
            ]
        ]
    ];
}
