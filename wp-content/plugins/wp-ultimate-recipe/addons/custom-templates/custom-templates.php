<?php

class WPURP_Custom_Templates extends WPURP_Addon {

    private $mapping;
    private $cache = array();

    public function __construct( $name = 'custom-templates' ) {
        parent::__construct( $name );

        // Load available blocks
        $this->load( 'template' );
        $this->load( 'block' );

        $this->load( 'general/code' );
        $this->load( 'general/date' );
        $this->load( 'general/icon' );
        $this->load( 'general/image' );
        $this->load( 'general/link' );
        $this->load( 'general/paragraph' );
        $this->load( 'general/space' );
        $this->load( 'general/title' );

        $this->load( 'layout/box' );
        $this->load( 'layout/columns' );
        $this->load( 'layout/container' );
        $this->load( 'layout/rows' );
        $this->load( 'layout/table' );

        $this->load( 'recipe/author' );
        $this->load( 'recipe/cook_time' );
        $this->load( 'recipe/cook_time_text' );
        $this->load( 'recipe/custom_field' );
        $this->load( 'recipe/date' );
        $this->load( 'recipe/description' );
        $this->load( 'recipe/image' );
        $this->load( 'recipe/ingredients' );
        $this->load( 'recipe/instructions' );
        $this->load( 'recipe/link' );
        $this->load( 'recipe/notes' );
        $this->load( 'recipe/nutrition' );
        $this->load( 'recipe/nutrition_label' );
        $this->load( 'recipe/passive_time' );
        $this->load( 'recipe/passive_time_text' );
        $this->load( 'recipe/post_content' );
        $this->load( 'recipe/prep_time' );
        $this->load( 'recipe/prep_time_text' );
        $this->load( 'recipe/servings' );
        $this->load( 'recipe/servings_type' );
        $this->load( 'recipe/stars' );
        $this->load( 'recipe/tags' );
        $this->load( 'recipe/title' );

        $this->load( 'recipe/sub_fields/ingredient_container' );
        $this->load( 'recipe/sub_fields/ingredient_group' );
        $this->load( 'recipe/sub_fields/ingredient_name' );
        $this->load( 'recipe/sub_fields/ingredient_notes' );
        $this->load( 'recipe/sub_fields/ingredient_quantity' );
        $this->load( 'recipe/sub_fields/ingredient_unit' );
        $this->load( 'recipe/sub_fields/instruction_container' );
        $this->load( 'recipe/sub_fields/instruction_group' );
        $this->load( 'recipe/sub_fields/instruction_image' );
        $this->load( 'recipe/sub_fields/instruction_text' );
        $this->load( 'recipe/sub_fields/tag_name' );
        $this->load( 'recipe/sub_fields/tag_terms' );

        $this->load( 'functionality/add_to_meal_plan' );
        $this->load( 'functionality/add_to_shopping_list' );
        $this->load( 'functionality/favorite' );
        $this->load( 'functionality/print_button' );
        $this->load( 'functionality/servings_changer' );
        $this->load( 'functionality/sharing' );
        $this->load( 'functionality/unit_changer' );

        $this->load( 'partner/bigoven' );
        $this->load( 'partner/chicory' );
        $this->load( 'partner/food_fanatic' );
        $this->load( 'partner/yummly' );

        $this->load( 'social/facebook' );
        $this->load( 'social/google' );
        $this->load( 'social/linkedin' );
        $this->load( 'social/pinterest' );
        $this->load( 'social/stumbleupon' );
        $this->load( 'social/twitter' );
        // Actions
        add_action( 'init', array( $this, 'assets' ) );
        add_action( 'init', array( $this, 'default_templates' ) );

        // Ajax
        add_action( 'wp_ajax_get_recipe_template', array( $this, 'ajax_get_recipe_template' ) );
        add_action( 'wp_ajax_nopriv_get_recipe_template', array( $this, 'ajax_get_recipe_template' ) );
    }

    private function load( $block )
    {
        include_once( $this->addonDir . '/templates/' . $block . '.php' );
    }

    public function assets() {

        $fonts = array();
        $templates = array(
            $this->get_template( 'recipe', 'default' )
        );

        foreach( $templates as $template ) {
            if( isset( $template->fonts ) && count( $template->fonts ) > 0 ) {

                $fonts = array_merge(
                    $fonts,
                    $template->fonts
                );
            }
        }

        if( count( $fonts ) > 0 ) {
            WPUltimateRecipe::get()->helper( 'assets' )->add(
                array(
                    'type' => 'css',
                    'file' => 'https://fonts.googleapis.com/css?family=' . implode( '|', array_unique( $fonts ) ),
                    'direct' => true,
                    'public' => true,
                )
            );
        }
    }

    public function get_mapping()
    {
        if( !$this->mapping ) {
            $this->mapping = get_option( 'wpurp_custom_template_mapping', array() );
        }
        return $this->mapping;
    }

    public function update_mapping( $mapping )
    {
        $this->mapping = $mapping;
        update_option( 'wpurp_custom_template_mapping', $mapping );
    }

    public function get_template_code( $template )
    {
        $template = intval( $template );

        if( !isset( $this->cache[$template] ) ) {
            $this->cache[$template] = get_option( 'wpurp_custom_template_' . $template, array() );
        }

        return $this->cache[$template];
    }

    public function get_template( $type, $template )
    {
        $mapping = $this->get_mapping();

        // Only return non-default templates for WP Ultimate Recipe Premium
        if( $template !== 'default' && isset( $mapping[ $template ] ) && WPUltimateRecipe::is_premium_active() ) {
            return $this->get_template_code( $template );
        } else {
            switch( $type ) {
                case 'print':
                    $default_template = WPUltimateRecipe::option( 'recipe_template_print_template', 1 );
                    break;
                case 'amp':
                    $default_template = WPUltimateRecipe::option( 'recipe_template_amp_template', 94 );
                    break;
                case 'user_menus':
                    $default_template = WPUltimateRecipe::option( 'user_menus_recipe_print_template', 1 );
                    break;
                case 'meal_planner':
                    $default_template = WPUltimateRecipe::option( 'meal_planner_recipe_details_template', 96 );
                    break;
                case 'meal_planner_print':
                    $default_template = WPUltimateRecipe::option( 'meal_planner_recipe_print_template', 1 );
                    break;
                case 'grid':
                    $default_template = WPUltimateRecipe::option( 'recipe_template_recipegrid_template', 80 );
                    break;
                case 'feed':
                    $default_template = WPUltimateRecipe::option( 'recipe_template_feed_template', 99 );
                    break;
                case 'metadata':
                    // Empty Recipe Container, but includes Recipe metadata
                    return $this->load_default_template( 'empty' );
                    break;
                default:
                    $default_template = WPUltimateRecipe::option( 'recipe_template_recipe_template', 70 );
                    break;
            }

            return $this->get_template_code( $default_template );
        }
    }

    public function ajax_get_recipe_template()
    {
        // Print Template
        if( check_ajax_referer( 'wpurp_print', 'security', false ) )
        {
            $recipe_id = intval( $_POST['recipe_id'] );
            $recipe = new WPURP_Recipe( $recipe_id );

            $template = $this->get_template( 'print', 'default' );

            $fonts = false;
            if( isset( $template->fonts ) && count( $template->fonts ) > 0 ) {
                $fonts = 'https://fonts.googleapis.com/css?family=' . implode( '|', $template->fonts );
            }

            $template_string = do_shortcode( $template->output_string( $recipe ) );

            $data = array(
                'output' => apply_filters( 'wpurp_output_recipe_print', $template_string, $recipe ),
                'fonts' => $fonts
            );

            echo json_encode( $data );
        }

        // User Menus & Meal Planner Print Template
        if( check_ajax_referer( 'wpurp_user_menus', 'security', false ) || check_ajax_referer( 'wpurp_meal_planner_print', 'security', false ) )
        {
            $recipe_ids = is_array( $_POST['recipe_ids'] ) ? array_map( 'intval', $_POST['recipe_ids'] ) : array();

            $templates = array();
            $fonts = false;

            foreach( $recipe_ids as $recipe_id ) {
                $recipe = new WPURP_Recipe( $recipe_id );

                if ( check_ajax_referer( 'wpurp_user_menus', 'security', false ) ) {
                    $template = $this->get_template( 'user_menus', 'default' );
                } else {
                    $template = $this->get_template( 'meal_planner_print', 'default' );
                }

                if( isset( $template->fonts ) && count( $template->fonts ) > 0 ) {
                    if( !$fonts ) {
                        $fonts = array();
                    }

                    $fonts = array_merge( $fonts, $template->fonts );
                }

                $template_string = do_shortcode( $template->output_string( $recipe ) );
                $templates[] = apply_filters( 'wpurp_output_recipe_print_user_menus', $template_string, $recipe );
            }

            if( $fonts ) {
                $fonts = array_unique( $fonts );
                $fonts = 'https://fonts.googleapis.com/css?family=' . implode( '|', $fonts );
            }

            echo json_encode( array(
                'templates' => $templates,
                'fonts' => $fonts,
            ) );
        }

        // Meal Planner Template
        if( check_ajax_referer( 'wpurp_meal_planner', 'security', false ) )
        {
            $recipe_id = intval( $_POST['recipe_id'] );
            $recipe = new WPURP_Recipe( $recipe_id );

            $template = $this->get_template( 'meal_planner', 'default' );

            $fonts = false;
            if( isset( $template->fonts ) && count( $template->fonts ) > 0 ) {
                $fonts = 'https://fonts.googleapis.com/css?family=' . implode( '|', $template->fonts );
            }

            $template_string = do_shortcode( $template->output_string( $recipe ) );

            $data = array(
                'output' => apply_filters( 'wpurp_output_recipe_meal_planner', $template_string, $recipe ),
                'fonts' => $fonts
            );

            echo json_encode( $data );
        }

        die();
    }

    public function default_templates( $reset = false )
    {
        $mapping = $this->get_mapping();

        if( $reset || count( $mapping ) < 6 )
        {
            $templates = array();

            $templates[0] = array(
                'name' => 'Default Recipe Template',
                'template' => $this->load_default_template( 'recipe' ),
            );
            $templates[1] = array(
                'name' => 'Default Print Template',
                'template' => $this->load_default_template( 'print' ),
            );
            $templates[2] = array(
                'name' => 'Default Recipe Grid Template',
                'template' => $this->load_default_template( 'grid' ),
            );
            $templates[70] = array(
                'name' => 'Default Recipe Template 2016',
                'template' => $this->load_default_template( 'recipe-2016' ),
            );
            $templates[80] = array(
                'name' => 'Default Recipe Grid Template 2016',
                'template' => $this->load_default_template( 'grid-2016' ),
            );
            $templates[94] = array(
                'name' => 'Default AMP Template',
                'template' => $this->load_default_template( 'amp' ),
            );
            $templates[95] = array(
                'name' => 'Default Print Template with Image',
                'template' => $this->load_default_template( 'print-image' ),
            );
            $templates[96] = array(
                'name' => 'Default Meal Planner Recipe Template',
                'template' => $this->load_default_template( 'meal-planner' ),
            );
            $templates[97] = array(
                'name' => 'Default Recipe Template (RTL languages)',
                'template' => $this->load_default_template( 'recipe-rtl' ),
            );
            $templates[98] = array(
                'name' => 'Default Print Template (RTL languages)',
                'template' => $this->load_default_template( 'print-rtl' ),
            );
            $templates[99] = array(
                'name' => 'Default RSS Feed Template',
                'template' => $this->load_default_template( 'rss' ),
            );

            // Set default options
            foreach( $templates as $id => $template ) {
                $mapping[$id] = $template['name'];
                update_option( 'wpurp_custom_template_' . intval( $id ), $template['template'], false );
            }

            $this->update_mapping( $mapping );
        }
    }

    public function load_default_template( $name )
    {
        ob_start();
        include( $this->addonDir . '/defaults/' . $name . '.txt' );
        $txt = ob_get_contents();
        ob_end_clean();
        
        if ( $txt ) {
            return unserialize( $txt );
        } else {
            return '';
        }
    }

    public function add_template( $name, $template )
    {
        $mapping = $this->get_mapping();
        $template_id = max( array_keys( $mapping ) ) + 1;

        $mapping[$template_id] = $name;

        $this->update_mapping( $mapping );
        update_option( 'wpurp_custom_template_' . $template_id, $template, false );

        return $template_id;
    }

    public function update_template( $id, $template )
    {
        update_option( 'wpurp_custom_template_' . $id, $template, false );
    }

    public function delete_template( $id )
    {
        $mapping = $this->get_mapping();
        unset( $mapping[$id] );

        $this->update_mapping( $mapping );
        delete_option( 'wpurp_custom_template_' . $id );
    }
}

WPUltimateRecipe::loaded_addon( 'custom-templates', new WPURP_Custom_Templates() );