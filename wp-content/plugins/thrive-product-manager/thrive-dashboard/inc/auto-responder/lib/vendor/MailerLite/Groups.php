<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class Thrive_Dash_Api_MailerLite_Groups extends Thrive_Dash_Api_MailerLite_ApiAbstract {

	protected $endpoint = 'groups';

	/**
	 * Get subscribers from group
	 *
	 * @param int    $groupId
	 * @param string $type
	 * @param array  $params
	 *
	 * @return [type]
	 */
	public function getSubscribers( $groupId, $type = null, $params = array() ) {
		$endpoint = $this->endpoint . '/' . $groupId . '/subscribers';

		if ( $type !== null ) {
			$endpoint .= '/' . $type;
		}

		$response = $this->restClient->get( $endpoint, $params );

		return $response['body'];
	}

	/**
	 * Add single subscriber to group
	 *
	 * @param int   $groupId
	 * @param array $subscriberData
	 * @param array $params
	 *
	 * @return [type]
	 */
	public function add_subscriber( $groupId, $subscriberData = array(), $params = array() ) {
		$endpoint = $this->endpoint . '/' . $groupId . '/subscribers';

		return $this->restClient->post( $endpoint, $subscriberData );
	}

	/**
	 * Remove subscriber from group
	 *
	 * @param int $groupId
	 * @param int $subscriberId
	 *
	 * @return [type]
	 */
	public function removeSubscriber( $groupId, $subscriberId ) {
		$endpoint = $this->endpoint . '/' . $groupId . '/subscribers/' . $subscriberId;

		$response = $this->restClient->delete( $endpoint );

		return $response['body'];
	}

	/**
	 * Batch add subscribers to group
	 *
	 * @param int   $groupId
	 * @param array $subscribers
	 *
	 * @return [type]
	 */
	public function importSubscribers( $groupId, $subscribers ) {
		$endpoint = $this->endpoint . '/' . $groupId . '/subscribers/import';

		$response = $this->restClient->post( $endpoint, array( 'subscribers' => $subscribers ) );

		return $response['body'];
	}
}
