<?php

namespace OM4\WooCommerceZapier\Webhook;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Webhook\DataStore as WebhookDataStore;

defined( 'ABSPATH' ) || exit;

/**
 * Webhook-related functionality during plugin activation and deactivation:
 * - Pauses existing webhooks during deactivation.
 * - Re-activated paused webhooks during plugin reactivation.
 *
 * @since 2.0.0
 */
class Installer {

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * WebhookDataStore instance.
	 *
	 * @var WebhookDataStore
	 */
	protected $webhook_data_store;

	/**
	 * Constructor.
	 *
	 * @param Logger           $logger             The Logger.
	 * @param WebhookDataStore $webhook_data_store WebhookDataStore instance.
	 */
	public function __construct( Logger $logger, WebhookDataStore $webhook_data_store ) {
		$this->logger             = $logger;
		$this->webhook_data_store = $webhook_data_store;
	}

	/**
	 * Instructs the installer functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_action( 'wc_zapier_plugin_deactivate', array( $this, 'pause_zapier_webhooks' ) );
		add_action( 'wc_zapier_db_upgrade_v_9_to_10', array( $this, 'unpause_zapier_webhooks' ) );
	}

	/**
	 * When a user deactivates the plugin, pause any existing Zapier webhooks so that no data is sent to them
	 * while the plugin is deactivated.
	 *
	 * @return void
	 */
	public function pause_zapier_webhooks() {
		$webhooks = $this->webhook_data_store->get_active_zapier_webhooks();

		if ( empty( $webhooks ) ) {
			return;
		}

		foreach ( $webhooks as $webhook ) {
			$webhook->set_status( 'paused' );
			$webhook->save();
			$this->logger->info( 'Active Webhook ID %d (%s) set to paused.', array( $webhook->get_id(), $webhook->get_name() ) );
		}
		$this->logger->info( '%d active webhook(s) paused.', array( count( $webhooks ) ) );
	}

	/**
	 * When a user activates the plugin, unpause any paused Zapier webhooks so that data will be
	 * (once again) sent to them.
	 *
	 * @return void
	 */
	public function unpause_zapier_webhooks() {
		$webhooks = $this->webhook_data_store->get_paused_zapier_webhooks();

		if ( empty( $webhooks ) ) {
			return;
		}

		foreach ( $webhooks as $webhook ) {
			$webhook->set_status( 'active' );
			$webhook->save();
			$this->logger->info( 'Paused Webhook ID %d (%s) set to active.', array( $webhook->get_id(), $webhook->get_name() ) );
		}
		$this->logger->info( '%d paused webhook(s) reactivated.', array( count( $webhooks ) ) );
	}
}
