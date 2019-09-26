<?php

class WPURP_Blocks {

    public function __construct()
    {
        add_action( 'enqueue_block_editor_assets', array( $this, 'block_assets' ) );

        add_filter( 'block_categories', array( $this, 'block_categories' ) );
    }

    public function block_assets()
    {
        wp_enqueue_script( 'wpurp-blocks', WPUltimateRecipe::get()->coreUrl . '/dist/blocks.js', array( 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor' ), WPURP_VERSION );

        wp_localize_script( 'wpurp-blocks', 'wpurp_blocks', array(
			'shortcodes' => array(
                'recipes_by_date' => wpurp_shortcode_generator_recipes_by_date(),
                'templates' => wpurp_shortcode_generator_templates(),
			),
		));
    }

    public function block_categories( $categories )
    {
        return array_merge(
			$categories,
			array(
				array(
					'slug' => 'wp-ultimate-recipe',
					'title' => 'WP Ultimate Recipe',
				),
			)
		);
    }
}