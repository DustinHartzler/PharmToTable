<?php

defined( 'ABSPATH' ) || exit;

/**
 * Can be loaded by ajax
 *
 * @var AutomateWoo\Workflow $workflow
 * @var AutomateWoo\Trigger  $trigger
 * @var bool                 $fill_fields (optional)
 */


// default to false
if ( ! isset( $fill_fields ) ) {
	$fill_fields = false;
}

if ( ! $trigger ) {
	return;
}


// if we're populating field values, get the trigger object from the workflow
// Otherwise just use the unattached trigger object

if ( $fill_fields ) {
	$trigger = $workflow->get_trigger();
}

$fields = $trigger->get_fields();

?>

	<?php foreach ( $fields as $field ) : ?>

		<?php
		if ( $fill_fields ) {
			$value = $workflow->get_trigger_option( $field->get_name() );
		} else {
			$value = null;
		}
		?>

		<tr class="automatewoo-table__row aw-trigger-option"
			data-name="name"
			data-type="<?php echo esc_attr( $field->get_type() ); ?>"
			data-required="<?php echo (int) $field->get_required(); ?> ">

			<td class="automatewoo-table__col automatewoo-table__col--label">

				<?php echo wp_kses_post( $field->get_title() ); ?>
				<?php if ( $field->get_required() ) : ?>
					<span class="required">*</span>
				<?php endif; ?>

				<?php AutomateWoo\Admin::help_tip( $field->get_description() ); ?>

			</td>

			<td class="automatewoo-table__col automatewoo-table__col--field">
				<?php $field->render( $value ); ?>
			</td>
		</tr>

	<?php endforeach; ?>
