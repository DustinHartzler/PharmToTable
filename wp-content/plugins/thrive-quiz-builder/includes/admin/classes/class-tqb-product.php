<?php

/**
 * Class TQB_Product
 */
class TQB_Product extends TVE_Dash_Product_Abstract {
	/**
	 * Tag of the product
	 *
	 * @var string tag.
	 */
	protected $tag = 'tqb';

	/**
	 * Name of the product displayed in Dashboard
	 *
	 * @var string title
	 */
	protected $title = 'Thrive Quiz Builder';

	/**
	 * Type of product
	 *
	 * @var string type of the product
	 */
	protected $type = 'plugin';

	/**
	 * TQB needs architect
	 *
	 * @var bool
	 */
	protected $needs_architect = true;

	/**
	 * TQB_Product constructor.
	 *
	 * @param array $data info used in dashboard.
	 */
	public function __construct( $data = array() ) {
		parent::__construct( $data );

		$this->logoUrl      = tqb()->plugin_url( 'assets/images/tqb-logo.png' );
		$this->logoUrlWhite = tqb()->plugin_url( 'assets/images/tqb-logo-white.png' );
		$this->productIds   = array();

		$this->incompatible_architect_version = ! tqb()->check_tcb_version();

		$this->description = __( 'Engage your visitors with a fun quiz and find out more about them.', Thrive_Quiz_Builder::T );

		$this->button = array(
			'active' => true,
			'url'    => admin_url( 'admin.php?page=tqb_admin_dashboard' ),
			'label'  => __( 'Quiz Builder Dashboard', Thrive_Quiz_Builder::T ),
		);

		$this->moreLinks = array(
			'support'   => array(
				'class'      => '',
				'icon_class' => 'tvd-icon-life-bouy',
				'href'       => 'https://thrivethemes.com/forums/forum/plugins/thrive-quiz-builder/',
				'target'     => '_blank',
				'text'       => __( 'Support', Thrive_Quiz_Builder::T ),
			),
			'tutorials' => array(
				'class'      => '',
				'icon_class' => 'tvd-icon-graduation-cap',
				'href'       => 'https://thrivethemes.com/thrive-knowledge-base/thrive-quiz-builder/',
				'target'     => '_blank',
				'text'       => __( 'Tutorials', Thrive_Quiz_Builder::T ),
			),
		);
	}

	/**
	 * Reset all TQB data
	 *
	 * @return bool|void
	 */
	public static function reset_plugin() {
		global $wpdb;

		$query    = new WP_Query( array(
				'post_type' => array(
					'thrive_image',
					TQB_Post_types::QUIZ_POST_TYPE,
					TQB_Post_types::SPLASH_PAGE_POST_TYPE,
					TQB_Post_types::QNA_PAGE_POST_TYPE,
					TQB_Post_types::OPTIN_PAGE_POST_TYPE,
					TQB_Post_types::RESULTS_PAGE_POST_TYPE,
				),
				'fields'    => 'ids',
			)
		);
		$post_ids = $query->posts;
		foreach ( $post_ids as $id ) {
			wp_delete_post( $id, true );
		}

		$tables = array(
			'event_log',
			'variations',
			'results',
			'results_links',
			'tests',
			'tests_items',
			'user_answers',
			'users',
		);
		foreach ( $tables as $table ) {
			$table_name = tqb_table_name( $table );
			$sql        = "TRUNCATE TABLE $table_name";
			$wpdb->query( $sql );
		}
		$tables = array(
			'answers',
			'questions',
		);
		foreach ( $tables as $table ) {
			$table_name = tge_table_name( $table );
			$sql        = "TRUNCATE TABLE $table_name";
			$wpdb->query( $sql );
		}

		$wpdb->query(
			"DELETE FROM $wpdb->options WHERE 
						`option_name` LIKE '%tqb_%';"
		);
	}
}
