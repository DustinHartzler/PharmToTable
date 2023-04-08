<?php
/**
 * @license MIT
 *
 * Modified by woocommerce on 27-March-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Automattic\WooCommerce\Bookings\Vendor\Monolog\Handler;

use Gelf\PublisherInterface;
use Automattic\WooCommerce\Bookings\Vendor\Monolog\Logger;
use Automattic\WooCommerce\Bookings\Vendor\Monolog\Formatter\GelfMessageFormatter;
use Automattic\WooCommerce\Bookings\Vendor\Monolog\Formatter\FormatterInterface;

/**
 * Handler to send messages to a Graylog2 (http://www.graylog2.org) server
 *
 * @author Matt Lehner <mlehner@gmail.com>
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 */
class GelfHandler extends AbstractProcessingHandler
{
    /**
     * @var PublisherInterface the publisher object that sends the message to the server
     */
    protected $publisher;

    /**
     * @param PublisherInterface $publisher a gelf publisher object
     */
    public function __construct(PublisherInterface $publisher, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->publisher = $publisher;
    }

    /**
     * {@inheritDoc}
     */
    protected function write(array $record): void
    {
        $this->publisher->publish($record['formatted']);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new GelfMessageFormatter();
    }
}
