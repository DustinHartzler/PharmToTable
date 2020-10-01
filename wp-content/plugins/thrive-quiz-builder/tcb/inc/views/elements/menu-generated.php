<?php
$menus = tve_get_custom_menus();

$attributes = array(
	'menu_id'          => isset( $_POST['menu_id'] ) ? $_POST['menu_id'] : ( ! empty( $menus[0] ) ? $menus[0]['id'] : 'custom' ),
	'uuid'             => isset( $_POST['uuid'] ) ? $_POST['uuid'] : '',
	/* color is not used anymore. kept here for backwards compat */
	'color'            => isset( $_POST['colour'] ) ? $_POST['colour'] : '',
	'dir'              => isset( $_POST['dir'] ) ? $_POST['dir'] : 'tve_horizontal',
	'font_class'       => isset( $_POST['font_class'] ) ? $_POST['font_class'] : '',
	'font_size'        => isset( $_POST['font_size'] ) ? $_POST['font_size'] : '',
	'ul_attr'          => isset( $_POST['ul_attr'] ) ? $_POST['ul_attr'] : '',
	'link_attr'        => isset( $_POST['link_attr'] ) ? $_POST['link_attr'] : '',
	'top_link_attr'    => isset( $_POST['top_link_attr'] ) ? $_POST['top_link_attr'] : '',
	'trigger_attr'     => isset( $_POST['trigger_attr'] ) ? $_POST['trigger_attr'] : '',
	'primary'          => isset( $_POST['primary'] ) && ( $_POST['primary'] == 'true' || $_POST['primary'] == '1' ) ? 1 : '',
	'head_css'         => isset( $_POST['head_css'] ) ? $_POST['head_css'] : '',
	'background_hover' => isset( $_POST['background_hover'] ) ? $_POST['background_hover'] : '',
	'main_hover'       => isset( $_POST['main_hover'] ) ? $_POST['main_hover'] : '',
	'child_hover'      => isset( $_POST['child_hover'] ) ? $_POST['child_hover'] : '',
	'dropdown_icon'    => isset( $_POST['dropdown_icon'] ) ? $_POST['dropdown_icon'] : 'style_1',
	'mobile_icon'      => isset( $_POST['mobile_icon'] ) ? $_POST['mobile_icon'] : '',
	'template'         => isset( $_POST['template'] ) ? $_POST['template'] : 'first',
	'template_name'    => isset( $_POST['template_name'] ) ? $_POST['template_name'] : 'Basic',
	'unlinked'         => isset( $_POST['unlinked'] ) ? $_POST['unlinked'] : new stdClass(),
	'icon'             => isset( $_POST['icon'] ) ? $_POST['icon'] : new stdClass(),
	'top_cls'          => isset( $_POST['top_cls'] ) ? $_POST['top_cls'] : new stdClass(),
	'type'             => isset( $_POST['type'] ) ? $_POST['type'] : '',
	'layout'           => isset( $_POST['layout'] ) ? $_POST['layout'] : array( 'default' => 'grid' ),
	'mega_desc'        => isset( $_POST['mega_desc'] ) ? $_POST['mega_desc'] : array(),
	'actions'          => isset( $_POST['actions'] ) ? $_POST['actions'] : new stdClass(),
	'images'           => isset( $_POST['images'] ) ? $_POST['images'] : new stdClass(),
	'img_settings'     => isset( $_POST['img_settings'] ) ? $_POST['img_settings'] : new stdClass(),
	'logo'             => isset( $_POST['logo'] ) && $_POST['logo'] !== 'false' ? $_POST['logo'] : array(),
);

if ( ! $attributes['dropdown_icon'] && $attributes['dir'] === 'tve_vertical' ) {
	$icon_styles                 = tcb_elements()->element_factory( 'menu' )->get_icon_styles();
	$styles                      = array_keys( $icon_styles );
	$attributes['dropdown_icon'] = reset( $styles );
}

$attributes['font_class'] .= ( ! empty( $_POST['custom_class'] ) ? ' ' . $_POST['custom_class'] : '' );
?>

<div class="thrive-shortcode-config" style="display: none !important"><?php echo '__CONFIG_widget_menu__' . json_encode( array_filter( $attributes ) ) . '__CONFIG_widget_menu__'; ?></div>
<?php echo tve_render_widget_menu( $attributes ); ?>
