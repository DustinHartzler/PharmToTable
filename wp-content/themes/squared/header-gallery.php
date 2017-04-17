<?php
$options                               = thrive_get_options_for_post( get_the_ID() );
$thrive_meta_postformat_gallery_images = get_post_meta( get_the_ID(), '_thrive_meta_postformat_gallery_images', true );
$thrive_gallery_ids                    = explode( ",", $thrive_meta_postformat_gallery_images );
?>
<?php
if ( count( $thrive_gallery_ids ) > 0 ):
	$first_img_url = wp_get_attachment_url( $thrive_gallery_ids[0] );
	?>
	<div class="hui hru fha" style="background-image: url('<?php echo trim( $first_img_url ); ?>')"
	     id="thrive-gallery-header" data-count="<?php echo count( $thrive_gallery_ids ); ?>" data-index="0">
		<img id="thive-gallery-dummy" class="tt-dmy" src="<?php echo trim( $first_img_url ); ?>" alt=""/>
		<div class="hut">
			<div class="wrp">
				<?php if ( $options['show_post_title'] != 0 ): ?>
					<h1><?php the_title(); ?></h1>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="gnav clearfix">
		<a class="gprev" href=""><?php _e( "Previous", 'thrive' ); ?></a>
		<div class="gwrp">
			<ul class="clearfix">
				<?php
				foreach ( $thrive_gallery_ids as $key => $id ):
					$img_url = wp_get_attachment_url( $id );
					if ( $img_url ):
						?>
						<li id="li-thrive-gallery-item-<?php echo $key; ?>">
							<a class="thrive-gallery-item" href=""
							   style="background-image: url('<?php echo trim( $img_url ); ?>');"
							   data-image="<?php echo trim( $img_url ); ?>" data-index="<?php echo $key; ?>"></a>
						</li>
						<?php
					endif;
				endforeach;
				?>
			</ul>
		</div>
		<a class="gnext" href=""><?php _e( "Next", 'thrive' ); ?></a>
	</div>
<?php endif; ?>