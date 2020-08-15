<?php
/**
 * Storefront Visual Hook Guide engine room
 *
 * @package storefront-visual-guide
 * @since   1.1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }

/**
 * Main Storefront Visual Guide Class.
 *
 */
class Storefront_Visual_Guide {

  /**
   * Storefront_Visual_Guide Constructor.
   */
  public function __construct() {
    add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
    add_action( 'admin_notices', array( $this, 'active_notice' ) );
    add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu'), 100 );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts'), 999 );
    add_action( 'get_header', array( $this, 'hooker') );
    add_action( 'svg_hook_button', array( $this, 'hooked_functions_button' ) );
    add_action( 'svg_hook_content', array( $this, 'action_hooks_content') );
    add_action( 'svg_hook_toggle', array( $this, 'remove_hooked_functions_toggle' ) );
  }

  /**
   * Load Localization files.
   *
   * @since   1.0.2
   */
  public function load_plugin_textdomain() {
    load_plugin_textdomain( 'storefront-visual-guide', false, dirname( plugin_basename( SVG_PLUGIN_PATH ) ) . '/languages' );
  }

  /**
   * Adds active warning notification to admin.
   *
   * @since   1.1.0
   */
  public static function active_notice() {
    $theme_info = wp_get_theme();
    $storefront_theme = array(
      'storefront',
    );
    $plugins_page = esc_url('/wp-admin/plugins.php');

    if ( in_array( $theme_info->Template, $storefront_theme ) ) {
      ?>
  	    <div class="notice notice-warning is-dismissible" >
          <p><?php printf( __( '<a href="%s">Storefront Visual Hook Guide</a> plugin is currently active. If this is a production site, remember to deactivate after use.', 'storefront-visual-guide' ), $plugins_page ); ?></p>
  	    </div>
    	<?php
    }
  }

  /**
   * Adds admin-bar menu to the frontend.
   *
   * @since   1.1.0
   */

  public function admin_bar_menu() {
		global $wp_admin_bar;

		if ( is_admin() ) {
      return;
    }

		$wp_admin_bar->add_menu(
			array(
				'id' => 'sf_visual_guide',
				'title' => _x( 'Storefront Visual Guide', 'Admin bar menu title', 'storefront-visual-guide' ),
				'href' => '',
				'position' => 0,
			)
		);
    if ( isset($_GET['storefront_hooks'] ) && $_GET['storefront_hooks'] == 'show' ) {
      $wp_admin_bar->add_menu(
        array(
          'id'	   => 'svg_clear_storefront_hooks',
          'parent'   => 'sf_visual_guide',
          'title'	   => __( 'Hide Storefront Hooks', 'storefront-visual-guide' ),
          'href'	   => esc_url( remove_query_arg( 'storefront_hooks' ) ),
          'position' => 10,
        )
      );
    } else {
      $wp_admin_bar->add_menu(
        array(
          'id'	   => 'svg_storefront_hooks',
          'parent'   => 'sf_visual_guide',
          'title'	   => __( 'Show Storefront Hooks', 'storefront-visual-guide' ),
          'href'	   => esc_url( add_query_arg( 'storefront_hooks', 'show' ) ),
          'position' => 10,
        )
      );
    }
    if ( isset($_GET['storefront_filters'] ) && $_GET['storefront_filters'] == 'show' ) {
      $wp_admin_bar->add_menu(
        array(
          'id'	   => 'svg_clear_storefront_filters',
          'parent'   => 'sf_visual_guide',
          'title'	   => __( 'Hide Storefront Filters', 'storefront-visual-guide' ),
          'href'	   => esc_url( remove_query_arg( 'storefront_filters' ) ),
          'position' => 20,
        )
      );
    } else {
      $wp_admin_bar->add_menu(
        array(
          'id'	   => 'svg_storefront_filters',
          'parent'   => 'sf_visual_guide',
          'title'	   => __( 'Show Storefront Filters', 'storefront-visual-guide' ),
          'href'	   => esc_url( add_query_arg( 'storefront_filters', 'show' ) ),
          'position' => 20,
        )
      );
    }
  }

  /**
   * Enqueues Storefront Visual Guide "Hooks styles" and "scripts" when called.
   *
   * @since   1.2.0
   */
  public function enqueue_scripts() {
    if ( ( 'show' == isset( $_GET['storefront_hooks'] ) ) || ( 'show' == isset( $_GET['woocommerce_hooks'] ) ) ) {
      wp_enqueue_style( 'svg_hooks', plugins_url( 'css/hooks.css', plugin_basename( SVG_PLUGIN_PATH ) ), array(), SVG_PLUGIN_VERSION );
      wp_enqueue_script( 'svg_hooks', plugins_url( 'js/hooks.js', plugin_basename( SVG_PLUGIN_PATH ) ), array( 'jquery' ), SVG_PLUGIN_VERSION, true );
    }
    if ( 'show' == isset( $_GET['storefront_filters'] ) ) {
      wp_enqueue_style( 'svg_filters', plugins_url( 'css/filters.css', plugin_basename( SVG_PLUGIN_PATH ) ), array(), SVG_PLUGIN_VERSION );
      wp_enqueue_script( 'svg_filters', plugins_url( 'js/filters.js', plugin_basename( SVG_PLUGIN_PATH ) ), array( 'jquery' ), SVG_PLUGIN_VERSION, true );
    }
	}

  /**
   * A place to hook the calls to display hooks and filters.
   *
   * @since   1.1.0
   */
  public function hooker() {
    if ( !('show' == isset( $_GET['storefront_hooks'] ) ) && !('show' == isset( $_GET['storefront_filters'] ) ) ) {
 			return;
 		}

 		add_action( 'all', array( $this, 'display_action_hooks'), 1 );
    add_action( 'storefront_before_site', array( $this, 'display_filters'), 1 );
 	}

  /**
   * Display Storefront action hooks.
   *
   * @since   1.1.0
   *
   */
  public function display_action_hooks() {
    global $wp_actions;

    if ( 'show' != isset( $_GET['storefront_hooks'] ) ) {
      return;
    }

    if ( ! is_user_logged_in() ) {
      return;  // Avoid non logged users using this plugins functionality
    }

    $current_action = current_filter();
    if ( ('storefront_' !== substr( $current_action, 0, 11 )) && ('homepage' !== $current_action) ) {
      return; // Not a Storefront hook or filter.
    }

    if ( ! isset( $wp_actions[ $current_action ] ) ) {
      return; // This action is not an action (it is probably a filter).
    }

    ?> <div id="visual-hook-box" class="storefront-visual-hook-box" data-svg-hook-box="<?php echo $current_action; ?>"><h3><?php echo $current_action; ?></h3><?php

    do_action( 'svg_hook_button', $current_action );

    ?><pre class="svg-hooked-functions"><?php

    do_action( 'svg_hook_content', $current_action );

    do_action( 'svg_hook_toggle', $current_action );

    ?> </pre></div> <?php

  }

  public static function hooked_functions_button( $current_action ) {
    global $wp_filter;
    if ( isset( $wp_filter[ $current_action ] ) ) :
      ?><span id="svg-show-hooked-functions" class="svg-inline-button" title="<?php _e( 'Toggle hooked functions', 'storefront-visual-guide' ); ?>"><span>+</span></span><?php
    endif;
  }

  public static function remove_hooked_functions_toggle( $current_action ) {
    global $wp_filter;
    if ( isset( $wp_filter[ $current_action ] ) ) :
      ?><span id="svg-toggle-remove-functions" class="svg-inline-button svg-bottom-toggle" title="<?php _e( 'Toggle remove_action hint', 'storefront-visual-guide' ); ?>" data-svg-action-hook="<?php echo $current_action; ?>"><?php _e( 'Remove_action hint', 'storefront-visual-guide' ); ?></span><?php
    endif;
  }

  public static function action_hooks_content( $current_action ) {
    global $wp_filter;
    if ( isset( $wp_filter[ $current_action ] ) ) :
      foreach ( $wp_filter[ $current_action ] as $priority => $callbacks ) {
        foreach ( $callbacks as $callback ) {
          $callback = self::populate_callback( $callback );
          if ( isset( $callback['component'] ) ) {
            $components[ $callback['component']->name ] = $callback['component']->name;
          }
          ?><code class="svg-hooked-function"><?php
          do_action( 'svg_hook_function_opening', $current_action );
          ?><span class="svg-function-name" title="<?php _e( 'Function', 'storefront-visual-guide' ); ?>">'<?php echo $callback['name']; ?>'</span>, <span class="svg-function-priority" title="<?php _e( 'Priority', 'storefront-visual-guide' ); ?>"><?php
          echo $priority;
          ?></span><?php
          do_action( 'svg_hook_function_closing' );
          ?></code><br><?php
        }
      }
    endif;
  }

  public static function populate_callback( array $callback ) {
		if ( is_string( $callback['function'] ) and ( false !== strpos( $callback['function'], '::' ) ) ) {
			$callback['function'] = explode( '::', $callback['function'] );
		}
		try {
			if ( is_array( $callback['function'] ) ) {
				if ( is_object( $callback['function'][0] ) ) {
					$class  = get_class( $callback['function'][0] );
					$access = '::';
				} else {
					$class  = $callback['function'][0];
					$access = ' -> ';
				}
				$callback['name'] = 'Class->' . $class . $access . $callback['function'][1] . '()';
				$ref = new ReflectionMethod( $class, $callback['function'][1] );
			} else {
				$callback['name'] = $callback['function'];
				$ref = new ReflectionFunction( $callback['function'] );
			}
			$callback['line'] = $ref->getStartLine();
			$name = trim( $ref->getName() );
		} catch ( ReflectionException $e ) {
			$callback['error'] = new WP_Error( 'reflection_exception', $e->getMessage() );
		}
		return $callback;
	}

  /**
   * Display Storefront filters.
   *
   * @since   2.0
   *
   */

  public function display_filters() {
    global $filter_hook;

    if ( ! is_user_logged_in() ) {
      return;  // Avoid non logged users using this plugins functionality
    }

    if ( 'show' == isset( $_GET['storefront_filters'] ) ) {
      add_filter( 'storefront_menu_toggle_text', array( $this, 'svg_menu_toggle_text') );
      add_filter( 'storefront_copyright_text', array( $this, 'svg_copyright_text') );
      add_filter( 'storefront_credit_link', array( $this, 'svg_credit_link') );
      add_action( 'storefront_before_site', array( $this, 'global_filters') );
      add_action( 'homepage', array( $this, 'homepage_filters') );
      add_action( 'storefront_single_post_before', array( $this, 'single_filters') );
      add_action( 'storefront_before_footer', array( $this, 'widgets_filters') );
      add_action( 'storefront_footer', array( $this, 'footer_filters') );
      if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action( 'woocommerce_before_main_content', array( $this, 'woocommerce_filters') );
      }
    }

  }

  public function global_filters() {
    $title = __('Global Filters', 'storefront-visual-guide');
    $this->filters_list( $title, 'global');
  }

  public function homepage_filters() {
    $title = __('Homepage Filters', 'storefront-visual-guide');
    $this->filters_list( $title, 'homepage');
  }

  public function single_filters() {
    $title = __('Single Post Filters', 'storefront-visual-guide');
    $this->filters_list( $title, 'single');
  }

  public function widgets_filters() {
    $title = __('Sidebar and Widgets Filters', 'storefront-visual-guide');
    $this->filters_list( $title, 'widgets');
  }

  public function footer_filters() {
    $title = __('Footer Filters', 'storefront-visual-guide');
    $this->filters_list( $title, 'footer');
  }

  public function woocommerce_filters() {
    $title = __('WooCommerce Filters', 'storefront-visual-guide');
    $this->filters_list( $title, 'woocommerce');
  }

  function filters_list( $title, $area ) {
    ?> <div id="visual-filter-box" class="storefront-visual-filter-box">
      <h3><?php echo $title; ?></h3>
      <span id="svg-show-filters" class="svg-inline-button" title="Toggle display filters"><span>+</span></span>
      <div class="visual-filter-table-wrap"><table>
        <tr>
          <th><?php _e('Filter', 'storefront-visual-guide'); ?></th>
          <th><?php _e('Description', 'storefront-visual-guide'); ?></th>
        </tr>
        <?php
        foreach ( $this->filters_library( $area ) as $filter ) {
          ?>
          <tr data-svg-filter-function="<?php echo $filter['function']; ?>">
            <td class="svg-filter-action"><?php echo $filter['filter']; ?></td>
            <td class="svg-filter-description"><?php echo $filter['description']; ?></td>
          </tr>
          <?php
        }
      ?></table></div>
    </div>
    <?php
    return;
  }

  function filters_library( $filter_area ) {
    require( dirname( SVG_PLUGIN_PATH ) .'/inc/filters-library.php' );
    return $filters[ $filter_area ];
  }

  public function gvhg_hook_name( $function, $element = null, $description = null ) {
  	$function_name = str_replace( 'svg', 'storefront', $function );
  	if ( $element ) {
  		return '<' . $element . ' class="gvhg-filter-cue" data-gvhg-filter-cue="' . $function_name . '" title="' . $description . '">' . $function_name . '</' . $element . '>';
  	}
  	return $function_name;
  }

  public function svg_menu_toggle_text() {
  	return $this::gvhg_hook_name( __FUNCTION__);
  }

  public function svg_copyright_text() {
    return $this::gvhg_hook_name( __FUNCTION__);
  }

  public function svg_credit_link() {
    return false;
  }

}

return new Storefront_Visual_Guide();
