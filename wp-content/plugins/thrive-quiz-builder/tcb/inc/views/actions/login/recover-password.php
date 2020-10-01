<?php $reset_url = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ); ?>
<p><?php echo __( 'Someone has requested a password reset for the following account:', 'thrive-cb' ); ?></p>
<p><?php echo sprintf( __( 'Site Name: %s', 'thrive-cb' ), $site_name ); ?></p>
<p><?php echo sprintf( __( 'Username: %s', 'thrive-cb' ), $user->user_login ); ?></p>
<p><?php echo __( 'If this was a mistake, just ignore this email and nothing will happen.', 'thrive-cb' ); ?></p>
<p><?php echo __( 'To reset your password, visit the following address:', 'thrive-cb' ); ?> <a href="<?php echo $reset_url ?>"><?php echo $reset_url ?></a></p>
