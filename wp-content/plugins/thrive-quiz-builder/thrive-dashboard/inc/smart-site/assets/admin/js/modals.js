( function ( $ ) {

	var modals = {};

	/**
	 * Add/Edit Field Modal
	 */
	modals.EditFieldModal = TVE_Dash.views.Modal.extend( {
		template: TVE_Dash.tpl( 'global-fields/modals/field' ),
		className: 'tvd-modal tss-modal',
		events: {
			'click .tvd-global-field-group-list': 'selectGroup',
			'click #tvd-tss-group-name': 'showDropdown',
			'click .tvd-add-new-group': 'addNewGroup',
			'click #tvd-save-new-group': 'saveNewGroup',
			'keypress #tvd-add-group': 'inputGroupName',
			'click .tvd-field-type-option': 'selectType',
			'click #tvd-tss-field-type': 'showTypeDropdown',
			'input .input-change': 'setInputData',
			'click .tvd-tss-save-field': 'save'
		},

		afterRender: function () {
			this.renderGroupList();
			this.renderFieldTypeList();
			if ( this.model.get( 'type' ) ) {
				this.renderByType();
			}

			var self = this;
			setTimeout( function () {
				self.$( '#tvd-tss-save-field' ).focus();
			} );
			$( 'body' ).on( 'click', function ( e ) {
				var $dropdowns = self.$( '.tvd-complex-dropdown-content' );

				$dropdowns.each( function () {
					var $dropdown = $( this ),
						$trigger = $( this ).siblings( '.tvd-input-field' ).find( '.tvd-complex-dropdown-trigger' );

					if ( $trigger.length === 0 ) {
						return true;
					}

					if ( ! $dropdown.is( e.target ) && $dropdown.has( e.target ).length === 0 && ! $trigger.is( e.target ) ) {
						$dropdown.addClass( 'tvd-hide' );
					}
				} );

			} );
		},
		/**
		 * Render field type dropdown
		 */
		renderFieldTypeList: function () {
			SmartSite.data.fieldTypes.forEach( this.renderFieldType, this );
		},
		renderFieldType: function ( type ) {
			var view = new SmartSite.views.FieldTypeItem( {
				model: new SmartSite.models.FieldType( type )
			} );

			this.$( '.tvd-global-field-type-list' ).append( view.render().$el );
		},
		/**
		 * Render group dropdown
		 */
		renderGroupList: function () {
			SmartSite.groups.each( this.renderGroup, this );
		},
		renderGroup: function ( group ) {
			var view = new SmartSite.views.GroupItem( {
					model: group,
					collection: SmartSite.groups
				} ),
				$element = view.render().$el;

			this.$( '.tvd-global-field-group-list' ).append( $element );
			return $element;
		},

		/**
		 * Select field type action
		 */
		selectType: function ( e ) {
			this.model.set( {data: {}} );
			this.model.set( {type: e.currentTarget.dataset.id} );
			this.$( '#tvd-tss-field-type' ).html( e.currentTarget.innerHTML ).removeClass( 'tvd-invalid' ).addClass( 'tvd-value-selected' );
			this.hideTypeDropdown();
			this.renderByType();
		},
		renderByType: function () {
			var type = SmartSite.utils.get_field_type_name( parseInt( this.model.get( 'type' ) ) );

			if ( ! SmartSite.views[ 'Field' + TVE_Dash.upperFirst( type ) + 'Options' ] ) {
				return;
			}

			var view = new SmartSite.views[ 'Field' + TVE_Dash.upperFirst( type ) + 'Options' ]( {
				model: this.model,
				el: this.$( '.tvd-tss-field-data-wrapper' )[ 0 ]

			} );

			view.render();
			TVE_Dash.materialize( this.$el );
		},
		showTypeDropdown: function () {
			this.$( '.tvd-select-type-dropdown' ).toggleClass( 'tvd-hide' );
		},
		hideTypeDropdown: function () {
			this.$( '.tvd-select-type-dropdown' ).addClass( 'tvd-hide' );
		},
		/**
		 * Set data to the model when inputs are changed
		 *
		 * @param e
		 */
		setInputData: function ( e ) {
			this.tvd_clear_errors();
			var field = $( e.currentTarget ).attr( 'data-field' ),
				props = field.split( '_' );

			if ( ! this.model.get( 'data' ) ) {
				this.model.set( {data: {}} );
			}

			if ( props.length === 1 ) {
				this.model.set( props[ 0 ], e.currentTarget.value );
			} else if ( props.length > 1 ) {
				this.model.get( props[ 0 ] )[ props[ 1 ] ] = e.currentTarget.value;
			}

		},
		inputGroupName: function ( e ) {
			if ( e.keyCode === 13 ) {
				this.saveNewGroup();
			}
			this.tvd_clear_errors();
		},

		/**
		 * New group inside dropdown actions
		 */
		saveNewGroup: function () {
			if ( this.saving_group ) {
				return false;
			}
			var newGroupName = this.$( '#tvd-add-group' ).val(),
				newGroup = new SmartSite.models.Group( {name: newGroupName} ),
				xhr,
				self = this;

			if ( ! newGroup.isValid() ) {
				return this.tvd_show_errors( newGroup );
			}
			this.saving_group = true;
			TVE_Dash.showLoader();
			xhr = newGroup.save();

			if ( xhr ) {
				xhr.done( function ( response, status, options ) {
					newGroup.set( 'fields', new SmartSite.models.collections.Fields() );
					self.collection.add( newGroup );

					TVE_Dash.success( SmartSite.t.GroupSaved );

					self.renderGroup( newGroup ).find( '.tvd-group-option' ).click();
					self.hideAddGroup();
				} );
				xhr.error( function ( errorObj ) {
					var error = JSON.parse( errorObj.responseText );
					TVE_Dash.err( error.message );
				} );
				xhr.always( function () {
					self.saving_group = false;
					TVE_Dash.hideLoader();
				} );
			}

		},
		addNewGroup: function () {
			this.showAddGroup();
		},
		showAddGroup: function () {
			this.$( '#tvd-input-new-group' ).removeClass( 'tvd-hide' );
			this.$( '#tvd-add-new-group' ).addClass( 'tvd-hide' );
			this.$( '#tvd-add-group' ).focus();
		},
		hideAddGroup: function () {
			this.$( '#tvd-input-new-group' ).addClass( 'tvd-hide' );
			this.$( '#tvd-add-new-group' ).removeClass( 'tvd-hide' );
			this.$( '.tvd-select-group-dropdown' ).addClass( 'tvd-hide' );
			this.$( '#tvd-add-group' ).val( '' );
		},
		showDropdown: function () {
			this.$( '.tvd-select-group-dropdown' ).toggleClass( 'tvd-hide' );
		},
		hideDropdown: function () {
			this.$( '.tvd-select-group-dropdown' ).addClass( 'tvd-hide' );
		},
		selectGroup: function ( e ) {
			var $element = $( e.target );
			if ( ! $element.hasClass( 'tvd-group-option' ) ) {
				$element = $element.closest( '.tvd-group-option' );
			}

			var group = this.collection.findWhere( {id: $element.attr( 'data-id' )} );

			if ( group ) {
				this.$( '#tvd-tss-group-name' ).html( group.get( 'name' ) ).removeClass( 'tvd-invalid' ).addClass( 'tvd-value-selected' );
				if ( this.model.get( 'group_id' ) != group.get( 'id' ) ) {
					this.groupChange = true;
				}
				this.model.set( {group_id: group.get( 'id' )} );
				this.hideDropdown();
			}
		},

		/**
		 * Save the field data
		 *
		 * @returns {Backbone.View}
		 */
		save: function () {
			if ( this.$( '#tvd-add-group' ).is( ":visible" ) || this.saving ) {
				return false;
			}
			this.tvd_clear_errors();

			if ( ! this.model.isValid() ) {
				return this.tvd_show_errors( this.model );
			}

			this.saving = true;
			var self = this,
				xhr = this.model.save();

			this.close();
			if ( xhr ) {
				TVE_Dash.showLoader( true );
				xhr.done( function ( response, status, options ) {
					var group = self.collection.findWhere( {id: self.model.get( 'group_id' )} );
					if ( group ) {
						group.get( 'fields' ).add( self.model );
					}
					if ( self.groupChange && self.view ) {
						self.view.$el.remove();
					}
					self.model.set( 'just_added', true );
					self.model.trigger( 'render_field' );
					TVE_Dash.success( SmartSite.t.FieldSaved );
				} );
				xhr.error( function ( errorObj ) {
					var error = JSON.parse( errorObj.responseText );
					TVE_Dash.err( error.message );
				} );
				xhr.always( function () {
					TVE_Dash.hideLoader();
					self.saving = false;
				} );

			}
		}
	} );

	/**
	 * Modal for editing the group
	 */
	modals.EditGroupModal = TVE_Dash.views.Modal.extend( {
		template: TVE_Dash.tpl( 'global-fields/modals/edit-group' ),
		className: 'tvd-modal tss-modal tss-edit-modal',
		events: {
			'input #tvd-tss-group-name': 'setName',
			'click .tvd-modal-submit': 'save',
			'click .tvd-modal-close': 'cancelChanges'
		},
		afterInitialize: function () {
			this.old_name = this.model.get( 'name' );
		},
		cancelChanges: function () {
			this.model.set( 'name', this.old_name );
		},
		/**
		 * Set the group name
		 *
		 * @param e
		 */
		setName: function ( e ) {
			this.model.set( {name: e.currentTarget.value} );
		},
		/**
		 * Save the group
		 */
		save: function () {

			this.tvd_clear_errors();

			if ( ! this.model.isValid() ) {
				return this.tvd_show_errors( this.model );
			}
			this.saving = true;
			TVE_Dash.showLoader();

			var id = this.model.get( 'id' ),
				xhr = this.model.save(),
				self = this;


			if ( xhr ) {
				xhr.done( function ( response, status, options ) {

					self.model.set( {fields: new SmartSite.models.collections.Fields( self.model.get( 'fields' ) )} );
					if ( id ) {
						self.model.trigger( 'render_groups' );
					} else {
						self.collection.add( self.model );
					}

					self.close();
					TVE_Dash.success( SmartSite.t.GroupSaved );
				} );
				xhr.error( function ( errorObj ) {
					var error = JSON.parse( errorObj.responseText );
					TVE_Dash.err( error.message );
				} );
				xhr.always( function () {
					TVE_Dash.hideLoader();
					self.saving = false;
				} );
			}
		}
	} );

	/**
	 * Delete a group or field
	 */
	modals.DeleteModal = TVE_Dash.views.Modal.extend( {
		template: TVE_Dash.tpl( 'global-fields/modals/delete' ),
		className: 'tvd-modal tss-modal',
		events: {
			'click .tvd-delete-item': 'deleteItem'
		},
		afterInitialize: function ( args ) {
			this.$el.addClass( 'tvd-red' );
			var _this = this;
			_.defer( function () {
				_this.$( '.tvd-delete-item' ).focus();
			} );
		},
		/**
		 * Destroy the model
		 * @param e
		 */
		deleteItem: function ( e ) {
			var self = this;
			if ( this.saving ) {
				return false;
			}
			this.saving = true;
			TVE_Dash.showLoader();
			var xhr = this.model.destroy();
			if ( xhr ) {
				xhr.done( function ( response, status, options ) {
					TVE_Dash.success( self.model.get( 'name' ) + ' ' + SmartSite.t.ItemDeleted );
				} );
				xhr.error( function ( errorObj ) {
					var error = JSON.parse( errorObj.responseText );
					TVE_Dash.err( error.message );
				} );
				xhr.always( function () {
					TVE_Dash.hideLoader();
					self.close();
					self.saving = false;
				} );
			}
		}
	} );


	module.exports = modals;

} )( jQuery );