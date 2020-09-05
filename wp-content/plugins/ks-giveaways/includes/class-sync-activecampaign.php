<?php
// https://github.com/ActiveCampaign/activecampaign-api-php
if (!class_exists('KS_Giveaways_Vendor_ActiveCampaign')) {
	require_once dirname(__FILE__) . '/vendor/activecampaign_api/ActiveCampaign.class.php';
}

class KS_Giveaways_ActiveCampaign
{
	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	protected $activecampaign = null;

	protected $key;
	protected $url;

	private function __construct()
	{
		$this->key = get_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY);
		$this->url = get_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_URL);

		try {
			$this->activecampaign = new KS_Giveaways_Vendor_ActiveCampaign($this->url, $this->key);
		}
		catch(Exception $e) {
			$this->activecampaign = null;
		}
	}

	/**
	 * Returns an instance of this class.
	 *
	 * @return  object    A single instance of this class.
	 */
	public static function get_instance()
	{
		if (null == self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function get_lists()
	{
		try {
			if (!$this->activecampaign) {
				throw new Exception('ActiveCampaign not connected');
			}

			$api_lists = $this->activecampaign->api("list/list", array("ids" => "all"));

			if ( ! (int) $api_lists->success) {
				throw new Exception('Error fetching lists');
			}

			$lists = array();

			foreach ($api_lists as $list) {
				if (is_object($list) && property_exists($list, 'id')) {
					$lists[$list->id] = $list->name;
				}
			}

			return $lists;

		} catch (Exception $e) {
			return false;
		}

		return false;
	}

	public function add_subscriber($list_id, $email, $first_name = null)
	{
		try {
			if ( ! $this->activecampaign) {
				throw new Exception('ActiveCampaign not connected');
			}

			$contact = array(
				"email" 				=> $email,
				"p[{$list_id}]"			=> $list_id,
				"status[{$list_id}]"	=> 1,
			);

			if ($first_name) {
				$contact['first_name'] = $first_name;
			}

			$contact_result = $this->activecampaign->api('contact/sync', $contact);

			if ( ! (int) $contact_result->success) {
				throw new Exception('Problem adding contact.');
			}

			return true;
		}

		catch(Exception $e) {
			//return $e->getMessage();
		}

		return false;
	}

	public function ping()
	{
		try {
			if (!$this->activecampaign) {
				throw new Exception('ActiveCampaign not connected');
			}

			return $this->is_valid();

		}
		catch (Exception $e) {
			return false;
		}
	}

	public static function disconnect()
	{
		delete_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY);
		delete_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_URL);
	}

	public static function is_valid()
	{
		if (get_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_KEY) && get_option(KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_API_URL)) {
			$cls = self::get_instance();

			if ((int) $cls->activecampaign->credentials_test()) {
				return true;
			}
		}

		return false;
	}
}