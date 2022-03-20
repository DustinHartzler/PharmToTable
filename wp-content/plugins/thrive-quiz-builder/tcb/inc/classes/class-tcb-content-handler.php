<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
if ( ! class_exists( 'TCB_Landing_Page_Cloud_Templates_Api' ) ) {
	require_once TVE_TCB_ROOT_PATH . 'landing-page/inc/TCB_Landing_Page_Transfer.php';
}

class TCB_Content_Handler extends TCB_Landing_Page_Transfer {
	/**
	 * Zip data
	 *
	 * @var string
	 */
	protected $cfg_name = 'tve_content.json';
	protected $html_file_name = 'tve_content.html';
	protected $archive_prefix = 'tve-content-';
	/**
	 * hold a copy of the config array for the import process
	 *
	 * @var array
	 */
	private $import_config = array();
	/**
	 * holds the current WP Page (or landing page)
	 *
	 * @var WP_Post
	 */
	private $current_page = null;
	/**
	 * Whether or not LP vars should be replaced by their values on export
	 */
	protected $allow_lp_vars = true;

	/**
	 * Whether or not we expect a LP to be imported
	 *
	 * @return bool
	 */
	protected function should_check_lp_page() {
		return false;
	}

	public function get_cfg_name() {
		return $this->cfg_name;
	}

	public function get_html_file_name() {
		return $this->html_file_name;
	}

	public function import( $file, $page_id ) {
		$this->importValidateFile( $file );

		$zip = new ZipArchive();
		if ( $zip->open( $file ) !== true ) {
			throw new Exception( __( 'Could not open the archive file', 'thrive-cb' ) );
		}
		/* 1. read config & validate */
		$this->import_config = $config = $this->importReadConfig( $zip );
		$this->current_page  = get_post( $page_id );

		/* 2. import all the images (add them as attachments) and store the links in the config array */
		$image_map = $this->importImages( $config, $zip );

		/* 3. import all lightboxes (create new posts with type tcb_lightbox) */
		$lightbox_id_map = $this->importLightboxes( $config, $image_map );

		/* 4. replace images from config*/
		$this->importParseImageLinks( $config, $image_map );
		/* 5. get content*/
		$page_content = $zip->getFromName( $this->html_file_name );
		/* 6. replace images & lightboxes from content*/
		$this->importParseImageLinks( $page_content, $image_map );

		$this->importReplaceLightboxIds( $page_content, $lightbox_id_map );

		if ( ! empty( $config['lightbox'] ) ) {
			foreach ( $config['lightbox'] as $old_id => $data ) {
				$lb_content = $zip->getFromName( 'lightboxes/' . $old_id . '.html' );
				$this->importReplaceLightboxIds( $lb_content, $lightbox_id_map );
				$this->importParseImageLinks( $lb_content, $image_map );
				update_post_meta( $lightbox_id_map[ $old_id ], 'tve_updated_post', $lb_content );
			}
		}

		return [
			'content'    => $page_content,
			'inline_css' => $config['tve_custom_css'],
			'custom_css' => $config['tve_user_custom_css'],
		];
	}
}
