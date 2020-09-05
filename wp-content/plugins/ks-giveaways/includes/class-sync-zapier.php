<?php

class KS_Giveaways_Zapier
{

	protected $url = null;

	public function __construct($url = NULL)
	{
		$this->url = $url;
	}


	public function send_entry($email, $first_name = NULL)
	{
		$fields = array(
						'email'			=> $email,
						'first_name'	=> ($first_name ? $first_name : ''),
						'giveaway_name'	=> '',
						'lucky_url'		=> '',
		);

		if ($post = get_post()) {
			$fields['lucky_url'] = KS_Helper::get_lucky_url($post);
			$fields['giveaway_name'] = $post->post_title;
		}

		$response = wp_remote_post($this->url, array('body' => $fields));

		if (is_wp_error($response)) {
   			$error_message = $response->get_error_message();
   			return false;
		}

		if ($json = json_decode($response['body'])) {
			return $json->status === 'success';
		}

		return false;
	}

}
