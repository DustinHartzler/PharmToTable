<?php


/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-quiz-builder
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/** @var $this TD_DB_Migration $questions */
$this->add_or_modify_column( 'event_log', 'post_id', 'BIGINT( 20 ) NULL' );
