<?php
/**
 * Customs RSS template with related posts.
 *
 * Place this file in your theme's directory.
 *
 * @package sometheme
 * @subpackage theme
 */


/**
 * Get related posts.
 */
function my_rss_related() {

	global $post;

	// Setup post data
	$pid     = $post->ID;
	$tags    = wp_get_post_tags( $pid );
	$tag_ids = array();

	// Loop through post tags
	foreach ( $tags as $individual_tag ) {
		$tag_ids[] = $individual_tag->term_id;
	}

	// Execute WP_Query
	$related_by_tag = new WP_Query( array(
		'tag__in'          => $tag_ids,
		'post__not_in'     => array( $pid ),
		'posts_per_page'   => 3,
	) );

	// Loop through posts and build HTML
	if ( $related_by_tag->have_posts() ) :

		echo 'Related:<br />';

			while ( $related_by_tag->have_posts() ) : $related_by_tag->the_post();
				echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a><br />';
			endwhile;

		else :
			echo '';
	endif;

	wp_reset_postdata();
}


/**
 * Feed defaults.
 */

// Query the Podcast Custom Post Type and fetch the latest 100 posts
$args = array( 'post_type' => 'podcast', 'posts_per_page' => 100 );
$loop = new WP_Query( $args );

header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
$frequency  = 1;        // Default '1'. The frequency of RSS updates within the update period.
$duration   = 'hourly'; // Default 'hourly'. Accepts 'hourly', 'daily', 'weekly', 'monthly', 'yearly'.
$postlink   = '<br /><a href="' . get_permalink() . '">View on YourWebsiteEngineer.com</a><br /><br />';
$postimages = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );

// Check for images
if ( $postimages ) {

	// Get featured image
	$postimage = $postimages[0];

} else {

	// Fallback to a default
	$postimage = get_stylesheet_directory_uri() . '/images/default.jpg';
}


/**
 * Start RSS feed.
 */
echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	xmlns:cc="http://web.resource.org/cc/"
	xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
	xmlns:media="http://search.yahoo.com/mrss/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	<?php do_action( 'rss2_ns' ); ?>
>

  <!-- RSS feed defaults -->
	<channel>
		<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
		<title><?php bloginfo_rss( 'name' ); wp_title_rss(); ?></title>
		<pubDate>  </pubDate>
		<lastBuildDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
		<link><?php bloginfo_rss( 'url' ) ?></link>
		<language><?php bloginfo_rss( 'language' ); ?></language>
		<copyright><![CDATA[]]></copyright>
		<description><?php bloginfo_rss( 'description' ) ?></description>
		<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', $duration ); ?></sy:updatePeriod>
		<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', $frequency ); ?></sy:updateFrequency>

		<!-- Feed Logo (optional) -->
		<image>
			<url>https://YourWebsiteEngineer.com/AlbumArt_Large.png</url>
			<title>
				<?php bloginfo_rss( 'description' ) ?>
			</title>
			<link><?php bloginfo_rss( 'url' ) ?></link>
		</image>

		<?php do_action( 'rss2_head' ); ?>

		<!-- Start loop -->
		<?php $post = $posts[0]; $c=0;?>
		<?php while ( $loop->have_posts() ) : $loop->the_post(); 
			$postlink   = '<br /><a href="' . get_permalink() . '">View on YourWebsiteEngineer.com</a><br /><br />';
			$length = get_post_meta( $post_id, 'length', true ); ?>

			<item>
				<title><?php the_title_rss(); ?></title>
				<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
				<guid isPermaLink="false"><?php the_guid(); ?></guid>
				<link><?php the_permalink_rss(); ?></link>
				<itunes:image href="http://static.libsyn.com/p/assets/d/0/e/5/d0e52c443785a261/347_-_Quickly_Work_On_Your_Site_with_WP-CLI.png" />
				<description><![CDATA[<?php echo the_excerpt();?>]]></description>
				<content:encoded>
					<?php $c++; if( $c <= 10) :?>
						<![CDATA[<?php echo the_post_thumbnail( array (420,164)); echo the_content(); echo $postlink;  ?>]]>
					<?php else :?>
						<![CDATA[<?php echo the_post_thumbnail( array (420,164)); echo the_excerpt(); echo $postlink;  ?>]]>
					<?php endif;?>
				</content:encoded>
				<?php rss_enclosure() ?>
				<itunes:duration><?php echo get_post_meta( $post_id, 'dustin', true ); ?></itunes:duration>
				<itunes:explicit>no</itunes:explicit>
				<itunes:keywords />
				<itunes:subtitle><![CDATA[<?php echo the_excerpt();?>]]></itunes:subtitle>
				
				
				
				<author><?php the_author(); ?></author>
				<image>
					<url><?php echo esc_url( $postimage ); ?>"/></url>
				</image>

			</item>


		<?php endwhile; ?>
	</channel>
</rss>
