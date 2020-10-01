<?php

class Thrive_Dash_List_Connection_MailPoet extends Thrive_Dash_List_Connection_Abstract {
	/**
	 * Return the connection type
	 *
	 * @return String
	 */
	public static function getType() {
		return 'autoresponder';
	}

	/**
	 * Return the connection email merge tag
	 *
	 * @return String
	 */
	public static function getEmailMergeTag() {
		/**
		 * Mailpoet 3 changed the shortcodes from user:email to subscriber:email
		 */
		if ( defined( 'MAILPOET_VERSION' ) && version_compare( MAILPOET_VERSION, '3', '>' ) ) {
			return '[subscriber:email]';
		}

		return '[user:email]';
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return 'MailPoet';
	}

	/**
	 * check whether or not the MailPoet plugin is installed
	 */
	public function pluginInstalled() {
		$installed = array();
		if ( defined( 'MAILPOET_VERSION' ) && (int) MAILPOET_VERSION <= 3 ) {
			$installed[] = 3;
		}

		if ( class_exists( 'WYSIJA' ) ) {
			$installed[] = 2;
		}

		return $installed;
	}

	/**
	 * output the setup form html
	 *
	 * @return void
	 */
	public function outputSetupForm() {
		$this->_directFormHtml( 'mailpoet' );
	}

	/**
	 * just save the key in the database
	 *
	 * @return mixed|void
	 */
	public function readCredentials() {
		if ( ! $this->pluginInstalled() ) {
			return $this->error( __( 'MailPoet plugin must be installed and activated.', TVE_DASH_TRANSLATE_DOMAIN ) );
		}

		$this->setCredentials( $_POST['connection'] );

		$result = $this->testConnection();

		if ( $result !== true ) {
			return $this->error( '<strong>' . $result . '</strong>)' );
		}
		/**
		 * finally, save the connection details
		 */
		$this->save();

		return true;
	}

	/**
	 * test if a connection can be made to the service using the stored credentials
	 *
	 * @return bool|string true for success or error message for failure
	 */
	public function testConnection() {
		if ( ! $this->pluginInstalled() ) {
			return __( 'At least one MailPoet plugin must be installed and activated.', TVE_DASH_TRANSLATE_DOMAIN );
		}

		return true;
	}

	/**
	 * add a contact to a list
	 *
	 * @param mixed $list_identifier
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function addSubscriber( $list_identifier, $arguments ) {
		if ( ! $this->pluginInstalled() ) {
			return __( 'MailPoet plugin is not installed / activated', TVE_DASH_TRANSLATE_DOMAIN );
		}

		list( $firstname, $lastname ) = $this->_getNameParts( $arguments['name'] );

		$credentials = $this->getCredentials();

		if ( ! isset( $credentials['version'] ) || $credentials['version'] == 2 ) {
			$user_data = array(
				'email'     => $arguments['email'],
				'firstname' => $firstname,
				'lastname'  => $lastname,
			);

			$data_subscriber = array(
				'user'      => $user_data,
				'user_list' => array( 'list_ids' => array( $list_identifier ) ),
			);

			/** @var WYSIJA_help_user $user_helper */
			$user_helper = WYSIJA::get( 'user', 'helper' );
			$result      = $user_helper->addSubscriber( $data_subscriber );
			if ( $result === false ) {
				$messages = $user_helper->getMsgs();
				if ( isset( $messages['xdetailed-errors'] ) ) {
					return implode( '<br><br>', $messages['xdetailed-errors'] );
				} elseif ( isset( $messages['error'] ) ) {
					return implode( '<br><br>', $messages['error'] );
				}

				return __( 'Subscriber could not be saved', TVE_DASH_TRANSLATE_DOMAIN );
			}
		} else {
			$user_data = array(
				'email'      => $arguments['email'],
				'first_name' => $firstname,
				'last_name'  => $lastname,
			);

			// Compatibility with latest version 3.21.0+
			if ( class_exists( 'MailPoet\API\API' ) ) {
				$mailpoet   = new \MailPoet\API\API();
				$errors     = array();
				$subscriber = array();

				// Get subscriber
				try {
					$subscriber = $mailpoet::MP( 'v1' )->getSubscriber( $arguments['email'] );
				} catch ( Exception $exception ) {
					$errors[] = $exception->getMessage();
				}

				// Create subscriber if not exists
				if ( is_array( $subscriber ) && empty( $subscriber['id'] ) ) {
					try {
						$subscriber = $mailpoet::MP( 'v1' )->addSubscriber( $user_data, array(), array() );
					} catch ( Exception $exception ) {
						$errors[] = $exception->getMessage();
					}
				}

				// Add subscriber to list
				if ( is_array( $subscriber ) && ! empty( $subscriber['email'] ) ) {
					try {
						$mailpoet::MP( 'v1' )->subscribeToList( $subscriber['email'], $list_identifier, $user_data );

						return true;
					} catch ( Exception $exception ) {
						return $exception->getMessage();
					}
				}

				if ( $errors ) {
					return implode( '<br/>', $errors );
				}

				return false;
			}

			if ( ! class_exists( 'MailPoet\Models\Subscriber' ) ) {
				$this->_error = __( 'No MailPoet plugin could be found.', TVE_DASH_TRANSLATE_DOMAIN );

				return false;
			}

			$result = call_user_func( array( 'MailPoet\Models\Subscriber', 'subscribe' ), $user_data, array( $list_identifier ) );

			if ( $result->getErrors() ) {
				return implode( '<br><br>', $result->getErrors() );
			}
		}

		return true;

	}

	/**
	 * instantiate the API code required for this connection
	 *
	 * @return mixed
	 */
	protected function _apiInstance() {
		// no API instance needed here
		return null;
	}

	/**
	 * get all Subscriber Lists from this API service
	 *
	 * @return array|bool
	 */
	protected function _getLists() {
		if ( ! $this->pluginInstalled() ) {
			$this->_error = __( 'No MailPoet plugin could be found.', TVE_DASH_TRANSLATE_DOMAIN );

			return false;
		}

		$lists = array();

		$credentials = $this->getCredentials();

		// Version 2 check [DB option] that uses different classes
		if ( ! isset( $credentials['version'] ) || 2 === (int) $credentials['version'] ) {
			$model_list = WYSIJA::get( 'list', 'model' );
			$lists      = $model_list->get( array( 'name', 'list_id' ), array( 'is_enabled' => 1 ) );
			foreach ( $lists as $i => $list ) {
				$lists[ $i ]['id'] = $list['list_id'];
			}
		} else {

			// Compatibility with latest version 3.21.0+
			if ( class_exists( 'MailPoet\API\API' ) ) {

				$mailpoet           = new \MailPoet\API\API();
				$subscription_lists = $mailpoet::MP( 'v1' )->getLists();

				if ( is_array( $subscription_lists ) && ! empty( $subscription_lists ) ) {
					foreach ( $subscription_lists as $list ) {
						$lists [] = array(
							'id'   => $list['id'],
							'name' => $list['name'],
						);
					}
				}

				return $lists;
			}

			if ( ! class_exists( 'MailPoet\Models\Segment' ) ) {
				$this->_error = __( 'No MailPoet plugin could be found.', TVE_DASH_TRANSLATE_DOMAIN );

				return false;
			}

			$segments = call_user_func( array( 'MailPoet\Models\Segment', 'getSegmentsWithSubscriberCount' ), 'default' );

			if ( ! empty( $segments ) ) {
				foreach ( $segments as $segment ) {
					$lists [] = array(
						'id'   => $segment['id'],
						'name' => $segment['name'],
					);
				}
			}
		}

		return $lists;
	}
}
