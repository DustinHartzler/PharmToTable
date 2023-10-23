<?php

namespace OM4\WooCommerceZapier\WooCommerceResource;

use OM4\WooCommerceZapier\Webhook\Payload;
use OM4\WooCommerceZapier\Webhook\Trigger;
use WP_REST_Controller;

defined( 'ABSPATH' ) || exit;

/**
 * Interface for WooCommerce REST API Resource Definitions.
 *
 * @since 2.0.0
 * @since 2.8.0 Removed the `get_description()` method, as it is no longer used.
 */
interface Definition {
	/**
	 * Whether or not this Resource is enabled/available.
	 *
	 * @return bool
	 */
	public function is_enabled();

	/**
	 * Get this Resource's key (internal name/type).
	 *
	 * Must be a-z lowercase characters only, and in singular (non plural) form.
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	public function get_key();

	/**
	 * Get this Resource's display name.
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Get class name of the REST API Controller for this resource (FQN).
	 *
	 * This class must extend a WP_REST_Controller.
	 *
	 * @return class-string<WP_REST_Controller>
	 */
	public function get_controller_name();

	/**
	 * Get the REST API controller's REST API version.
	 *
	 * This is used to determine which REST API version to use when a webhook payload is delivered.
	 *
	 * @since 2.1.0
	 *
	 * @return int
	 */
	public function get_controller_rest_api_version();

	/**
	 * Get the name of this Resource's screen name where the metabox will be displayed.
	 * For custom post type based resources this is the name of this resource's Custom Post Type.
	 *
	 * @return string|null Name or null if metaboxes are not supported for this resource.
	 */
	public function get_metabox_screen_name();

	/**
	 * Get the custom trigger definitions that this resource supports.
	 *
	 * Set to an empty array when no custom triggers are defined in our plugin.
	 *
	 * Note: this does not include any default triggers that WooCommerce core defines on the resource.
	 *
	 * @since 2.1.0
	 *
	 * @return Trigger[]
	 */
	public function get_webhook_triggers();

	/**
	 * Modify webhook trigger.
	 *
	 * Modify already defined webhook trigger.
	 * For able to fix built-in or newly defined triggers.
	 *
	 * @since 2.7.0
	 *
	 * @param array $topic_hooks Topic hooks.
	 * @return array
	 */
	public function webhook_topic_hooks( $topic_hooks );

	/**
	 * Used to build payload for `woocommerce_webhook_payload` filter.
	 *
	 * @since 2.2.0
	 *
	 * @return Payload|null Payload or null if webhook payload not provided.
	 */
	public function get_webhook_payload();

	/**
	 * Get the edit URL to an individual resource object/record.
	 *
	 * @since 2.1.0
	 *
	 * @param int $resource_id Resource ID.
	 * @return string
	 */
	public function get_admin_url( $resource_id );

	/**
	 * Get an individual resource record/instance's unique identifier.
	 *
	 * @since 2.7.1
	 *
	 * @param mixed $object Object instance.
	 *
	 * @return int
	 */
	public function get_resource_id_from_object( $object );
}
