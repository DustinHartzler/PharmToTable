<?php
/*
Template Name: Newsletter Page
*/
?>
<?php get_header(); ?>
    <div class="body-container">
        <div class="body wrapper-404 clearfix">

            <div class="left-side">
                <img src="https://pharmtotable.life/wp-content/uploads/2017/08/freeebook.png" id="logo2" class="leadstyle-image" style="max-width: 268px;"><p class="leadstyle-text">Holy Smokes! Unfortunately, the information you're looking for is unavailable.</p>
                <p class="style2">BUT WAIT!</p>
                <p class="style3">Don't leave empty handed. <br />Grab this <a href="#" data-reveal-id="myModal">free ebook</a>!</p>
            </div>

            <div class="right-side">
                <div class="arrow-box"></div>
                <img src="<?php bloginfo('template_url'); ?>/img/404/book.png" id="book"><p class="title">The most useful plugins in the WordPress Repository</p>
                <div class="cl"></div>
                <button href="#" class="button" data-reveal-id="myModal">Send My eBook Now!</button>
                <a class="link" data-reveal-id="myModal">Click Here For Free Instant Access</a>
            </div>
        </div>
    </div>

	<?php get_template_part( 'popup', '50' ); ?>


<?php get_footer(); ?>
