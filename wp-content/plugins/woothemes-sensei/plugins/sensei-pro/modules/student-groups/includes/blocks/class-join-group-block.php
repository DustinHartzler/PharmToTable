<?php
/**
 * File containing the Join_Group_Block class.
 *
 * @package student-groups
 */

namespace Sensei_Pro_Student_Groups\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Join Group Block.
 *
 * @since 1.18.0
 */
class Join_Group_Block {
	/**
	 * Join_Group_Block instance.
	 *
	 * @var Join_Group_Block
	 */
	private static $instance;

	/**
	 * Script and stylesheet loading.
	 *
	 * @var Components_Provider
	 */
	private $assets;

	/**
	 * Creates instance of Join_Group_Block.
	 *
	 * @return Join_Group_Block
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Join_Group_Block constructor.
	 */
	private function __construct() {}

	/**
	 * Initialize hooks.
	 *
	 * @param Components_Provider $assets Assets provider.
	 */
	public function init( $assets ) {
		$this->assets = $assets;

		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Enqueue block assets.
	 *
	 * @internal
	 */
	public function enqueue_block_assets() {
		$this->assets->enqueue_component( 'join-group-block' );
	}

	/**
	 * Register block.
	 *
	 * @internal
	 */
	public function register_block() {
		register_block_type_from_metadata(
			SENSEI_PRO_PLUGIN_DIR_PATH . 'modules/student-groups/assets/blocks/join-group-block/'
		);
	}
}
