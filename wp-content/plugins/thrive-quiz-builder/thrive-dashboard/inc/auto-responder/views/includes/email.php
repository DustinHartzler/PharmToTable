<?php if ( ! empty( $args['name'] ) && false !== $has_shortcode ) : ?>
	<br>
	<b><?php echo __( 'Name:', TVE_DASH_TRANSLATE_DOMAIN ); ?> </b> <span> <?php echo $args['name']; ?> </span>
<?php endif; ?>

<?php if ( false !== $has_shortcode ) : ?>
	<br>
	<b><?php echo __( 'Email:', TVE_DASH_TRANSLATE_DOMAIN ); ?></b> <span> <?php echo $args['email']; ?> </span>
<?php endif; ?>

<?php if ( ! empty( $args['phone'] ) && false !== $has_shortcode ) : ?>
	<br>
	<b><?php echo __( 'Phone:', TVE_DASH_TRANSLATE_DOMAIN ); ?></b> <span> <?php echo $args['phone']; ?> </span>
<?php endif; ?>

<?php if ( isset( $args['include_date'] ) && 1 === (int) $args['include_date'] ) : ?>
	<br>
	<b><?php echo __( 'Date:', TVE_DASH_TRANSLATE_DOMAIN ); ?> </b> <span> <?php echo date_i18n( 'jS F, Y' ); ?> </span>
<?php endif; ?>

<?php if ( isset( $args['include_time'] ) && 1 === (int) $args['include_time'] ) : ?>
	<br>
	<b><?php echo __( 'Time:', TVE_DASH_TRANSLATE_DOMAIN ); ?> </b> <span> <?php echo $time; ?> </span>
<?php endif; ?>

<?php if ( isset( $args['include_page_url'] ) && 1 === (int) $args['include_page_url'] ) : ?>
	<br>
	<b><?php echo __( 'Page URL:', TVE_DASH_TRANSLATE_DOMAIN ); ?> </b> <span> <a href="<?php echo $args['url']; ?>"> <?php echo $args['url']; ?> </a> </span>
<?php endif; ?>

<?php if ( isset( $args['include_ip'] ) && 1 === (int) $args['include_ip'] ) : ?>
	<br>
	<b><?php echo __( 'IP Address:', TVE_DASH_TRANSLATE_DOMAIN ); ?> </b> <span> <?php echo tve_dash_get_ip(); ?> </span>
<?php endif; ?>

<?php if ( isset( $args['include_device_settings'] ) && 1 === (int) $args['include_device_settings'] ) : ?>
	<br>
	<b><?php echo __( 'Device Settings:', TVE_DASH_TRANSLATE_DOMAIN ); ?> </b> <span> <?php echo htmlspecialchars( $_SERVER['HTTP_USER_AGENT'] ); ?> </span>
<?php endif; ?>
<br>
