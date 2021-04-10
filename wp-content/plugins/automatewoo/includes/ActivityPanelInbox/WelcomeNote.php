<?php

namespace AutomateWoo\ActivityPanelInbox;

use AutomateWoo\Admin;
use Automattic\WooCommerce\Admin\Notes\NoteTraits;
use Automattic\WooCommerce\Admin\Notes\Note;

/**
 * Add the Welcome note on first install and remove it if the plugin is deactivated.
 *
 * @package AutomateWoo\ActivityPanelInbox
 * @since 5.1.0
 */
class WelcomeNote {

	use NoteTraits;

	const NOTE_NAME = 'automatewoo-welcome';

	/**
	 * Init the hooks for the note.
	 */
	public static function init() {
		add_action( 'automatewoo_first_installed', [ __CLASS__, 'possibly_add_note' ] );
		register_deactivation_hook( AUTOMATEWOO_FILE, [ __CLASS__, 'possibly_delete_note' ] );
	}

	/**
	 * Get the note.
	 */
	public static function get_note() {
		$note = new Note();
		$note->set_type( Note::E_WC_ADMIN_NOTE_INFORMATIONAL );
		$note->set_name( self::NOTE_NAME );
		$note->set_source( 'automatewoo' );
		$note->set_title( __( 'AutomateWoo is ready', 'automatewoo' ) );
		$note->set_content(
			__(
				'Create your first automated workflow easily with our presets, or build your own from scratch.',
				'automatewoo'
			)
		);
		$note->add_action(
			'presets',
			__( 'Create workflow', 'automatewoo' ),
			Admin::page_url( 'workflow-presets' )
		);

		return $note;
	}
}
