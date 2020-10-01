<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 10/4/2016
 * Time: 11:48 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

global $variation;
if ( empty( $variation ) ) {
	$variation = tqb_get_variation( $_REQUEST[ Thrive_Quiz_Builder::VARIATION_QUERY_KEY_NAME ] );
}

$page_type_name   = tqb()->get_style_page_name( $variation['post_type'] );
$current_template = ! empty( $variation[ Thrive_Quiz_Builder::FIELD_TEMPLATE ] ) ? $variation[ Thrive_Quiz_Builder::FIELD_TEMPLATE ] : '';
$templates        = TQB_Template_Manager::get_templates( $variation['post_type'], $variation['quiz_id'] );
?>

<h2 class="tcb-modal-title"><?php echo sprintf( __( 'Choose %s Template', Thrive_Quiz_Builder::T ), $page_type_name ); ?></h2>
<div class="margin-top-20">
	<?php echo __( 'Any changes you’ve made to the current page will be lost when you select a new template. We recommend you to save your current template first.', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="tve-templates-wrapper">
	<div class="tve-header-tabs">
		<div class="tab-item active click" data-fn="tab_click" data-content="default"><?php echo __( 'Default Templates', Thrive_Quiz_Builder::T ); ?></div>
		<div class="tab-item click" data-fn="tab_click" data-content="saved"><?php echo sprintf( __( 'Saved %s Templates', Thrive_Quiz_Builder::T ), $page_type_name ); ?></div>
	</div>
	<div class="tve-tabs-content">
		<div class="tve-tab-content active" data-content="default">
			<div class="tve-default-templates-list expanded-set">
				<?php foreach ( $templates as $data ) : ?>
					<div class="tve-template-item">
						<div class="template-wrapper click<?php echo $current_template == $data['key'] ? ' active' : '' ?>" data-fn="select_template" data-key="<?php echo $data['key']; ?>">
							<div class="template-thumbnail" style="background-image: url('<?php echo $data['thumbnail']; ?>')"></div>
							<div class="template-name">
								<?php echo $data['name']; ?>
							</div>
							<div class="selected"></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="tve-tab-content" data-content="saved">
			<div class="tve-saved-templates-list expanded-set"></div>
		</div>
		<div class="tve-template-preview"></div>
	</div>
</div>
<div class="tcb-modal-footer clearfix padding-top-20 row end-xs">
	<div class="col col-xs-12">
		<button type="button" class="tcb-right tve-button medium green click" data-fn="save">
			<?php echo __( 'Choose Template', Thrive_Quiz_Builder::T ) ?>
		</button>
	</div>
</div>
