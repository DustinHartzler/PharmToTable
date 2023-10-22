/**
 * WordPress dependencies
 */
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const TEMPLATE = [
	[
		'core/columns',
		{
			className: 'wp-block-sensei-pro-join-group__columns',
			style: {
				spacing: {
					margin: {
						bottom: '0',
					},
				},
			},
		},
		[
			[ 'core/column', {}, [] ],
			[
				'core/column',
				{ verticalAlignment: 'bottom' },
				[
					[
						'core/buttons',
						{ layout: { type: 'flex', justifyContent: 'right' } },
						[
							[
								'core/button',
								{ text: __( 'Join Group', 'sensei-pro' ) },
							],
						],
					],
				],
			],
		],
	],
];

const Edit = () => {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InnerBlocks template={ TEMPLATE } />
		</div>
	);
};

export default Edit;
