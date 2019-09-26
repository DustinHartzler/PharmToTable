const { __ } = wp.i18n;
const {
    PanelBody,
    SelectControl,
} = wp.components;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;

registerBlockType( 'wp-ultimate-recipe/recipe', {
    title: __( 'WPURP Recipe' ),
    description: __( 'Display a WP Ultimate Recipe box.' ),
    icon: 'media-document',
    keywords: [ 'wpurp' ],
    category: 'wp-ultimate-recipe',
    supports: {
		html: false,
    }, 
    attributes: {		
		id: {
			type: 'string',
			default: 'random',
		},
		template: {
			type: 'string',
			default: 'default',
		},
	},
    transforms: {
        from: [
            {
                type: 'shortcode',
                tag: 'ultimate-recipe',
                attributes: {
                    id: {
                        type: 'string',
                        shortcode: ( { named: { id = '' } } ) => {
                            return id.replace( 'id', '' );
                        },
                    },
                    template: {
                        type: 'string',
                        shortcode: ( { named: { template = '' } } ) => {
                            return template.replace( 'template', '' );
                        },
                    },
                },
            },
        ]
    },
    edit: (props) => {
        const { attributes, setAttributes, isSelected, className } = props;

        const IdOptions = [
            {
                value: 'random',
                label: __( 'Random' ),
            },
            {
                value: 'latest',
                label: __( 'Latest' ),
            }
        ].concat(wpurp_blocks.shortcodes.recipes_by_date);

        return (
            <div className={ className } style={{
                border: '1px dashed #444',
                padding: '10px',
            }}>
                <InspectorControls>
                    <PanelBody title={ __( 'Recipe Details' ) }>
                        <SelectControl
                            label={ __( 'Recipe' ) }
                            value={ attributes.id }
                            options={ IdOptions }
                            onChange={ (id) => setAttributes({
                                id,
                            }) }
                        />
                        <SelectControl
                            label={ __( 'Template' ) }
                            value={ attributes.template }
                            options={ wpurp_blocks.shortcodes.templates }
                            onChange={ (template) => setAttributes({
                                template,
                            }) }
                        />
                    </PanelBody>
                </InspectorControls>
                <strong>Placeholder for WP Ultimate Recipe recipe ({ attributes.id })</strong>
            </div>
        )
    },
    save: (props) => {
        const id = props.attributes.id;

        if ( !id ) {
            return null;
        } else {
            return `[ultimate-recipe id="${props.attributes.id}" template="${props.attributes.template}"]`;
        }
    },
} );