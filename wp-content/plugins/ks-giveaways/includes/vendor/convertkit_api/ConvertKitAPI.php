<?php

/**
 * @author Garrett Grimm <garrett@grimmdude.com>
 * @package ConvertKitAPI
 * @version 1.0.0
 */
class KS_Giveaways_Vendor_ConvertKitAPI
{
	const API_URL = 'https://api.convertkit.com/v3/';

	private $apiKey;

	/**
	 * @param string $apiKey
	 */
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}


	/**
	 * Ping the API server to verify the API key
	 *
	 * @return bool
	 */
	public function ping()
	{
		return $this->getForms() == TRUE;
	}


	public function getForms()
	{
		$result = $this->getURL(self::API_URL . 'forms', array('api_key' => $this->apiKey));

		if ($result['status'] !== 200) {
			return FALSE;
		}

		$forms = json_decode($result['body']);

		// Transform into format we can use
		$normalized_forms = array();
		foreach ($forms->forms as $form) {
			$normalized_forms[$form->id] = $form->name;
		}

		return $normalized_forms;
	}


	public function addSubscriberToForm($formId, $email, $firstName)
	{
		$fields = array('api_key' => $this->apiKey, 'email' => $email, 'first_name' => $firstName);
		$result = $this->postURL(self::API_URL . 'forms/' . $formId . '/subscribe', $fields);

		if (in_array($result['status'], array(200, 201))) {
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * GETs an URL using cURL
	 *
	 * @param $page
	 * @param $args array
	 * @param integer $timeout
	 *
	 * @return bool|mixed
	 */
	private function getURL($page, $args, $timeout = 3)
	{
		if (is_array($args)) {
			$page .= '?' . http_build_query($args);
		}

		$ch = curl_init($page);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int) $timeout);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "{$this->apiKey}:password");
		$ret = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return array(
			'status' => $status,
			'body' => $ret
		);
	}

	/**
	 * POSTs data to an URL
	 *
	 * @param $page
	 * @param array|string $args
	 * @param integer $timeout
	 *
	 * @return bool|mixed
	 */
	private function postURL($page, $args, $timeout = 3)
	{
		$ch = curl_init($page);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int) $timeout);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "{$this->apiKey}:password");
		curl_setopt($ch, CURLOPT_HEADER, "Content-type: application/json");
		$ret = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return array(
			'status' => $status,
			'body' => $ret
		);
	}
}