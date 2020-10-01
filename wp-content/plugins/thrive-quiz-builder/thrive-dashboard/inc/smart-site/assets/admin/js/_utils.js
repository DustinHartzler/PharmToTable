( function ( $ ) {

	var utils = {};

	utils.get_field_type_name = function ( id ) {
		return SmartSite.data.fieldTypes[ id ] ? SmartSite.data.fieldTypes[ id ].name : '';
	};

	module.exports = utils;

} )( jQuery );
