<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

class TD_NM_Action_Custom_Script extends TD_NM_Action_Abstract {

	public function execute( $prepare_data ) {

		$url = $this->settings['url'];

		wp_remote_post( $url, array(
			'body' => $prepare_data
		) );
	}

	public function prepare_email_sign_up_data( $sign_up_data ) {
		$data = array();

		$tl_item = $sign_up_data[0];
		$tl_form = $sign_up_data[2];
		$tl_data = $sign_up_data[4];

		$data['thrv_event']       = 'thrv_signup';
		$data['source']           = $tl_item->post_type;
		$data['source_name']      = $tl_item->post_title;
		$data['source_id']        = $tl_item->ID;
		$data['source_form_name'] = $tl_form['post_title'];
		$data['source_form_id']   = $tl_form['key'];
		$data['user_email']       = $tl_data['email'];
		$data['user_custom_data'] = $tl_data['custom_fields'];

		return $data;
	}

	public function prepare_split_test_ends_data( $split_test_ends_data ) {
		$data = array();

		$test_item = $split_test_ends_data[0];
		$test      = $split_test_ends_data[1];

		$data['thrv_event']             = 'split_test';
		$data['test_id']                = $test->id;
		$data['test_url']               = $test->url;
		$data['winning_variation_name'] = $test_item->variation['post_title'];
		$data['winning_variation_id']   = $test_item->variation['key'];

		return $data;
	}

	public function prepare_testimonial_submitted_data( $testimonial_data ) {
		$data = array();

		$testimonial = $testimonial_data[0];
		$extra_data  = $testimonial_data[1];

		$data['id']          = $testimonial['id'];
		$data['title']       = $testimonial['title'];
		$data['date']        = $testimonial['date'];
		$data['content']     = $testimonial['summary'];
		$data['role']        = $testimonial['role'];
		$data['name']        = $testimonial['name'];
		$data['email']       = $testimonial['email'];
		$data['website_url'] = $testimonial['website_url'];
		$data['picture_url'] = $testimonial['picture_url'];
		if ( ! empty( $testimonial['tags'] ) && is_array( $testimonial['tags'] ) ) {
			$tags_text_arr = array();
			foreach ( $testimonial['tags'] as $tag ) {
				$tags_text_arr[] = $tag['text'];
			}
			$data['tags'] = implode( ',', $tags_text_arr );
		}

		return $data;
	}

}
