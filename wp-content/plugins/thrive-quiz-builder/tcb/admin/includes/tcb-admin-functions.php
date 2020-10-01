<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 3/6/2017
 * Time: 11:10 AM
 */

/**
 * @return array
 */
function tcb_admin_get_localization() {
	/** @var TCB_Symbols_Taxonomy $tcb_symbol_taxonomy */
	global $tcb_symbol_taxonomy;

	return array(
		'admin_nonce'        => wp_create_nonce( TCB_Admin_Ajax::NONCE ),
		'dash_url'           => admin_url( 'admin.php?page=tve_dash_section' ),
		't'                  => include tcb_admin()->admin_path( 'includes/i18n.php' ),
		'architect_logo'     => tcb_admin()->admin_url( 'assets/images/admin-logo.png' ),
		'symbols_logo'       => tcb_admin()->admin_url( 'assets/images/admin-logo.png' ),
		'rest_routes'        => array(
			'symbols'       => tcb_admin()->tcm_get_route_url( 'symbols' ),
			'symbols_terms' => rest_url( sprintf( '%s/%s', 'wp/v2', TCB_Symbols_Taxonomy::SYMBOLS_TAXONOMY ) ),
		),
		'nonce'              => wp_create_nonce( 'wp_rest' ),
		'symbols_tax'        => TCB_Symbols_Taxonomy::SYMBOLS_TAXONOMY,
		'symbols_tax_terms'  => $tcb_symbol_taxonomy->get_symbols_tax_terms(),
		'sections_tax_terms' => $tcb_symbol_taxonomy->get_symbols_tax_terms( true ),
		'default_terms'      => $tcb_symbol_taxonomy->get_default_terms(),
	);
}

/**
 * @param array $templates
 *
 * @return array
 */
function tcb_admin_get_category_templates( $templates = array() ) {
	$return         = array();
	$no_preview_img = tcb_admin()->admin_url( 'assets/images/no-template-preview.jpg' );
	foreach ( $templates as $key => $tpl ) {
		if ( empty( $tpl['image_url'] ) ) {
			$tpl['image_url'] = $no_preview_img;
		}
		if ( isset( $tpl['id_category'] ) && is_numeric( $tpl['id_category'] ) ) {
			if ( empty( $return[ $tpl['id_category'] ] ) ) {
				$return[ $tpl['id_category'] ] = array();
			}
			$return[ $tpl['id_category'] ][] = array_merge( array( 'id' => $key ), $tpl );
		} elseif ( isset( $tpl['id_category'] ) && $tpl['id_category'] === '[#page#]' ) {
			$return[ $tpl['id_category'] ][] = array_merge( array( 'id' => $key ), $tpl );
		} else {
			if ( empty( $return['uncategorized'] ) ) {
				$return['uncategorized'] = array();
			}
			$return['uncategorized'][] = array_merge( array( 'id' => $key ), $tpl );
		}
	}

	return $return;
}

/**
 * Filter content templates by their name
 *
 * @param array $templates
 * @param string $search
 *
 * @return array
 */
function tcb_filter_templates( $templates, $search ) {
	$result = array();

	foreach ( $templates as $template ) {
		if ( stripos( $template['name'], $search ) !== false ) {
			$result[] = $template;
		}
	}

	return $result;
}

/**
 * Displays an icon using svg format
 *
 * @param string $icon
 * @param bool $return whether to return the icon as a string or to output it directly
 *
 * @return string|void
 */
function tcb_admin_icon( $icon, $return = false ) {
	$html = '<svg class="tcb-admin-icon tcb-admin-icon-' . $icon . '"><use xlink:href="#icon-' . $icon . '"></use></svg>';

	if ( false !== $return ) {
		return $html;
	}

	echo $html;
}
