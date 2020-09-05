<?php
/**
 * @see \KS_Giveaways_Admin::add_meta_boxes()
 */
/** @var array $valid_services */

/** @var KS_Giveaways_Admin $admin */
$admin = KS_Giveaways_Admin::get_instance();
?>
<table class="form-table">
    <?php if ($valid_services['ks_giveaways_sendfox_valid']): ?>
        <tr valign="top">
            <th scope="row">
                <label>SendFox</label>
            </th>
            <td>
                <?php $admin->input_sendfox_tag_id(true, get_post_meta(get_post()->ID, "_".KS_GIVEAWAYS_OPTION_SENDFOX_TAG_ID, true)); ?>
            </td>
        </tr>
    <?php endif; ?>
    <?php if ($valid_services['ks_giveaways_aweber_valid']): ?>
    <tr valign="top">
        <th scope="row">
            <label>Aweber</label>
        </th>
        <td>
            <?php $admin->input_aweber_list_id(true, get_post_meta(get_post()->ID, "_".KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID, true)); ?>
        </td>
    </tr>
    <?php endif; ?>
    <?php if ($valid_services['ks_giveaways_mailchimp_valid']): ?>
        <tr valign="top">
            <th scope="row">
                <label>MailChimp</label>
            </th>
            <td>
                <?php $admin->input_mailchimp_list_id(true, get_post_meta(get_post()->ID, "_".KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID, true)); ?>
            </td>
        </tr>
    <?php endif; ?>
    <?php if ($valid_services['ks_giveaways_getresponse_valid']): ?>
        <tr valign="top">
            <th scope="row">
                <label>GetResponse</label>
            </th>
            <td>
                <?php $admin->input_getresponse_campaign_id(true, get_post_meta(get_post()->ID, "_".KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID, true)); ?>
            </td>
        </tr>
    <?php endif; ?>
    <?php if ($valid_services['ks_giveaways_campaignmonitor_valid']): ?>
        <tr valign="top">
            <th scope="row">
                <label>CampaignMonitor</label>
            </th>
            <td>
                <?php $admin->input_campaignmonitor_list_id(true, get_post_meta(get_post()->ID, "_".KS_GIVEAWAYS_OPTION_CAMPAIGNMONITOR_LIST_ID, true)); ?>
            </td>
        </tr>
    <?php endif; ?>
    <?php if ($valid_services['ks_giveaways_convertkit_valid']): ?>
        <tr valign="top">
            <th scope="row">
                <label>ConvertKit</label>
            </th>
            <td>
                <?php $admin->input_convertkit_form_id(true, get_post_meta(get_post()->ID, "_".KS_GIVEAWAYS_OPTION_CONVERTKIT_FORM_ID, true)); ?>
            </td>
        </tr>
    <?php endif; ?>
    <?php if ($valid_services['ks_giveaways_activecampaign_valid']): ?>
        <tr valign="top">
            <th scope="row">
                <label>ActiveCampaign</label>
            </th>
            <td>
                <?php $admin->input_activecampaign_list_id(true, get_post_meta(get_post()->ID, "_".KS_GIVEAWAYS_OPTION_ACTIVECAMPAIGN_LIST_ID, true)); ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr valign="top">
        <th scope="row">
            <label>Zapier</label>
        </th>
        <td>
            <?php $admin->input_zapier_trigger_url(true, get_post_meta(get_post()->ID, "_".KS_GIVEAWAYS_OPTION_ZAPIER_TRIGGER_URL, true)); ?>
        </td>
    </tr>
</table>