/**
 * Created by Dan Brinzaru on 4/25/2019.
 */

var baseCollection = require( './base' );

var model = require( './../models/notification' );

module.exports = baseCollection.extend( {
	model: model
} );
