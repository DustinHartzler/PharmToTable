<?php
$query = str_replace( '"', "'", json_encode( array(
	'filter'               => 'custom',
	'related'              => array(),
	'post_type'            => 'post',
	'orderby'              => 'date',
	'order'                => 'DESC',
	'posts_per_page'       => '1',
	'offset'               => '1',
	'no_posts_text'        => esc_html__('There are no posts to display.'),
	'exclude_current_post' => array( '1' ),
	'rules'                => array(),
) ) );
?>
<div class="thrv_wrapper tcb-elem-placeholder tcb-compact-element tcb-ct-placeholder tcb-selector-no_save tcb-featured-list tve_no_drag tve_no_duplicate tve-draggable tve-droppable"
	 data-element-name="Featured Content List" data-tcb-elem-type="post_list_featured" data-ct="post_list_featured-0"
	 data-query="<?php echo $query; ?>">
	<span class="tcb-inline-placeholder-action with-icon"><?php tcb_icon( 'post-list', false, 'editor' ); ?>
		<?php echo esc_html__( 'Insert Featured Content List', 'thrive-cb' ) ?>
	</span>
</div>