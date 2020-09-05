<?php if(!isset($status)): ?>
	<div style="font-size: 85%">
		<p class="description" id="ks-giveaways-test-services-subscription-status">Please enter an email address to test your service integration configuration works correctly.</p>
	</div>

<?php elseif($status === 3): ?>
	<div style="font-size: 85%">
		<p class="description" id="ks-giveaways-test-services-subscription-status" style="color:red">No services have been set up!</p>
		<?php if (isset($errors) && !empty($errors)): ?>
			<ul>
				<li><?php echo implode('</li><li>', $errors) ?></li>
			</ul>
		<?php endif; ?>
	</div>


<?php elseif($status): ?>
	<div style="font-size: 85%">
		<p class="description" id="ks-giveaways-test-services-subscription-status" style="color:green">Service integration has been successfully tested.</p>
		<?php if (isset($errors) && !empty($errors)): ?>
			<ul>
				<li><?php echo implode('</li><li>', $errors) ?></li>
			</ul>
		<?php endif; ?>
	</div>

<?php else: ?>
	<div style="font-size: 85%">
		<p class="description" id="ks-giveaways-test-services-subscription-status" style="color:red">Service integration has failed.</p>
		<?php if (isset($errors) && !empty($errors)): ?>
			<ul>
				<li><?php echo implode('</li><li>', $errors) ?></li>
			</ul>
		<?php endif; ?>
	</div>

<?php endif; ?>