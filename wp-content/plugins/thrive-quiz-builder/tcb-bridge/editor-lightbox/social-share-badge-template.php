<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
$style_templates = tqb()->get_tcb_social_share_badge_templates();
?>

<h2 class="tcb-modal-title"><?php echo __( 'Social share badge template', Thrive_Quiz_Builder::T ); ?></h2>
<div class="margin-top-20">
	<?php echo __( 'Select the display template for the social share badge.', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="tve-templates-wrapper">
	<div class="tve-default-templates-list expanded-set">
		<?php foreach ( $style_templates as $key => $template ) : ?>
			<div class="tve-template-item">
				<div class="template-wrapper click" data-fn="select_template" data-key="<?php echo $template['file']; ?>">
					<div class="template-thumbnail" style="background-image: url('<?php echo $template['image']; ?>')"></div>
					<div class="template-name">
						<?php echo $template['name']; ?>
					</div>
					<div class="selected"></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<div class="tcb-modal-footer clearfix padding-top-20 row end-xs">
	<div class="col col-xs-12">
		<button type="button" class="tcb-right tve-button medium green click" data-fn="choose_template">
			<?php echo __( 'Choose Template', Thrive_Quiz_Builder::T ) ?>
		</button>
	</div>
</div>
