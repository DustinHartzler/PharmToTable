<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

$variation         = tqb_get_variation( $_REQUEST[ Thrive_Quiz_Builder::VARIATION_QUERY_KEY_NAME ] );
$variation_manager = new TQB_Variation_Manager( $variation['quiz_id'], $variation['page_id'] );
$child_variations  = $variation_manager->get_page_variations( array( 'parent_id' => $variation['id'] ) );
?>

<h2 class="tcb-modal-title"><?php echo __( 'Import content', Thrive_Quiz_Builder::T ); ?></h2>
<div class="margin-top-20">
	<?php echo __( 'Select the state you want to bring content from:', Thrive_Quiz_Builder::T ) ?>
</div>
<div class="row margin-top-20">
	<div class="col col-xs-12">
		<select id="tqb-import-from">
			<option value=""><?php echo __( 'Select an interval', Thrive_Quiz_Builder::T ) ?></option>
			<?php
			foreach ( $child_variations as $child ) :
				?>
				<option value="<?php echo $child['id']; ?>"><?php echo $child['post_title']; ?></option>
				<?php
			endforeach;
			?>
		</select>
	</div>
</div>
<div class="tcb-modal-footer clearfix padding-top-20 row end-xs">
	<div class="col col-xs-12">
		<button type="button" class="tcb-right tve-button medium green click" data-fn="import_content">
			<?php echo __( 'Import', Thrive_Quiz_Builder::T ) ?>
		</button>
	</div>
</div>
