<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>
<div class="tcb-modal-header">
	<span class="tcb-new-tab click" data-fn="openIntercomArticle">
		<?php tcb_icon( 'external-link-regular' ); ?>
		<?php echo __( 'Open in new tab', 'thrive-cb' ) ?>
	</span>
</div>
<div class="tve-modal-content">
	<div class="tcb-video-instructions">
		<iframe></iframe>
	</div>
	<div class="tcb-article-instructions">
		<h1 class="tcb-article-title"></h1>
		<div class="tcb-article-content"></div>
	</div>
</div>
