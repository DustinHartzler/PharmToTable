<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

/** @var $this TD_DB_Migration */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

$db = new TVD_Smart_DB();
$db->add_tiktok_field();
