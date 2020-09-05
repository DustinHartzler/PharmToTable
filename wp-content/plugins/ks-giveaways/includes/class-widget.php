<?php
class KS_Giveaways_Widget extends WP_Widget
{

	/**
     * Version used for stylesheet and Javascript assets.
     */
    const VERSION = KS_GIVEAWAYS_EDD_VERSION;

    protected $plugin_slug = 'ks-giveaways';

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct()
	{
		$widget_ops = array( 
			'classname' => 'ks_giveaways_widget',
			'description' => 'Display a widget for a KingSumo giveaway',
		);
		parent::__construct( 'ks_giveaways_widget', 'KingSumo Giveaways', $widget_ops );
	}


	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance)
	{
		$this->enqueue_styles();
		$this->enqueue_scripts();
		// outputs the content of the widget
		// Get giveaway info
		echo $args['before_widget'];

		if (empty($instance['giveaway_id'])) {
			?>
			<p><em>Giveaway not specified in widget settings.</em></p>
			<?php

		} elseif ($giveaway = get_post($instance['giveaway_id'])) {
			echo $args['before_title'] . apply_filters('widget_title', $giveaway->post_title) . $args['after_title'];
			echo KS_Helper::get_description($giveaway);
			?>
			<?php if (!KS_Helper::has_started($giveaway)): ?>
				<div class="ks-countdown-widget" data-until="<?php echo KS_Helper::get_date_start($giveaway) ?>"></div>
				<p>Giveaway hasn't started yet. Come back later.</p>
			<?php elseif (KS_Helper::has_ended($giveaway)): ?>
				<p>Giveaway ended.</p>
			<?php elseif (KS_Helper::has_started($giveaway) && !KS_Helper::has_ended($giveaway)): ?>
	           	<div class="ks-countdown-widget" data-until="<?php echo KS_Helper::get_date_end($giveaway) ?>"></div>
				<form method="post" action="<?php echo get_permalink($giveaway); ?>">
					<?php wp_nonce_field('ks_giveaways_form', 'giveaways_nonce') ?>
					<input type="hidden" name="widget" value="1" />
					<p><input type="email" name="giveaways_email" placeholder="Email" required /></p>
					<p><button>Enter</button></p>
				</form>
			<?php endif ?>
			<?php

		} else {
			?>
			<p>Please select a giveaway in the widget settings.</p>
			<?php
		}
		echo $args['after_widget'];
	}


	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form($instance)
	{
		// outputs the options form on admin
		$giveaways = get_posts(array('post_type' => KS_GIVEAWAYS_POST_TYPE));
		?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('giveaway_id')); ?>"><?php _e(esc_attr('Giveaway:')); ?></label> 
				<select class="widefat" name="<?php echo esc_attr($this->get_field_name('giveaway_id')); ?>" id="<?php echo esc_attr($this->get_field_id('giveaway_id')); ?>">
					<option></option>
					<?php foreach($giveaways as $giveaway): ?>
						<option value="<?php echo esc_attr($giveaway->ID); ?>" <?php selected( (isset($instance['giveaway_id']) ? $instance['giveaway_id'] : 0), $giveaway->ID); ?>><?php _e(esc_attr($giveaway->post_title . ' (' . $giveaway->ID . ')')); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		<?php
	}


	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update($new_instance, $old_instance)
	{
		// processes widget options to be saved
		$instance = array();
		$instance['giveaway_id'] = ! empty( $new_instance['giveaway_id']) ? strip_tags($new_instance['giveaway_id']) : '';

		return $instance;
	}


	/**
     * Register and enqueue the public-facing stylesheet.
     */
	public function enqueue_styles()
	{
		wp_register_style($this->plugin_slug . '-plugin-styles', plugins_url('../public/assets/css/public.css', __FILE__), array(), self::VERSION);
		wp_enqueue_style($this->plugin_slug . '-plugin-styles');
	}

    /**
     * Register and enqueue public-facing Javascript files.
     */
    public function enqueue_scripts()
    {
        $this->enqueue_styles();
        wp_enqueue_script('jquery-plugin', plugins_url('../public/assets/js/jquery.plugin.min.js', __FILE__), array('jquery'), self::VERSION);
        wp_enqueue_script('jquery-countdown', plugins_url('../public/assets/js/jquery.countdown.min.js', __FILE__), array('jquery', 'jquery-plugin'), self::VERSION);
        wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('../public/assets/js/public.js', __FILE__), array('jquery', 'jquery-countdown'), self::VERSION);
    }

}