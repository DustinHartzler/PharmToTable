/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Button } from '../base/components';

export default function NextButton( props ) {
	return (
		<Button
			{ ...props }
			isPrimary
			isLarge
			className="automatewoo-workflow-runner-next-button"
		>
			{ props.children || __( 'Next', 'automatewoo' ) }
		</Button>
	);
}
