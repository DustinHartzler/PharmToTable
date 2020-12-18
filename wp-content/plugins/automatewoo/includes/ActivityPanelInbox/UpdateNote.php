<?php

namespace AutomateWoo\ActivityPanelInbox;

use AutomateWoo\Admin;
use Automattic\WooCommerce\Admin\Notes\DataStore;
use Automattic\WooCommerce\Admin\Notes\NoteTraits;
use Automattic\WooCommerce\Admin\Notes\WC_Admin_Note;
use Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes;

/**
 * Add the Update note when updating from < $version to >= $version and remove
 * the note if the plugin is deactivated.
 * Also remove the Welcome note and any prior Update notes if present, to avoid
 * double notes.
 *
 * @package AutomateWoo\ActivityPanelInbox
 * @since 5.1.0
 */
class UpdateNote {

	use NoteTraits;

	const NOTE_NAME = 'automatewoo-update';

	/** @var string The version this notice relates to. */
	protected static $version = '5.1';

	/**
	 * Init the hooks for the note.
	 */
	public static function init() {
		add_action( 'automatewoo_version_changed', [ __CLASS__, 'maybe_add_activity_panel_inbox_note' ], 10, 2 );
		register_deactivation_hook( AUTOMATEWOO_FILE, [ __CLASS__, 'possibly_delete_note' ] );
	}

	/**
	 * Add the Update note if notes are enabled and it's the first update to $version or higher.
	 *
	 * @param string $old_version previously installed version.
	 * @param string $new_version newly updated version.
	 *
	 * @throws \Exception If the data store fails to load.
	 */
	public static function maybe_add_activity_panel_inbox_note( $old_version, $new_version ) {
		if ( ! class_exists( WC_Admin_Notes::class ) ) {
			return;
		}

		if ( ! class_exists( 'WC_Data_Store' ) ) {
			return;
		}

		// First install or already above $version.
		if ( '' === $old_version || version_compare( $old_version, self::$version, '>=' ) ) {
			return;
		}

		// Remove Welcome note if present.
		WelcomeNote::possibly_delete_note();

		// Remove older Update notes if present.
		self::possibly_delete_older_update_notes();

		self::possibly_add_note();
	}

	/**
	 * Get the note.
	 */
	public static function get_note() {
		$note = new WC_Admin_Note();
		$note->set_type( WC_Admin_Note::E_WC_ADMIN_NOTE_INFORMATIONAL );
		$note->set_name( self::NOTE_NAME );
		$note->set_source( 'automatewoo' );
		$note->set_title( __( 'Check out these new AutomateWoo presets', 'automatewoo' ) );
		$note->set_content(
			__(
				'Reach out to your customers and automate your work easily with these new preset workflows.',
				'automatewoo'
			)
		);
		$note->add_action(
			'presets',
			__( 'Browse presets', 'automatewoo' ),
			Admin::page_url( 'workflow-presets' )
		);
		$note->set_content_data(
			(object) [
				'version' => self::$version,
			]
		);

		return $note;
	}

	/**
	 * Find any older Update notes and remove them.
	 */
	public static function possibly_delete_older_update_notes() {
		/** @var DataStore $data_store */
		$data_store = \WC_Data_Store::load( 'admin-note' );
		$note_ids   = $data_store->get_notes_with_name( self::NOTE_NAME );

		foreach ( (array) $note_ids as $note_id ) {
			$note = WC_Admin_Notes::get_note( $note_id );
			if ( ! $note ) {
				continue;
			}
			$content_data = $note->get_content_data();
			$note_version = $content_data->version ?? 0;
			if ( version_compare( $note_version, self::$version, '<' ) ) {
				$note->delete();
			}
		}
	}
}
