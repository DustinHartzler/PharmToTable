module.exports = {
	/**
	 * In the login editor, the body is the wrapper
	 * @param $editor
	 * @return {*}
	 */
	'editor_wrapper': $editor => TVE.inner_$( 'body' ),

	/**
	 * Include our custom components
	 * @param TVE
	 * @returns {*}
	 */
	'tcb.includes': TVE => {
		TVE.Views.Components = {...TVE.Views.Components, ...require( './components/_includes' )}

		return TVE;
	},

	/**
	 * We don't need to save the content
	 * @param data
	 * @return {*}
	 */
	'tcb_save_post_data_after': data => {
		data.tve_content = '';
		data.stripped_content = '';

		return data;
	},

	/**
	 * Extend the right sidebar view
	 *
	 * @returns {*}
	 */
	'tcb.extend-sidebar-right': () => {
		const resetDesignModal = require( './modals/reset-design' );

		return {
			loginCloudTemplates: () => {
				TVE.modal_open( 'cloud-templates', {
					element: TVE.inner.$body
				} );
			},
			resetDesign: () => {
				new resetDesignModal( {
					el: TVE.modal.get_element( 'reset-login-design-modal' )
				} ).open();
			}
		}
	},
	/**
	 * Special case for hover state on the form link
	 * @param headCss
	 * @return {*}
	 */
	'hover_head_css_selector': headCss => {
		/* we rewrite the head css selector for hover state because we have two selectors separated by comma. */
		if ( headCss.current_element.id === 'nav' || headCss.current_element.id === 'backtoblog' ) {
			const hasSuffix = headCss.selector.includes( ' a' ),
				prepareSelector = ( fullSelector, after, suffix = '' ) => fullSelector.split( ', ' )
				                                                                      .map( selector => `${TVE.CONST.global_css_prefix} ${selector}${suffix}${after}` )
				                                                                      .join( ', ' );

			headCss.selector = prepareSelector( headCss.current_element.dataset.selector, TVE.state_manager.get_pseudo(), hasSuffix ? ' a' : '' )
			headCss.state_preview_selector = prepareSelector( headCss.current_element.dataset.selector, TVE.state_manager.css_class(), hasSuffix ? ' a' : '' )
		}

		return headCss;
	},
	/**
	 * Force important when setting the border on body
	 * @param forceImportant
	 * @param selector
	 * @param rules
	 * @return {boolean}
	 */
	'tcb.force_important': ( forceImportant, selector, rules ) => {
		if ( selector === 'body' && Object.keys( rules ).some( ( rule, index ) => rule.includes( 'border' ) ) ) {
			forceImportant = true;
		}

		return forceImportant;
	}
}
