( function ( $ ) {

	var views = {};
	/**
	 * remove tvd-invalid class for all inputs in the view's root element
	 *
	 * @returns {Backbone.View}
	 */
	Backbone.View.prototype.tvd_clear_errors = function () {
		this.$( '.tvd-invalid' ).removeClass( 'tvd-invalid' );
		this.$( 'select' ).trigger( 'tvdclear' );
		return this;
	};

	/**
	 *
	 * @param {Backbone.Model|object} [model] backbone model or error object with 'field' and 'message' properties
	 *
	 * @returns {Backbone.View|undefined}
	 */
	Backbone.View.prototype.tvd_show_errors = function ( model ) {
		model = model || this.model;

		if ( ! model ) {
			return;
		}

		var err = model instanceof Backbone.Model ? model.validationError : model,
			self = this,
			$all = $();

		function show_error( error_item ) {
			if ( typeof error_item === 'string' ) {
				return TVE_Dash.err( error_item );
			}

			$all = $all.add( self.$( '[data-field=' + error_item.field + ']' ).addClass( 'tvd-invalid' ).each( function () {
				var $this = $( this );
				if ( $this.is( 'select' ) ) {
					$this.trigger( 'tvderror', error_item.message );
				} else {
					$this.next( 'label' ).attr( 'data-error', error_item.message )
				}
			} ) );
		}

		if ( $.isArray( err ) ) {
			_.each( err, function ( item ) {
				show_error( item );
			} );
		} else {
			show_error( err );
		}
//			$all.not( '.tvd-no-focus' ).first().focus();
		/* if the first error message is not visible, scroll the contents to include it in the viewport. At the moment, this is only implemented for modals */
		this.scroll_first_error( $all.first() );

		return this;
	};

	/**
	 * scroll the contents so that the first errored input is visible
	 * currently this is only implemented for modals
	 *
	 * @param {Object} $input first input element that has the error
	 *
	 * @returns {Backbone.View}
	 */
	Backbone.View.prototype.scroll_first_error = function ( $input ) {
		if ( ! ( this instanceof TVE_Dash.views.Modal ) || ! $input.length ) {
			return this;
		}
		var input_top = $input.offset().top,
			content_top = this.$_content.offset().top,
			scroll_top = this.$_content.scrollTop(),
			content_height = this.$_content.outerHeight();
		if ( input_top >= content_top && input_top < content_height + content_top - 50 ) {
			return this;
		}

		this.$_content.animate( {
			'scrollTop': scroll_top + input_top - content_top - 40 // 40px difference
		}, 200, 'swing' );
	};


	/**
	 * Base View
	 */
	views.Base = Backbone.View.extend( {
		/**
		 * Always try to return this !!!
		 *
		 * @returns {views.Base}
		 */
		render: function () {
			return this;
		},
		/**
		 *
		 * Instantiate and open a new modal which has the view constructor assigned and send params further along
		 *
		 * @param ViewConstructor View constructor
		 * @param params
		 */
		modal: function ( ViewConstructor, params ) {
			return TVE_Dash.modal( ViewConstructor, params );
		},
		bind_zclip: function () {
			/**
			 * Keep the old ZClip working
			 */
			TVE_Dash.bindZClip( this.$( 'a.tvd-copy-to-clipboard' ) );

			var $element = this.$( '.tva-sendowl-search' );

			function bind_it() {
				$element.each( function () {
					var $elem = $( this ),
						$input = $elem.prev().on( 'click', function ( e ) {
							this.select();
							e.preventDefault();
							e.stopPropagation();
						} ),
						_default_btn_color_class = $elem.attr( 'data-tvd-btn-color-class' ) || 'tva-copied';

					try {
						$elem.zclip( {
							path: TVE_Dash_Const.dash_url + '/js/util/jquery.zclip.1.1.1/ZeroClipboard.swf',
							copy: function () {
								return jQuery( this ).prev().val();
							},
							afterCopy: function () {
								var $link = jQuery( this );
								$input.select();
								$link.removeClass( _default_btn_color_class ).addClass( 'tva-copied' );
								setTimeout( function () {
									$link.removeClass( 'tva-copied' );
								}, 2000 );
							}
						} );
					} catch ( e ) {
						console.error && console.error( 'Error embedding zclip - most likely another plugin is messing this up' ) && console.error( e );
					}
				} );
			}

			setTimeout( bind_it, 200 );
		}
	} );

	/**
	 * Header View
	 */
	views.Header = views.Base.extend( {
		template: TVE_Dash.tpl( 'header' ),
		render: function () {
			this.$el.html( this.template( {} ) );
			return this;
		}
	} );

	views.Menu = views.Base.extend( {
		template: TVE_Dash.tpl( 'menu' ),
		render: function () {
			this.$el.html( this.template( {page: Backbone.history.getFragment()} ) );

			return this;
		}
	} );
	/**
	 * breadcrumbs view - renders breadcrumb links
	 */
	views.Breadcrumbs = views.Base.extend( {
		el: $( '#tvd-tss-breadcrumbs-wrapper' )[ 0 ],
		template: TVE_Dash.tpl( 'breadcrumbs' ),
		/**
		 * setup collection listeners
		 */
		initialize: function () {
			this.$title = $( 'head > title' );
			this.original_title = this.$title.html();
			this.listenTo( this.collection, 'change', this.render );
			this.listenTo( this.collection, 'add', this.render );
		},
		/**
		 * render the html
		 */
		render: function () {
			this.$el.empty().html( this.template( {links: this.collection} ) );
			return this;
		}
	} );


	/**
	 * Dashboard view (Global Fields for now)
	 */
	views.Dashboard = views.Base.extend( {
		template: TVE_Dash.tpl( 'dashboard' ),
		render: function () {
			this.$el.html( this.template( {} ) );

			return this;
		}
	} );

	views.GlobalFields = views.Base.extend( {
		template: TVE_Dash.tpl( 'global_fields' ),
		events: {
			'click .tvd-tss-add-new-field': 'addField',
		},
		initialize: function () {
			this.listenTo( this.collection, 'add', this.render );
			this.listenTo( this.collection, 'remove', this.render );
		},
		render: function () {
			this.$el.html( this.template( {} ) );

			this.renderGroups();

			return this;
		},
		renderGroups: function () {
			this.collection.each( this.renderGroup, this );
			TVE_Dash.materialize( this.$el );
		},
		renderGroup: function ( group ) {
			var view = new views.Group( {
				model: group,
				collection: this.collection
			} ), $groupElement = view.render().$el;

			this.$( '.tvd-tss-groups-wrapper' ).append( $groupElement );
		},
		addField: function () {
			this.modal( SmartSite.modals.EditFieldModal, {
				collection: this.collection,
				group: new SmartSite.models.Group(),
				model: new SmartSite.models.Field(),
				'max-width': '60%',
				width: '800px'
			} );
		}
	} );

	/**
	 * View for the side menu
	 */
	views.SideMenu = views.Base.extend( {
		template: TVE_Dash.tpl( 'side-menu' ),
		render: function () {
			this.$el.html( this.template( {page: Backbone.history.getFragment()} ) );

			return this;
		}
	} );
	/**
	 * Group view
	 */
	views.Group = views.Base.extend( {
		template: TVE_Dash.tpl( 'global-fields/group' ),
		tagName: 'li',
		className: 'tvd-tss-group-item tvd-active',
		events: {
			'click .tvd-tss-add-field': 'addField',
			'click .tvd-tss-delete-group': 'deleteGroup',
			'click .tvd-tss-edit-group': 'editGroup'
		},

		initialize: function () {
			if ( Number( this.model.get( 'is_default' ) ) ) {
				this.$el.addClass( 'tvd-tss-group-item-default ' );
			}
			this.listenTo( this.model.get( 'fields' ), 'add', this.render );
			this.listenTo( this.model.get( 'fields' ), 'remove', this.render );
			this.model.on( 'render_groups', this.render, this );
		},
		render: function () {
			this.$el.html( this.template( {model: this.model} ) );

			this.renderFields();

			return this;
		},
		/**
		 * Render each group fields
		 */
		renderFields: function () {
			if ( this.model.get( 'fields' ).length === 0 ) {
				this.$( 'ul.tvd-tss-fields-wrapper' ).html( '<p class="tvd-tss-placeholder">' + SmartSite.t.NoFields + '</p>' );
			} else {
				this.model.get( 'fields' ).each( this.renderField, this );
			}

		},
		/**
		 * Render one field
		 *
		 * @param field
		 */
		renderField: function ( field ) {
			var view = new views.Field( {
				model: field,
				collection: this.model.get( 'fields' ),
				group: this.model
			} );

			this.$( 'ul.tvd-tss-fields-wrapper' ).append( view.render().$el )
		},
		/**
		 * Open modal for adding a field
		 */
		addField: function () {
			var model = new SmartSite.models.Field( {group_id: this.model.get( 'id' )} );

			this.modal( SmartSite.modals.EditFieldModal, {
				model: model,
				collection: this.collection,
				group: this.model,
				'max-width': '60%',
				width: '800px'
			} );
		},
		/**
		 * Open delete group modal
		 */
		deleteGroup: function () {
			this.modal( SmartSite.modals.DeleteModal, {
				model: this.model,
				collection: this.collection,
				'max-width': '60%',
				width: '800px'
			} );
		},
		/**
		 * Open edit group modal
		 */
		editGroup: function () {
			this.modal( SmartSite.modals.EditGroupModal, {
				model: this.model,
				collection: this.collection,
				'max-width': '60%',
				width: '800px'
			} );
		}
	} );

	/**
	 * Start adding fields in group view
	 */
	views.AddGroupField = views.Base.extend( {
		template: TVE_Dash.tpl( 'global-fields/add-group-field' ),
		className: 'tvd-row',
		render: function () {
			this.$el.html( this.template( {} ) );
			return this;
		}
	} );

	/**
	 * Add more button view
	 */
	views.AddMore = views.Base.extend( {
		template: TVE_Dash.tpl( 'global-fields/add-more' ),
		render: function () {
			this.$el.html( this.template( {} ) );
			return this;
		}
	} );

	/**
	 * Field View
	 */
	views.Field = views.Base.extend( {
		template: TVE_Dash.tpl( 'global-fields/field' ),
		tagName: 'li',
		events: {
			'click .tvd-tss-delete-field': 'deleteField',
			'click .tvd-tss-edit-field': 'editField'
		},
		initialize: function ( options ) {
			this.group = options.group;
			this.model.on( 'render_field', this.render, this );
		},
		render: function () {
			this.$el.closest('.tvd-tss-fields-content').find('.tve-tss-just-added').removeClass('tve-tss-just-added');
			this.$el.html( this.template( {model: this.model} ) );

			return this;
		},
		deleteField: function () {
			this.modal( SmartSite.modals.DeleteModal, {
				model: this.model,
				collection: this.collection,
				'max-width': '60%',
				width: '800px'
			} );
		},
		editField: function () {
			this.modal( SmartSite.modals.EditFieldModal, {
				model: this.model,
				collection: SmartSite.groups,
				group: this.group,
				view: this,
				'max-width': '60%',
				width: '800px',
				edit: true
			} );
		}
	} );

	/**
	 * Text Field options
	 */
	views.FieldTextOptions = views.Base.extend( {
		template: TVE_Dash.tpl( 'global-fields/text-options' ),
		render: function () {
			this.$el.html( this.template( {model: this.model} ) );
			if ( ! this.model.get( 'name' ) ) {
				this.$( 'input' ).val( '' );
			}
			return this;
		}
	} );

	/**
	 * Address Field options
	 */
	views.FieldAddressOptions = views.FieldTextOptions.extend( {
		template: TVE_Dash.tpl( 'global-fields/address-options' )
	} );

	/**
	 * Phone Field options
	 */
	views.FieldPhoneOptions = views.FieldTextOptions.extend( {
		template: TVE_Dash.tpl( 'global-fields/phone-options' )
	} );

	/**
	 * Email Field options
	 */
	views.FieldEmailOptions = views.FieldTextOptions.extend( {
		template: TVE_Dash.tpl( 'global-fields/email-options' )
	} );

	/**
	 * Link Field options
	 */
	views.FieldLinkOptions = views.FieldTextOptions.extend( {
		template: TVE_Dash.tpl( 'global-fields/link-options' )
	} );


	/**
	 * Group item in modal dropdown
	 */
	views.GroupItem = views.Base.extend( {
		template: TVE_Dash.tpl( 'global-fields/modal-group-item' ),
		className: 'tvd-global-fields-group',
		render: function () {
			this.$el.html( this.template( {model: this.model} ) );

			return this;
		}
	} );

	/**
	 * Field type item in modal dropdown
	 */
	views.FieldTypeItem = views.Base.extend( {
		template: TVE_Dash.tpl( 'global-fields/modal-type-item' ),
		className: 'tvd-global-fields-type',
		render: function () {
			this.$el.html( this.template( {model: this.model} ) );

			return this;
		}
	} );


	/**
	 * Location Field options
	 */
	views.FieldLocationOptions = views.FieldTextOptions.extend( {
		template: TVE_Dash.tpl( 'global-fields/location-options' ),
		events: {
			'change .input-change': 'setInputData'
		},
		render: function () {
			this.$el.html( this.template( {model: this.model} ) );
			this.setInputData();
			return this;
		},
		setInputData: function () {
			var url = 'https://maps.google.com/maps?q=' + encodeURI( this.model.get( 'data' ).location ? this.model.get( 'data' ).location : 'New York' ) + '&t=m&z=10&output=embed&iwloc=near';
			this.$( '#tvd-tss-google-map' ).html( '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' + url + '"></iframe>' );
		}
	} );

	module.exports = views;

} )( jQuery );