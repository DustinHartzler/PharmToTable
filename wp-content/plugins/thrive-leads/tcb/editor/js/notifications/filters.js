module.exports = {
	/**
	 * Include the custom components
	 * @param TVE
	 * @returns {*}
	 */
	'tcb.includes': TVE => {
		TVE.Views.Components = {...TVE.Views.Components, ...( require( './components/_includes' ) )};

		return TVE;
	},

	/**
	 * Remove classes and attributes that are not necessary
	 * @param $content
	 * @returns {*}
	 */
	'tcb_filter_html_before_save': ( $content ) => {
		if ( $content.find( '.notifications-content-wrapper' ).attr( 'data-timer' ) < 0 ) {
			$content.find( '.notifications-content-wrapper' ).attr( 'data-timer', 3000 );
		}
		$content.find( '.notification-edit-mode' ).removeClass( 'notification-edit-mode' );
		$content.find( '.tve_no_icons' ).each( ( index, element ) => {
			jQuery( element ).removeClass( 'tve_no_drag tve_no_icons' );
		} );

		return $content;
	},

	/**
	 * Update the selected notification template
	 * @param data
	 * @returns {*}
	 */
	'tcb_save_post_data_after': ( data ) => {
		jQuery.ajax( {
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'notification_update_template',
				post_id: data.post_id,
			}
		} );
		return data;
	},

	/**
	 * Do not allow elements to be dropped elsewhere than inside the Notification element
	 * @param elements
	 * @returns {*}
	 */
	'only_inner_drop': ( elements ) => {

		elements += ',.notifications-content';

		return elements;
	},

	/* Do not allow elements to be inserted outside the notification element */
	'tve.drag.position.insert': ( dir, $new_element, $target ) => {
		if ( $target.is( '.notifications-content' ) ) {
			dir = 'mid';
		}

		return dir;
	},

	/* Insert new elements inside the corresponding notification */
	'tve.insert.near.target': ( $target ) => {
		if ( $target.is( '.notifications-content-wrapper' ) ) {
			$target = $target.find( `.notifications-content.notification-${$target.attr( 'data-state' )}` );
		}

		return $target;
	},

	/* Allow custom refocus after exiting the Edit Mode */
	'tve.edit.mode.refocus': () => {
		return false;
	},

	/* Add prefix in order to successfully override the default style */
	'tcb_head_css_prefix': ( prefix, element ) => {
		if ( ! element.is( '.notifications-content,.thrv-notification_message,.notifications-content-wrapper' ) ) {
			const state = TVE.FLAGS.notification_state || TVE.inner_$( '.notifications-content-wrapper' ).attr( 'data-state' );
			prefix = `.notification-${state} `;
		}

		return prefix;
	},
};
