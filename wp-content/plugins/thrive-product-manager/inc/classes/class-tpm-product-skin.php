<?php

/**
 * Class TPM_Product_Skin
 * Beside Plugins and Themes TPM offers Thrive Theme Builder Skins
 * Which are handled by this class and are known as TTB Themes
 */
class TPM_Product_Skin extends TPM_Product_Theme {

	/**
	 * @var WP_Term
	 */
	protected $ttb_skin;

	/**
	 * @var [WP_Term]
	 */
	protected $ttb_downloaded_skins = array();

	/**
	 * @return array
	 */
	public function to_array() {

		$data         = parent::to_array();
		$data['type'] = 'skin';

		return $data;
	}

	/**
	 * Download and install the current skin
	 * - TTB API is used which TTB is required to be installed and activated
	 *
	 * @param array $credentials
	 *
	 * @return bool|WP_Error
	 * @see activate()
	 *
	 */
	public function install( $credentials ) {

		$response = $this->_install_ttb_skin( $this->api_slug );

		if ( true === $response instanceof WP_Term ) {
			$this->ttb_skin = $response;
		}

		return $response;
	}


	/**
	 * Uses TTB to activate current skin
	 *
	 * @return bool|WP_Error
	 */
	public function activate() {

		if ( $this->is_activated() ) {
			return true;
		}

		$skin = $this->_get_ttb_skin();

		if ( false === $skin instanceof WP_Term ) {
			return new WP_Error( 400, sprintf( __( 'Could not activate skin: %s', Thrive_Product_Manager::T ), $this->name ) );
		}

		$skin_id = $skin->term_id;

		Thrive_Skin_Taxonomy::set_skin_active( $skin_id );

		/* We need to make sure that the instance is the one with the active skin before we generate the css file */
		thrive_skin( $skin_id )->generate_style_file();

		return true;
	}

	/**
	 * Checks if the skin is installed/downloaded
	 * and can be activated(set as active)
	 *
	 * @return bool
	 */
	public function is_installed() {

		$installed = false;
		$skins     = $this->_get_all_downloaded_skins();

		foreach ( $skins as $skin ) {
			if ( ! empty( $skin->tag ) && $skin->tag === $this->api_slug ) {
				$installed = true;
				break;
			}
		}

		return $installed;
	}

	/**
	 * @return WP_Term[]
	 */
	protected function _get_all_downloaded_skins() {

		if ( false === class_exists( 'Thrive_Skin_Taxonomy', false ) ) {
			return $this->ttb_downloaded_skins;
		}

		if ( empty( $this->ttb_downloaded_skins ) ) {
			$this->ttb_downloaded_skins = Thrive_Skin_Taxonomy::get_all();
		}

		return $this->ttb_downloaded_skins;
	}

	/**
	 * Gets the current Thrive_Skin instance
	 * - instance exists if the current flow is install one
	 *
	 * @return WP_Term|null
	 */
	protected function _get_ttb_skin() {

		if ( false === $this->ttb_skin instanceof WP_Term ) {
			return null;
		}

		return $this->ttb_skin;
	}

	/**
	 * Uses TTB API to install a skin
	 *
	 * @param $skin_id
	 *
	 * @return WP_Term|WP_Error on error
	 */
	protected function _install_ttb_skin( $skin_id ) {

		if ( false === class_exists( 'Thrive_Theme_Cloud_Api_Factory', false ) ) {
			return new WP_Error( 400, sprintf( __( 'Could not install Theme: %s', Thrive_Product_Manager::T ), $this->name ) );
		}

		try {
			/**
			 * Try to create some default data for TTB cos it ain't created on try_install_activate request for TTB
			 */
			if ( class_exists( 'Thrive_Theme_Default_Data', false ) ) {
				Thrive_Theme_Default_Data::create_default();
			}

			$zip      = Thrive_Theme_Cloud_Api_Factory::build( 'skins' )->download_item( $skin_id, null );
			$import   = new Thrive_Transfer_Import( $zip );
			$response = $import->import( 'skin' );
		} catch ( Exception $e ) {
			$response = new WP_Error( 400, $e->getMessage() );
		}

		return $response;
	}

	/**
	 * Checks if current skin is active for TTB
	 *
	 * @return bool
	 */
	public function is_activated() {

		$skin_activated = false;

		if ( class_exists( 'Thrive_Skin', false ) ) {
			$current_skin   = new Thrive_Skin( 0 ); //current active skin in TTB
			$skin_activated = $current_skin->get_tag() === $this->api_slug;
		}

		return $skin_activated;
	}

	public function get_status() {

		if ( ! empty( $this->status ) ) {
			return $this->status;
		}

		if ( ! $this->is_purchased() ) {
			$this->status = self::AVAILABLE;

			return $this->status;
		}

		if ( ! $this->is_licensed() ) {
			$this->status = self::TO_LICENSE;

			return $this->status;
		}

		if ( ! $this->is_installed() ) {
			$this->status = self::TO_INSTALL;

			return $this->status;
		}

		if ( ! $this->is_activated() ) {
			$this->status = self::TO_ACTIVATE;

			return $this->status;
		}

		$this->status = self::READY;

		return $this->status;
	}
}
