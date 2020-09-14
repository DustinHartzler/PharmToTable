/**
 * External dependencies
 */
import { Button } from '@wordpress/components';
import classnames from 'classnames';

export default ( props ) => {
	return (
		<Button
			{ ...props }
			// Add is-button class that woocommerce css is expecting
			className={ classnames(
				'automatewoo-button is-button',
				props.className
			) }
		>
			{ props.children }
		</Button>
	);
};
