<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 6/25/2017
 * Time: 2:28 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>

<div id="tve-quiz-component" class="tve-component" data-view="quiz">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Quiz Options', Thrive_Quiz_Builder::T ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control" data-view="change_quiz"></div>
		<div class="tve-control" data-view="quiz_scroll"></div>
	</div>
</div>
