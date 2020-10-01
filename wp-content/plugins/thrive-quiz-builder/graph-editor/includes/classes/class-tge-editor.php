<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-quiz-builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

if ( class_exists( 'TGE_Editor' ) ) {
	return;
}

class TGE_Editor {

	/**
	 * @var TGE_Editor
	 */
	private static $_instance = null;

	/**
	 * @var WP_Post
	 */
	private $_post = null;

	/**
	 * @var bool
	 */
	private $_can_edit_post = null;

	public static function instance() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	final private function __construct() {
		if ( $this->_can_edit_post() ) {
			$this->_clear_scripts();
			$this->_init();
		}
	}

	private function _can_edit_post() {

		if ( isset( $this->_can_edit_post ) ) {
			return $this->_can_edit_post;
		}

		$this->_can_edit_post = false;
		$this->_can_edit_post = TQB_Product::has_access();
		$post                 = $this->_can_edit_post ? get_post() : null;
		$this->_can_edit_post = $this->_can_edit_post && (bool) $post;

		$this->_can_edit_post ? $this->_post = $post : null;

		return $this->_can_edit_post;
	}

	private function _clear_scripts() {

		//global $wp_filter;
		//print_r( $wp_filter['wp_footer'] );

		remove_all_actions( 'wp_head' );
		remove_all_actions( 'wp_footer' );

		remove_all_actions( 'wp_enqueue_scripts' );
		remove_all_actions( 'wp_print_styles' );
		remove_all_actions( 'wp_print_footer_scripts' );
		remove_all_actions( 'print_footer_scripts' );
		remove_all_actions( 'admin_bar_menu' );

		remove_all_filters( 'single_template' );
		remove_all_filters( 'template_include' );

		add_action( 'wp_head', 'wp_enqueue_scripts' );
		add_action( 'wp_head', 'wp_print_styles' );
		add_action( 'wp_head', 'wp_print_head_scripts' );

		add_action( 'wp_head', '_wp_render_title_tag', 1 );

		add_action( 'wp_footer', '_wp_footer_scripts' );
		add_action( 'wp_footer', 'wp_print_footer_scripts', 20 );
		add_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
		add_action( 'wp_footer', 'print_footer_scripts', 1000 );

		_wp_admin_bar_init();
	}

	private function _init() {

		/**
		 * Scripts
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );

		/**
		 * Styles
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ), PHP_INT_MAX );

		$this->_add_vendors();

		/**
		 * Layout
		 */
		add_filter( 'single_template', array( $this, 'layout' ) );

		add_filter( 'tve_dash_enqueue_frontend', array( $this, 'allow_thrive_dashboard_on_frontend' ) );

		add_action( 'wp_print_footer_scripts', array( $this, 'print_backbone_templates' ) );
		add_action( 'wp_print_footer_scripts', 'tve_dash_backbone_templates' );
		add_action( 'wp_print_footer_scripts', array( $this, 'add_admin_svg_file' ) );

		apply_filters( 'tge_filter_edit_post', $this->_post );

		$this->set_quiz_settings();

		add_filter( 'document_title_parts', array( $this, 'get_title' ) );

		wp_dequeue_script( 'membermouse-socialLogin' );
	}

	private function _add_vendors() {

		/**
		 * Vendors Scripts
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'add_vendors_scripts' ) );

		/**
		 * Vendors Styles
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'add_vendors_styles' ), PHP_INT_MAX );
	}

	public function get_title( $title ) {
		return array(
			'title'  => $this->_post->post_title,
			'editor' => 'Question Editor',
		);
	}

	public function allow_thrive_dashboard_on_frontend() {
		return true;
	}

	public function add_scripts() {

		/** some themes have hooks defined here, which rely on functions defined only in the admin part - these will not be defined on frontend */
		remove_all_filters( 'media_view_settings' );

		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-draggable' );

		$js_suffix = defined( 'TVE_DEBUG' ) && TVE_DEBUG ? '.js' : '.min.js';

		wp_enqueue_script( 'tge-jquery', tge()->url( 'assets/vendors/jquery.min.js' ), array(), Thrive_Quiz_Builder::V );
		wp_enqueue_script( 'tge-lodash', tge()->url( 'assets/vendors/lodash.min.js' ), array(), Thrive_Quiz_Builder::V );
		wp_enqueue_script( 'tge-backbone', tge()->url( 'assets/vendors/backbone-min.js' ), array(
			'tge-jquery',
			'tge-lodash',
		), Thrive_Quiz_Builder::V );
		wp_enqueue_script( 'tge-jointjs', tge()->url( 'assets/vendors/jointjs/joint' . $js_suffix ), array(
			'tge-jquery',
			'tge-lodash',
			'tge-backbone',
		), Thrive_Quiz_Builder::V );
		tve_dash_enqueue_script( 'tve-dash-main-js', TVE_DASH_URL . '/js/dist/tve-dash' . $js_suffix, array(
			'tge-jointjs',
		) );
		wp_enqueue_script( 'tge-editor', tge()->url( 'assets/js/dist/tge-editor' . $js_suffix ), array(
			'tge-jointjs',
			'tve-dash-main-js',
		), Thrive_Quiz_Builder::V, true );

		$question_manager = new TGE_Question_Manager( $this->_post->ID );
		$questions        = $question_manager->get_quiz_questions( array( 'with_answers' => true ) );
		$quiz_style       = TQB_Post_meta::get_quiz_style_meta( $this->_post->ID );

		$data = array(
			'debug_mode'             => defined( 'TVE_DEBUG' ) && TVE_DEBUG,
			'quiz_dash_url'          => $this->get_quiz_dash_url(),
			'ajaxurl'                => admin_url( 'admin-ajax.php' ),
			'ajax_controller_action' => 'tge_admin_ajax_controller',
			'nonce'                  => wp_create_nonce( TGE_Ajax::AJAX_NONCE_NAME ),
			'assets_url'             => tge()->url( 'assets' ),
			'post_id'                => $this->_post->ID,
			'quiz'                   => $this->_post,
			'question_types'         => TGE_Question_Manager::get_question_types(),
			'icons'                  => array(
				'delete'    => tge()->url( 'assets/img/delete-qe.png' ),
				'edit'      => tge()->url( 'assets/img/edit-qe.png' ),
				'duplicate' => tge()->url( 'assets/img/duplicate-qe.png' ),
			),
			'questions'              => $question_manager->prepare_questions( $questions ),
			't'                      => array(
				'edit_description'            => __( 'Edit description', Thrive_Graph_Editor::T ),
				'add_description'             => __( 'Add description', Thrive_Graph_Editor::T ),
				'invalid_image_answer'        => __( 'You cannot add a new answer as long as there are answers without image added.', Thrive_Graph_Editor::T ),
				'invalid_text_answer'         => __( 'You cannot add a new answer as long as there are answers without text added.', Thrive_Graph_Editor::T ),
				'select_question_type'        => __( 'Please select question type', Thrive_Graph_Editor::T ),
				'quiz_start'                  => __( 'Quiz Start', Thrive_Graph_Editor::T ),
				'question_text_required'      => __( 'Question text is required', Thrive_Graph_Editor::T ),
				'answer_text_required'        => __( 'Answer text required', Thrive_Graph_Editor::T ),
				'answer_points_required'      => __( 'Answer points required', Thrive_Graph_Editor::T ),
				'answer_weight_required'      => __( 'Answer weight required', Thrive_Graph_Editor::T ),
				'answer_points_number'        => __( 'Answer points must be a number', Thrive_Graph_Editor::T ),
				'answer_weight_number'        => __( 'Answer weight must be a number', Thrive_Graph_Editor::T ),
				'points_input_number'         => __( 'The input must be an integer with max 6 digits.', Thrive_Graph_Editor::T ),
				'invalid_answer'              => __( 'There are some invalid answers', Thrive_Graph_Editor::T ),
				'insufficient_answers'        => __( 'A question needs at least 1 answer', Thrive_Graph_Editor::T ),
				'answer_image_required'       => __( 'Answer image is mandatory', Thrive_Graph_Editor::T ),
				'question_success_deleted'    => __( 'Question has been deleted', Thrive_Graph_Editor::T ),
				'question_error_deleted'      => __( 'Question could not be deleted', Thrive_Graph_Editor::T ),
				'select_result'               => __( 'Please select category', Thrive_Graph_Editor::T ),
				'saving'                      => __( 'Saving...', Thrive_Graph_Editor::T ),
				'changes_saved'               => __( 'Changes saved', Thrive_Graph_Editor::T ),
				'changes_automatically_saved' => __( 'All your changes are auto saved', Thrive_Graph_Editor::T ),
				'change_question_type'        => __( 'Change Question Type', Thrive_Graph_Editor::T ),
				'edit_question'               => __( 'Edit question', Thrive_Graph_Editor::T ),
				'minimize'                    => __( 'Minimize', Thrive_Graph_Editor::T ),
				'maximize'                    => __( 'Maximize', Thrive_Graph_Editor::T ),
				'tags_switcher_off_tooltip'   => __( 'Disable attaching tags to answers.', Thrive_Graph_Editor::T ),
				'tags_switcher_on_tooltip'    => __( 'Enable attaching tags to answers.', Thrive_Graph_Editor::T ),
				'tags_switcher_on_toast'      => sprintf( __( 'Attaching tags to answers is now enabled. If your API connection supports it, %s these tags can be sent to your mailing list. For more details %s', Thrive_Graph_Editor::T ), '<br/>', '<a class="tvd-white-text" href="https://thrivethemes.com/?post_type=tkb_item&p=514707" target="_blank">' . __( 'check this tutorial.' ) . '</a>' ),
				'tags_switcher_off_toast'     => __( 'Attaching tags to answers is now disabled but the settings are saved for later use.', Thrive_Graph_Editor::T ),
				'media'                       => array(
					'question_title'          => __( 'Select image for your question', Thrive_Graph_Editor::T ),
					'video_title'             => __( 'Select a media file for your question', Thrive_Graph_Editor::T ),
					'video_error'             => __( 'Only video types are supported.', Thrive_Graph_Editor::T ),
					'invalid_youtube_url'     => __( 'Please add a valid Youtube URL', Thrive_Graph_Editor::T ),
					'invalid_vimeo_url'       => __( 'Please add a valid Vimeo URL', Thrive_Graph_Editor::T ),
					'invalid_wistia_url'      => __( 'Please add a valid Wistia URL', Thrive_Graph_Editor::T ),
					'autoplay_tooltip'        => __( 'Be advised that the “Autoplay” option does not work on video thumbnails', Thrive_Graph_Editor::T ),
					'spotify_options_tooltip' => __( 'Be advised that the Audio Options are not available for Spotify', Thrive_Graph_Editor::T ),
					'cancel'                  => __( 'Cancel', Thrive_Graph_Editor::T ),
					'placeholder_file_name'   => __( 'File name', Thrive_Graph_Editor::T ),
					'placeholder_url'         => __( 'URL', Thrive_Graph_Editor::T ),
				),
				'autoplay'                    => __( 'Autoplay', Thrive_Graph_Editor::T ),
				'hide_logo'                   => __( 'Hide logo', Thrive_Graph_Editor::T ),
				'disable_playbar'             => __( 'Disable playbar', Thrive_Graph_Editor::T ),
				'loop'                        => __( 'Loop', Thrive_Graph_Editor::T ),
				'show_user'                   => __( 'Show user', Thrive_Graph_Editor::T ),
				'hide_user'                   => __( 'Do not show the user', Thrive_Graph_Editor::T ),
				'show_artwork'                => __( 'Show artwork', Thrive_Graph_Editor::T ),
				'hide_artwork'                => __( 'Do not show artwork', Thrive_Graph_Editor::T ),
				'optimize_related'            => __( 'Optimize related', Thrive_Graph_Editor::T ),
				'hide_controls'               => __( 'Hide controls', Thrive_Graph_Editor::T ),
				'hide_full_screens'           => __( 'Hide full screen', Thrive_Graph_Editor::T ),
				'hide_full_screens_wistia'    => __( 'Hide full screen button', Thrive_Graph_Editor::T ),
				'type_text'                   => __( 'Text Question', Thrive_Graph_Editor::T ),
				'type_video'                  => __( 'Video Question', Thrive_Graph_Editor::T ),
				'type_audio'                  => __( 'Audio Question', Thrive_Graph_Editor::T ),
				'video_style_no_style'        => __( 'No style', Thrive_Graph_Editor::T ),
				'video_style_gray_monitor'    => __( 'Gray Monitor', Thrive_Graph_Editor::T ),
				'video_style_black_monitor'   => __( 'Black Monitor', Thrive_Graph_Editor::T ),
				'video_style_black_tablet'    => __( 'Black Tablet', Thrive_Graph_Editor::T ),
				'video_style_white_tablet'    => __( 'White Tablet', Thrive_Graph_Editor::T ),
				'video_style_white_frame'     => __( 'White Frame', Thrive_Graph_Editor::T ),
				'video_style_gray_frame'      => __( 'Gray Frame', Thrive_Graph_Editor::T ),
				'video_style_dark_frame'      => __( 'Dark Frame', Thrive_Graph_Editor::T ),
				'video_style_light_frame'     => __( 'Light Frame', Thrive_Graph_Editor::T ),
				'video_style_lifted_style1'   => __( 'Lifted Style 1', Thrive_Graph_Editor::T ),
				'video_style_lifted_style2'   => __( 'Lifted Style 2', Thrive_Graph_Editor::T ),
				'video_style_lifted_style3'   => __( 'Lifted Style 3', Thrive_Graph_Editor::T ),
				'video_style_lifted_style4'   => __( 'Lifted Style 4', Thrive_Graph_Editor::T ),
				'video_style_lifted_style5'   => __( 'Lifted Style 5', Thrive_Graph_Editor::T ),
				'video_style_lifted_style6'   => __( 'Lifted Style 6', Thrive_Graph_Editor::T ),
				'video_source_youtube'        => __( 'YouTube', Thrive_Graph_Editor::T ),
				'video_source_vimeo'          => __( 'Vimeo', Thrive_Graph_Editor::T ),
				'video_source_wistia'         => __( 'Wistia', Thrive_Graph_Editor::T ),
				'video_source_custom'         => __( 'Custom', Thrive_Graph_Editor::T ),
				'audio_source_spotify'        => __( 'Spotify', Thrive_Graph_Editor::T ),
				'audio_source_soundcloud'     => __( 'Soundcloud', Thrive_Graph_Editor::T ),
				'not_supported_video_tag'     => __( 'Your browser does not support the video tag.', Thrive_Graph_Editor::T ),
				'low_font_size_value'         => __( 'Font size value can\'t be lower than 10px', Thrive_Graph_Editor::T ),
				'high_font_size_value'        => __( 'Font size value can\'t be higher than 50px', Thrive_Graph_Editor::T ),
				'add_valid_video_url'         => __( 'Please add a valid %s video URL', Thrive_Graph_Editor::T ),
				'add_valid_audio_url'         => __( 'Please add a valid %s audio URL', Thrive_Graph_Editor::T ),
				'media_url_required'          => __( 'Please add a valid %s URL', Thrive_Graph_Editor::T ),
				'add_image_label_text'        => __( 'Add Image', Thrive_Graph_Editor::T ),
				'add_image_label_video'       => __( 'Add Video Thumbnail', Thrive_Graph_Editor::T ),
				'scroll_settings_saved'       => __( 'Scroll settings successfully saved', Thrive_Graph_Editor::T ),
			),
			'default_video_style'    => get_post_meta( $this->_post->ID, TQB_Post_meta::META_NAME_FOR_QUIZ_VIDEO_STYLE, 1 ) ? get_post_meta( $this->_post->ID, TQB_Post_meta::META_NAME_FOR_QUIZ_VIDEO_STYLE, 1 ) : 0,
			'default_video_options'  => get_post_meta( $this->_post->ID, TQB_Post_meta::META_NAME_FOR_QUIZ_VIDEO_OPTIONS, 1 ) ? get_post_meta( $this->_post->ID, TQB_Post_meta::META_NAME_FOR_QUIZ_VIDEO_OPTIONS, 1 ) : null,
			'default_audio_options'  => get_post_meta( $this->_post->ID, TQB_Post_meta::META_NAME_FOR_QUIZ_AUDIO_OPTIONS, 1 ) ? get_post_meta( $this->_post->ID, TQB_Post_meta::META_NAME_FOR_QUIZ_AUDIO_OPTIONS, 1 ) : null,
			'quiz_style'             => $quiz_style,
			'progress_bar_defaults'  => TQB_Progress_Settings::get_quiz_style_defaults( $quiz_style ),
		);

		wp_localize_script( 'tge-editor', 'TGE_Editor', $data );
		tve_dash_enqueue_script( 'tge-api-wistia-popover', '//fast.wistia.com/assets/external/E-v1.js', array(), '', true );

		/**
		 * Used on video/audio question type
		 */
		if ( function_exists( 'tie' ) ) {
			wp_enqueue_script( 'spectrum-script', tie()->url( 'assets/js/spectrum/spectrum.js' ), array( 'jquery' ), false, true );
		}
	}

	public function add_styles() {
		wp_enqueue_style( 'tge-jointjs', tge()->url( 'assets/vendors/jointjs/joint.min.css' ), array(), Thrive_Quiz_Builder::V );
		wp_enqueue_style( 'tge-editor', tge()->url( 'assets/css/tge-editor.css', array(
			'tge-jointjs',
		) ), Thrive_Quiz_Builder::V );

		tve_dash_enqueue_style( 'tve-dash-styles-css', TVE_DASH_URL . '/css/styles.css' );

		/**
		 * Used on video/audio question type
		 */
		if ( function_exists( 'tie' ) ) {
			tie_enqueue_style( 'spectrum-style', tie()->url( 'assets/js/spectrum/spectrum.css' ) );
		}
	}

	public function layout() {
		$layout = dirname( dirname( __FILE__ ) ) . '/layouts/editor.php';

		return $layout;
	}

	public function print_backbone_templates() {
		$templates = tve_dash_get_backbone_templates( tge()->path( 'includes/templates/backbone' ), 'backbone' );
		tve_dash_output_backbone_templates( $templates );
	}

	public function get_quiz_dash_url() {
		return admin_url( 'admin.php?page=tqb_admin_dashboard#dashboard/quiz/' . $this->_post->ID );
	}

	/**
	 * Set quiz settings, feedback and highlight answer time
	 */
	public function set_quiz_settings() {
		$this->_post->display_weight     = (bool) get_post_meta( $this->_post->ID, 'tge_display_weight', true );
		$this->_post->display_tags       = (bool) get_post_meta( $this->_post->ID, 'tge_display_tags', true );
		$this->_post->feedback_settings  = TQB_Post_meta::get_feedback_settings_meta( $this->_post->ID );
		$this->_post->highlight_settings = TQB_Post_meta::get_highlight_settings_meta( $this->_post->ID );
		$this->_post->progress_settings  = tqb_progress_settings_instance( (int) $this->_post->ID )->get();
		$this->_post->scroll_settings    = TQB_Post_meta::get_quiz_scroll_settings_meta( $this->_post->ID );
	}

	public function add_vendors_scripts() {
		if ( function_exists( 'tie' ) ) {
			wp_enqueue_script( 'spectrum-script', tie()->url( 'assets/js/spectrum/spectrum.js' ), array( 'jquery' ), false, true );
		}
	}

	public function add_vendors_styles() {
		if ( function_exists( 'tie' ) ) {
			tie_enqueue_style( 'spectrum-style', tie()->url( 'assets/js/spectrum/spectrum.css' ) );
		}
	}

	/**
	 * Load svg files required in graph editor
	 */
	public function add_admin_svg_file() {

		$post = get_post();

		if ( true === $post instanceof WP_Post && $post->post_type === 'tqb_quiz' ) {
			include tqb()->plugin_path( 'assets/images/tqb-admin-svg-icons.svg' );
		}
	}
}
