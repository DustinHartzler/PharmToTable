<div class="cloud-template-item<#=item.locked ? ' locked' : ''#>">
	<div class="cloud-item <#=item.locked ? '' : ' click'#><#=selected ? ' active' : ''#>" data-id="<#= item.id || 0 #>" data-fn="dom_select" data-name="<#= item.name #>">
		<div class="cb-template-wrapper">
			<# if ( item.locked ) { #>
			<span class="locked-star">
			<?php tcb_icon( 'star' ) ?>
		</span>
			<div class="template-locked-text">
				<p><?php echo __( 'Available in the full version of Thrive Architect', 'thrive-cb' ); ?></p>
				<a href="https://thrivethemes.com/architect/upgrade/?utm_campaign=tarc-upgrade&utm_medium=tarc-lite&utm_source=ttb-ui&utm_content=tarc-element&utm_term=ttb-customer" title="Thrive Architect" target="_blank"><?php echo __( 'Upgrade', 'thrive-cb' ); ?></a>
			</div>
			<# } #>
			<div class="cb-template-thumbnail lazy-loading">
				<img class="tve-lazy-img" src='<?php echo tve_editor_css(); ?>/images/loading-spinner.gif' data-src="<#= item.thumb #>" data-ratio="<#= parseFloat(parseInt(item.thumb_size ? item.thumb_size.h : 150) / parseInt(item.thumb_size ? item.thumb_size.w : 150)).toFixed(3) #>"/>
			</div>
		</div>
	</div>
</div>