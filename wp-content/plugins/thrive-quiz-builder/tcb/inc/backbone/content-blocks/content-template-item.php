<div class="ct-item click" data-fn="selectTemplate" data-id="<#= item.id #>"  data-name="<#= item.label #>" data-category="<#= item.id_category #>">
	<div class="template-name-section">
		<div class="symbol-name"><#= item.label #></div>
		<span><?php tcb_icon( 'check-regular' ); ?></span>
	</div>
	<div class="symbol-wrapper">
		<div class="cb-template-thumbnail lazy-loading">
			<img class="tve-lazy-img" src="<?php echo tve_editor_css(); ?>/images/loading-spinner.gif" data-src="<#= item.thumb.url #>" data-ratio="<#= parseFloat(parseInt(item.thumb.h) / parseInt(item.thumb.w)).toFixed(3) #>"/>
		</div>
	</div>
</div>