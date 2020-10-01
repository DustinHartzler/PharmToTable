<?php

class Thrive_Dash_List_Connection_FileUpload_GoogleDrive
	extends Thrive_Dash_List_Connection_Abstract
	implements Thrive_Dash_List_Connection_FileUpload_Interface {

	public static function getType() {
		return 'storage';
	}

	public function getTitle() {
		return 'Google Drive';
	}

	/**
	 * Builds an authorization URI - the user will be redirected to that URI and asked to give app access
	 *
	 * @return string
	 */
	public function getAuthorizeUrl() {
		$this->save(); // save the client_id and client_secret for later use

		return $this->getApi()->get_authorize_url();
	}

	/**
	 * whether or not this list is connected to the service (has been authenticated)
	 *
	 * @return bool
	 */
	public function isConnected() {
		return $this->param( 'access_token' ) && $this->param( 'refresh_token' );
	}

	public function outputSetupForm() {
		$this->_directFormHtml( 'google-drive' );
	}

	/**
	 * Called during the redirect from google oauth flow
	 *
	 * _REQUEST contains a `code` parameter which needs to be sent back to g.api in exchange for an access token
	 *
	 * @return bool|mixed|string|Thrive_Dash_List_Connection_Abstract
	 */
	public function readCredentials() {
		$code = empty( $_REQUEST['code'] ) ? '' : $_REQUEST['code'];

		if ( empty( $code ) ) {
			return $this->error( 'Missing `code` parameter' );
		}

		try {
			/* get access token from googleapis */
			$response = $this->getApi()->get_access_token( $code );
			if ( empty( $response['access_token'] ) ) {
				throw new Thrive_Dash_Api_Google_Exception( 'Missing token from response data' );
			}
			$this->_credentials = array(
				'client_id'     => $this->param( 'client_id' ),
				'client_secret' => $this->param( 'client_secret' ),
				'access_token'  => $response['access_token'],
				'expires_at'    => time() + $response['expires_in'],
				'refresh_token' => $response['refresh_token'],
			);
			$this->save();
		} catch ( Thrive_Dash_Api_Google_Exception $e ) {

			echo 'caught ex: ' . $e->getMessage();
			$this->_credentials = array();
			$this->save();

			$this->error( $e->getMessage() );

			return false;
		}

		return true;
	}

	public function testConnection() {

		$result = array(
			'success' => true,
			'message' => __( 'Connection works', TVE_DASH_TRANSLATE_DOMAIN ),
		);
		try {
			$this->getApi()->get_files();
		} catch ( Thrive_Dash_Api_Google_Exception $e ) {
			$result['success'] = false;
			$result['message'] = $e->getMessage();
		}

		return $result;
	}

	/**
	 * Upload a file to the storage
	 *
	 * @param string $file_contents contents of the uploaded file
	 * @param string $folder_id     folder identification
	 * @param array  $metadata      file metadata props, such as name
	 *
	 * @return string|WP_Error stored file id or WP_Error if any exceptions occured
	 */
	public function upload( $file_contents, $folder_id, $metadata ) {
		$metadata['parents'] = array( $folder_id );

		try {
			$file = $this->getApi()->multipart_upload( $file_contents, $metadata );
		} catch ( Thrive_Dash_Api_Google_Exception $e ) {
			return new WP_Error( 'tcb_file_upload_error', $e->getMessage() );
		}

		return $file['id'];
	}

	/**
	 * Deletes an uploaded file
	 *
	 * @param string $file_id
	 *
	 * @return true|WP_Error
	 */
	public function delete( $file_id ) {
		try {
			$this->getApi()->delete( $file_id );
			$result = true;
		} catch ( Thrive_Dash_Api_Google_Exception $e ) {
			$result = new WP_Error( 'tcb_file_upload_error', $e->getMessage() );
		}

		return $result;
	}

	/**
	 * Retrieve the full URL to a file stored on drive
	 *
	 * @param string $file_id
	 *
	 * @return array containing URL and original name
	 */
	public function get_file_data( $file_id ) {

		// fallback to a default representation of a google file url
		$data = array(
			'url'  => sprintf( 'https://drive.google.com/file/d/%s/view?usp=drivesdk', $file_id ),
			'name' => $file_id,
		);

		try {
			$file = $this->getApi()->get_file( $file_id, 'webViewLink,name' );
			if ( ! empty( $file['webViewLink'] ) ) {
				$data['url']  = $file['webViewLink'];
				$data['name'] = $file['name'];
			}
		} catch ( Thrive_Dash_Api_Google_Exception $e ) {
		}

		return $data;
	}

	/**
	 * Rename an uploaded file by applying a callback function on its name
	 * The callback function should return the new filename
	 *
	 * @param string   $file_id  file ID from google
	 * @param callable $callback function to apply to get the new filename
	 *
	 * @return array information about the renamed file
	 */
	public function rename_file( $file_id, $callback ) {
		// fallback to a default representation of a google file url
		$file = $this->get_file_data( $file_id );

		/* if file[name] is identical to file_id this means we could not retrieve actual filedata from the api */
		if ( ! is_callable( $callback ) || $file['name'] === $file_id ) {
			return $file;
		}

		try {
			$new_name = $callback( $file['name'] );
			if ( $new_name !== $file['name'] ) {
				$this->getApi()->update_file_metadata( $file_id, array(
					'name' => $new_name,
				) );
				$file['name'] = $new_name;
			}

		} catch ( Thrive_Dash_Api_Google_Exception $e ) {
		}

		return $file;
	}

	/**
	 * Instantiate the service and set any available data
	 *
	 * @return Thrive_Dash_Api_Google_Service
	 */
	protected function _apiInstance() {
		$api = new Thrive_Dash_Api_Google_Service(
			$this->param( 'client_id' ),
			$this->param( 'client_secret' ),
			$this->param( 'access_token' )
		);

		/* check for expired token and renew it */
		if ( $this->param( 'refresh_token' ) && $this->param( 'expires_at' ) && time() > (int) $this->param( 'expires_at' ) ) {
			$data                               = $api->refresh_access_token( $this->param( 'refresh_token' ) );
			$this->_credentials['access_token'] = $data['access_token'];
			$this->_credentials['expires_at']   = time() + $data['expires_in'];
			$this->save();
		}

		return $api;
	}

	protected function _getLists() {
	}

	public function addSubscriber( $list_identifier, $arguments ) {
	}
}
