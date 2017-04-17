<?php
$fname         = get_the_author_meta( 'first_name' );
$lname         = get_the_author_meta( 'last_name' );
$desc          = get_the_author_meta( 'description' );
$thrive_social = array_filter( array(
	"twt" => get_the_author_meta( 'twitter' ),
	"fbk" => get_the_author_meta( 'facebook' ),
	"ggl" => get_the_author_meta( 'gplus' )
) );

$author_name  = get_the_author_meta( 'display_name' );
$display_name = empty( $author_name ) ? $fname . " " . $lname : $author_name;
?>
<!-- TODO - the image has to link to the author page -->
<article>
	<div class="awr aut">
		<div class="amg">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
				<div class="amgi"
				     style="background-image: url('<?php echo _thrive_get_avatar_url( get_avatar( get_the_author_meta( 'user_email' ), 180 ) ); ?>')"></div>
			</a>
		</div>
		<div class="aat right">
			<h4><?php _e( "About the Author", 'thrive' ); ?></h4>
			<p><?php echo $desc; ?></p>
		</div>
		<div class="clear"></div>
	</div>
</article>