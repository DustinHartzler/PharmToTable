<?php

class TPM_Product_Theme_Builder extends TPM_Product_Theme {

	public function to_array() {

		$data           = parent::to_array();
		$data['type']   = 'theme';
		$data['hidden'] = true;

		return $data;
	}

	/**
	 * Finds out URL where to download the zip from
	 *
	 * @param string $api_slug
	 *
	 * @return WP_Error|string url
	 */
	protected function _get_download_url( $api_slug ) {

		$request = array(
			'sslverify' => false,
			'body'      => array(
				'action' => 'theme_update',
				'type'   => 'latest',
			),
			'timeout'   => 60,
		);

		$thrive_update_api_url = add_query_arg( array(
			'p' => $this->_get_hash( $request['body'] ),
		), 'http://service-api.thrivethemes.com/theme/update' );

		$result = wp_remote_get( $thrive_update_api_url, $request );

		if ( ! is_wp_error( $result ) ) {
			$info = @unserialize( wp_remote_retrieve_body( $result ) );

			return ! empty( $info['package'] ) ? $info['package'] : new WP_Error( '404', 'Bad request' );
		}

		return new WP_Error( '400', $result->get_error_message() );
	}

	/**
	 * Activates TTB
	 *
	 * @return bool|WP_Error
	 */
	public function activate() {

		$activated = $this->is_activated();

		if ( ! $activated && $this->is_installed() ) {
			$theme = wp_get_theme( 'thrive-theme' );

			$activated = true;

			switch_theme( $theme->get_stylesheet() );
		}

		return $activated;
	}

	/**
	 * Used in frontend/js
	 *
	 * @return string
	 */
	public static function get_dashboard_url() {

		return admin_url( 'admin.php?page=thrive-theme-dashboard' );
	}

	/**
	 * Check if the TTB with slug thrive-theme is installed
	 *
	 * @return bool
	 */
	public function is_installed() {

		$theme = wp_get_theme( 'thrive-theme' );

		return ! is_wp_error( $theme->errors() );
	}
}
