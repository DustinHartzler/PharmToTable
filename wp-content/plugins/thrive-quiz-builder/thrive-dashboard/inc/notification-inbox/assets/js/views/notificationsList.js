/**
 * Created by Dan Brinzaru on 4/9/2019.
 */

var baseView = require( './base' );

var notification_view = require( './notification' );
var notification_detail = require( './notificationDetail' );
var notificationModel = require( './../models/notification' );

( function ( $ ) {

	var headerView = baseView.extend( {
		events: {
			'click .tvd-ni-mark-read': 'readAll'
		},
		render: function () {
			this.$el.html(
				TVE_Dash.tpl( 'notification-header' )( {} )
			);

			if ( this.collection.where( {read: 'unread'} ).length === 0 ) {
				this.$( '.tvd-ni-mark-read' ).addClass( 'tvd-ni-disabled' );
			}

			if ( this.collection.length == 0 ) {
				this.$( '.tvd-ni-mark-read' ).empty();
			}

			return this;
		},
		/**
		 * Bulk read
		 */
		readAll: function ( event ) {

			this.collection.each( function ( model ) {
				model.set( {read: "read"}, {silent: true} );
			} );

			this.saveData();

			this.collection.trigger( 'render' );
			this.render();

			event.stopPropagation();
		},
		saveData: function () {
			var self = this;
			$.ajax( {
				type: 'POST',
				url: TD_Inbox.ajaxurl,
				data: {
					'action': 'thrv_bulkread',
					'_nonce': TD_Inbox.admin_nonce,
				}
			} ).success( function ( response ) {
				var response = JSON.parse( response );
				if ( typeof response.total_unread !== 'undefined' ) {
					TD_Inbox.total_unread = response.total_unread;
					self.collection.trigger( 'bulk_update' );
				}
			} );
		}
	} );

	module.exports = baseView.extend( {

		headerView: null,

		events: {
			'click .td-inbox-item': 'renderNotification',
			'click .ni-go-back': 'render',
			'click .tvd-ni-close': 'close',
			'click .tvd-ni-load-inner': 'loadMore'
		},
		initialize: function () {
			var self = this;
			this.collection.on( 'render', function () {
				self.render();
			} );
		},
		render: function ( event ) {
			this.$( '.tvd-notification-wrapper' ).html(
				'<div class="tvd-notifications-list"></div><div class="tvd-load-more"></div>'
			);

			if ( this.headerView ) {
				this.headerView.undelegateEvents();
			}
			this.headerView = new headerView( {
				el: this.$( '.tvd-notification-header' ),
				collection: this.collection
			} ).render();

			this.renderList();

			if ( this.collection.length === 0 ) {
				this.$( '.tvd-load-more' ).append( '<span class="tvd-ni-no-data">' + TD_Inbox.t.no_data + '</span>' );
			} else {
				if ( this.collection.length < parseInt( TD_Inbox.total ) ) {
					this.$( '.tvd-load-more' ).html( '<span class="tvd-ni-load"><span class="tvd-ni-load-inner">' + TD_Inbox.t.more_10 + '</span></span>' );
				} else {
					this.$( '.tvd-load-more' ).html( '<span class="tvd-ni-no-more-data">' + TD_Inbox.t.no_more_data + '</span>' );
				}
			}

			if ( event && event.target ) {
				event.stopPropagation();
			}

			return this;
		},
		renderList: function () {
			this.$( '.tvd-notifications-list' ).empty();

			this.collection.each( function ( model ) {
				new notification_view( {
						model: model,
						el: this.$( '.tvd-notifications-list' )
					}
				).render();
			}, this );
		},
		renderNotification: function ( e ) {
			var id = e.currentTarget.dataset.id,
				model = this.collection.findWhere( {'id': id} );

			new notification_detail( {
				model: model,
				el: this.$( '.tvd-notification-wrapper' )
			} ).render();

			this.$( '.tvd-notification-header' ).empty();

			e.stopPropagation();
		},
		close: function () {
			this.$el.toggle();
		},
		loadMore: function ( e ) {

			this.doAjaxCall( 'thrv_load_more', {
				limit: TD_Inbox.limit,
				offset: TD_Inbox.offset
			} );

			if ( this.collection.length >= parseInt( TD_Inbox.total ) ) {
				this.$( '.tvd-load-more' ).empty();
				this.$( '.tvd-load-more' ).html( '<span class="tvd-ni-no-more-data">' + TD_Inbox.t.no_more_data + '</span>' );
			}

			e.stopPropagation();
		},
		doAjaxCall: function ( action, data ) {

			var self = this;
			var $loadMoreText = this.$( '.tvd-ni-load-inner' );
			var loadMoreText = $loadMoreText.text();

			$loadMoreText.text( 'Loading...' );

			$.ajax( {
				type: 'POST',
				url: TD_Inbox.ajaxurl,
				data: _.extend( {
					'action': action,
					'_nonce': TD_Inbox.admin_nonce
				}, data )
			} ).success( function ( response ) {
				TD_Inbox.offset = parseInt( TD_Inbox.limit ) + parseInt( TD_Inbox.offset );

				_.each( response, function ( item ) {
					self.collection.add( new notificationModel( item ) );
				} );

				if ( self.collection.length === parseInt( TD_Inbox.total ) ) {
					self.$( '.tvd-load-more' ).html( '<span class="tvd-ni-no-more-data">' + TD_Inbox.t.no_more_data + '</span>' );
				}
				self.renderList();
			} ).always( function () {
				if ( $loadMoreText.length ) {
					$loadMoreText.text( loadMoreText );
				}
			} );
		}
	} );
} )( jQuery );
