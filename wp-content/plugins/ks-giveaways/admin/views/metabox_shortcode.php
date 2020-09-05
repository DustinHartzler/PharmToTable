<h4>On This Website:</h4>
<p>
	You can embed this giveaway in a post or a page on this site by using this shortcode:
</p>
<p>
	<code>[giveaway id=<?php echo $post->ID; ?>]</code>
</p>
<label for="embed_page_url">Embed Post ID</label><br />
<input type="number" name="embed_post_id" id="embed_post_id" value="<?php echo esc_attr(get_post_meta($post->ID, '_embed_post_id', true)) ?>" style="width:100%;" />	
<p class="description">
	Enter the ID of the post or page where this giveaway is embedded to use that URL in confirmation emails.
</p>
<hr />
<h4>On Any Website:</h4>
<p>
	Copy &amp; paste the HTML below to embed this giveaway on any website.
</p>
<p>
	<textarea style="width:100%;" rows="3" type="text" onclick="this.select()" readonly><?php echo do_shortcode(sprintf('[giveaway id=%d external=true]', $post->ID)); ?></textarea>
</p>
