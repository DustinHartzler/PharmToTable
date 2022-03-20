<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
$admin_base_url = admin_url( '/', is_ssl() ? 'https' : 'admin' );
// for some reason, the above line does not work in some instances
if ( is_ssl() ) {
	$admin_base_url = str_replace( 'http://', 'https://', $admin_base_url );
}
?>

<div id="tve-menu-component" class="tve-component" data-view="CustomMenu">
	<div class="action-group">
		<div class="dropdown-header" data-prop="docked">
			<div class="group-description">
				<?php echo esc_html__( 'Main Options', 'thrive-cb' ); ?>
			</div>
			<i></i>
		</div>
		<div class="dropdown-content">
			<div class="tve-menu-display-control">
				<div class="tve-control hide-tablet hide-mobile" data-view="MenuDirection"></div>
				<div class="tve-control" data-view="MenuDisplay"></div>
			</div>
			<hr class="mt-10">
			<div class="tve-control" data-view="MenuSource"></div>
			<div class="control-grid hide-tablet hide-mobile only-wp">
				<span class="blue-text">
					<svg class="tcb-icon tcb-icon-info"><use xlink:href="#icon-info"></use></svg>
					<a class="tve-edit-menu tve-wpmenu-info" href="<?php echo esc_url( $admin_base_url ); ?>nav-menus.php?action=edit&menu=0" target="_blank">
						<?php echo esc_html__( 'Click here to edit this WordPress menu.', 'thrive-cb' ); ?>
					</a>
				</span>
			</div>
			<div class="tve-control hide-states" data-view="MenuPalettes"></div>
			<hr>
			<div class="tve-control btn-group-light no-space" data-view="MenuSpacing"></div>
			<div class="spacing">
				<div class="tve-control" data-view="HorizontalSpacing"></div>
				<div class="tve-control" data-view="VerticalSpacing"></div>
				<div class="tve-control pb-10" data-view="BetweenSpacing"></div>
			</div>
			<div class="tve-control if-horizontal" data-view="LogoSplit"></div>
			<hr>
			<div class="tve-control if-vertical" data-view="ContentAlign"></div>
			<div class="mb-10 if-vertical"><?php echo esc_html__( 'Menu alignment', 'thrive-cb' ); ?></div>
			<div class="tve-control if-vertical pt-5 gl-st-button-toggle-2" data-key="Align" data-view="ButtonGroup"></div>
			<div class="tve-control if-vertical gl-st-button-toggle-2" data-view="MenuWidth"></div>
			<div class="mb-10"><?php echo esc_html__( 'Sub menu options', 'thrive-cb' ); ?></div>
			<div class="tve-control if-vertical hide-tablet hide-mobile" data-view="DropdownDirection"></div>
			<div class="tve-control" data-key="DropdownIcon" data-initializer="dropdownIcon"></div>
			<div class="tve-control if-not-hamburger" data-view="DropdownAnimation"></div>
			<div class="tve-control hide-tablet hide-mobile if-not-hamburger" data-view="DisableActiveLinks"></div>
			<div class="if-hamburger">
				<div class="tve-control" data-view="MobileIcon"></div>
				<div class="tve-control" data-view="IconColor"></div>
				<div class="tve-control mb-10" data-view="IconSize"></div>
			</div>
			<div class="if-hamburger">
				<div class="tve-control" data-view="MenuState"></div>
				<div class="tve-control mb-10" data-view="MobileSide"></div>
			</div>
			<div class="hide-tablet hide-mobile only-custom">
				<hr>
				<div class="tve-advanced-controls extend-grey menu-items">
					<div class="dropdown-header" data-prop="advanced">
						<span>
							<?php echo esc_html__( 'Menu Items', 'thrive-cb' ); ?>
						</span>
					</div>
					<div class="dropdown-content pl-0 pr-0 tcb-relative pt-25">
						<button class="tve-button blue click tcb-absolute" style="right: 1px; top: 0; padding: 5px 8px;" data-fn="addMenuItem">
							<?php echo esc_html__( 'Add new', 'thrive-cb' ); ?>
						</button>
						<div class="clear"></div>
						<div class="tve-control tve-order-list mb-0" data-key="OrderList" data-initializer="orderItems"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

