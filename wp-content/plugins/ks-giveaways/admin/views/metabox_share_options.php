<p>Use this area to enable or disable social media platforms in the sharing section of the giveaway.</p>
<table class="form-table">
  <tr valign="top">
    <th scope="row">
      <label>Facebook</label>
    </th>
    <td>
      <?php if (metadata_exists('post', $post->ID, '_enable_facebook')): ?>
        <input type="checkbox" name="enable_facebook" value="1" <?php checked(get_post_meta($post->ID, '_enable_facebook', true), 1); ?> />
      <?php else: ?>
        <input type="checkbox" name="enable_facebook" value="1" checked />
      <?php endif; ?>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Twitter</label>
    </th>
    <td>
      <?php if (metadata_exists('post', $post->ID, '_enable_twitter')): ?>
        <input type="checkbox" name="enable_twitter" value="1" <?php checked(get_post_meta($post->ID, '_enable_twitter', true), 1); ?> />
      <?php else: ?>
        <input type="checkbox" name="enable_twitter" value="1" checked />
      <?php endif; ?>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>LinkedIn</label>
    </th>
    <td>
      <?php if (metadata_exists('post', $post->ID, '_enable_linkedin')): ?>
        <input type="checkbox" name="enable_linkedin" value="1" <?php checked(get_post_meta($post->ID, '_enable_linkedin', true), 1); ?> />
      <?php else: ?>
        <input type="checkbox" name="enable_linkedin" value="1" checked />
      <?php endif; ?>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Pinterest</label>
    </th>
    <td>
      <?php if (metadata_exists('post', $post->ID, '_enable_pinterest')): ?>
        <input type="checkbox" name="enable_pinterest" value="1" <?php checked(get_post_meta($post->ID, '_enable_pinterest', true), 1); ?> />
      <?php else: ?>
        <input type="checkbox" name="enable_pinterest" value="1" checked />
      <?php endif; ?>
    </td>
  </tr>
</table>
