<?php require_once( TVE_DASH_PATH . '/templates/header.phtml' ); ?>
<div class="tvd-am-breadcrumbs">
	<ul>
		<li class="tvd-breadcrumb"><a href="<?php echo admin_url( 'admin.php?page=tve_dash_section' ); ?>"><?php echo __( 'Thrive Dashboard', TVE_DASH_TRANSLATE_DOMAIN ); ?></a></li>
		<li class="tvd-breadcrumb"><?php echo __( 'Access Manager', TVE_DASH_TRANSLATE_DOMAIN ); ?></li>
	</ul>
</div>
<div class="tvd-v-spacer vs-2"></div>
<div class="tvd-access-manager-setting">
	<h3 class="tvd-am-section-title"><?php echo __( 'Access Manager', TVE_DASH_TRANSLATE_DOMAIN ); ?></h3>
	<div class="tvd-v-spacer vs-2"></div>
	<table>
		<thead>
		<th class="tvd-am-products" colspan="2"><?php echo __( 'Products', TVE_DASH_TRANSLATE_DOMAIN ); ?></th>
		<?php foreach ( $all_roles as $role ): ?>
			<th class="tvd-am-role"><a href="<?php echo $role['url']; ?>" target="_blank"><?php echo $role['name']; ?></a></th>
		<?php endforeach; ?>
		</thead>
		<tbody>
		<?php foreach ( $all_products as $product ): ?>
			<tr class="tvd-am-row">
				<td class="tvd-am-icon">
					<img src="<?php echo $product->logo; ?>" alt="" class="tvd-responsive-img">
				</td>
				<td class="tvd-am-title"><?php echo __( $product->name, TVE_DASH_TRANSLATE_DOMAIN ); ?></td>
				<?php foreach ( $product->roles as $role ): ?>
					<td class="tvd-am-role">
						<a class="<?php echo $role['can_use'] === true ? 'tvd-am-with-cap' : 'tvd-am-without-cap' ?> tvd-am-cap click"
						   data-cap="<?php echo $role['prod_capability'] ?>"
						   data-role="<?php echo $role['role'] ?>">
							<?php if ( $product->tag === 'td' && $role['role'] === 'administrator' ) {
								dashboard_icon( 'dash-cap' );
							} else {
								$role['can_use'] ? dashboard_icon( 'cap' ) : dashboard_icon( 'no-cap' );
							} ?></a>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div class="tvd-col tvd-m6 tvd-am-dash-button">
		<a href="<?php echo admin_url( 'admin.php?page=tve_dash_section' ); ?>" class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-gray">
			<?php echo __( "Back To Dashboard", TVE_DASH_TRANSLATE_DOMAIN ); ?>
		</a>
	</div>
</div>
