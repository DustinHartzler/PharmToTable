<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Help cards content
 */
$items = array(
	array(
		'title'       => __( 'Knowledge Base', 'thrive-cb' ),
		'picture'     => tve_editor_url( 'editor/css/images/help-corner/knowledge-base.svg' ),
		'picture-alt' => 'Knowledge Base Item Picture',
		'text'        => array(
			__( 'Search our extensive knowledge base for “how-to” articles and instructions.', 'thrive-cb' ),
		),
		'class'       => 'knowledge-base',

	),
	array(
		'title'       => __( 'Thrive University', 'thrive-cb' ),
		'picture'     => tve_editor_url( 'editor/css/images/help-corner/thrive-university.svg' ),
		'picture-alt' => 'Thrive University Item Picture',
		'text'        => array(
			__( 'Take one of our free online courses on website building and online marketing.', 'thrive-cb' ),
		),
		'class'       => 'thrive-university',
	),
	array(
		'title'       => __( 'Get Support', 'thrive-cb' ),
		'picture'     => tve_editor_url( 'editor/css/images/help-corner/support.svg' ),
		'picture-alt' => 'Support Item Picture',
		'text'        => array(
			__( 'Contact our friendly support team who will help you with any issues or questions.', 'thrive-cb' ),
		),
		'class'       => 'support',
	),
);
?>

<h2><?php echo __( 'Help Corner', 'thrive-cb' ); ?></h2>
<div class="parent">
	<?php foreach ( $items as $item ) : ?>
		<div class="<?php echo $item['class']; ?> click item" data-fn="chooseLink">
			<img src="<?php echo $item['picture']; ?>" alt="<?php echo $item['picture-alt']; ?>"/>
			<div class="item-title">
				<span><?php echo $item['title']; ?></span>
			</div>
			<div class="item-text">
				<?php foreach ( $item['text'] as $text ) : ?>
					<p><?php echo $text; ?></p>
				<?php endforeach; ?>
			</div>
			<?php if ( $item['title'] == 'Knowledge Base' ): ?>
				<div class="kb-search">
					<input type="text" class="kb-input-search keyup-enter" data-fn="searchKB" placeholder="Search knowledge base" autocomplete="off">
					<?php tcb_icon( 'search-regular', false, 'sidebar', 'click', array( 'data-fn' => 'searchKB' ) ) ?>
				</div>
			<?php endif ?>
		</div>
	<?php endforeach; ?>
</div>
