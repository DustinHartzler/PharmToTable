<div class="col col-xs-12">
	<div class="row tcb-revision-row mt-5 mb-5 ml-5 mr-5 pt-10 pb-5">
		<div class="col col-xs-2">
			<#= item.author.avatar #>
		</div>
		<div class="col col-xs-6">
			<?php echo __( 'Revision made by ', 'thrive-cb' ); ?>
			<strong>
				<#= item.author.name #>
			</strong>
			<br>
			<span class="tcb-revision-date-text"><#= item.dateShort #>&nbsp;(<#= item.timeAgo #>)</span>
		</div>
		<div class="col col-xs-4">
			<a class="click tcb-modal-lnk"
			   data-fn="clicked"
			   href="<#= item.restoreUrl #>"><?php echo __( 'Restore Revision', 'thrive-cb' ) ?></a>
		</div>
	</div>
</div>
