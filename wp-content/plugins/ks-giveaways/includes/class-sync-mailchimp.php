<?php

if (!class_exists('KS_Giveaways_Vendor_MailChimp')) {
	require_once dirname(__FILE__) . '/vendor/mailchimp_api/src/MailChimp.php';
}

class KS_Giveaways_Mailchimp
{
	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	protected $mailchimp = null;

	private function __construct()
	{
		$this->key = get_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY);

		try {
			$this->mailchimp = new KS_Giveaways_Vendor_MailChimp($this->key);
		}
		catch(Exception $e) {
			$this->mailchimp = null;
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
		/*
		 * MailChimp getList() methods are limited to 100 max
		 * So we will need to call it repeatedly until we run out
		 *
		 * This may change with v3 API
		 */
		try {
			if (!$this->mailchimp)
				throw new Exception('Mailchimp not connected');

			$lists = array();

			if ($mailchimp_lists_api = $this->mailchimp->get('lists', array('count' => 25))) {
				$mailchimp_lists_api = $mailchimp_lists_api['lists'];

				foreach ($mailchimp_lists_api as $list) {
					$lists[$list['id']] = $list['name'];
				}
			}

			return $lists;
		}

		catch(Exception $e) {
		}

		return false;
	}

	public function add_subscriber($list_id, $email, $first_name = null)
	{
		try {
			if (!$this->mailchimp)
				throw new Exception('Mailchimp not connected');

			$sub_data = array(
				'email_address' => $email,
				'status'        => 'subscribed',
				'email_type'	=> 'html'
			);

			if ($first_name) {
				$sub_data['merge_fields'] = array('FNAME' => $first_name);
			}

			$result = $this->mailchimp->post("lists/$list_id/members", $sub_data);

			return true;
		}

		catch(Exception $e) {
			return $e->getMessage();
		}

		return false;
	}

	public function ping()
	{
		try {
			if (!$this->mailchimp)
				throw new Exception('Mailchimp not connected');

			return $this->mailchimp->get('lists');

		}
		catch (Exception $e) {
			return false;
		}
	}

	public static function disconnect()
	{
		delete_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY);
	}

	public static function is_valid()
	{
		if (get_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY)) {
			$cls = self::get_instance();

			return $cls->ping();
		}

		return false;
	}
}