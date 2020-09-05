<style type="text/css">
  .entry-actions select, .entry-actions input {display:block;}
</style>
<div data-ng-app="KingSumoAdminApp" data-ng-controller="entryActions">
  <p>This section lets you add "entry actions" which award contestants with entries for completing them.</p>
  <table class="form-table entry-actions">
    <tr valign="top" data-ng-repeat="action in entryActions" class="ng-cloak" style="border-bottom:1px solid #ddd;" data-ng-form="action{{$index}}">
      <th scope="row">
        Action #{{$index + 1}}<br />
        <em style="color:#666">{{getActionDisplayName(action.type)}}</em>
      </th>
      <td>
        <select class="regular-text" style="margin-bottom:7px;" data-ng-if="action.type === 'social'" name="<?php echo KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS; ?>[{{$index}}][platform]" data-ng-model="action.platform" data-ng-change="changePlatform($index)">
          <option data-ng-repeat="platform in socialMediaPlatforms">{{platform}}</option>
        </select>
        <input type="text" name="<?php echo KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS; ?>[{{$index}}][url]" placeholder="URL" class="regular-text" data-ng-value="action.url" style="margin-bottom:7px;" data-ng-if="usesURLField($index)" />
        <input type="text" name="<?php echo KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS; ?>[{{$index}}][username]" placeholder="Username" class="regular-text" data-ng-value="action.username" style="margin-bottom:7px;" data-ng-if="usesUsernameField($index)" />
        <input type="text" name="<?php echo KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS; ?>[{{$index}}][text]" placeholder="Text" class="regular-text" data-ng-model="action.text"  style="margin-bottom:7px;" />
        <input type="number" name="<?php echo KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS; ?>[{{$index}}][entries]" placeholder="Number of entries" class="regular-text" data-ng-value="action.entries" />
        <input type="hidden" name="<?php echo KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS; ?>[{{$index}}][type]" data-ng-value="action.type">
        <input type="hidden" name="<?php echo KS_GIVEAWAYS_OPTION_ENTRY_ACTIONS; ?>[{{$index}}][id]" data-ng-value="action.id">
        <p class="description">How many entries this action is worth.</p>
        <p><a data-ng-click="deleteAction($index)" href="javascript:;" style="color:#a00;">Delete</a></p>
      </td>
    </tr>
  </table>
  <!--<p>
    <button class="button" type="button" data-ng-click="addAction()"><span class="dashicons dashicons-plus" style="padding-top:4px;"></span> Add Action</button>
  </p>-->
  <p>
    <select data-ng-change="addAction(addActionType)" data-ng-model="addActionType">
      <option value="0">+ Add Action</option>
      <option value="link">Link</option>
      <option value="social">Social Media Follow</option>
      <option value="youtube-video">Watch a YouTube Video</option>
      <!--<option value="input">User Input</option>-->
    </select>
  </p>
</div>

<script>
  var KingSumoAdminGlobals = {};
  KingSumoAdminGlobals.entryActions = <?php echo json_encode(get_post_meta($post->ID, '_entry_actions', true)) ?>;
  KingSumoAdminGlobals.post = {};
  KingSumoAdminGlobals.post.post_status = <?php echo json_encode($post->post_status); ?>;
</script>
