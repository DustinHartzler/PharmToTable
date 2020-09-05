var KingSumoAdminApp = angular.module('KingSumoAdminApp', []);

KingSumoAdminApp.controller('entryActions', ['$scope', '$window', '$log', function($scope, $window, $log) {
	var EntryAction = function(type, fields) {
		if (typeof type === 'undefined') {
			console.error('type is required.');
		}

		this.username = '';
		this.platform = 'facebook';
		this.url = '';

		if (type === 'social') {
			this.text = 'Follow us on Facebook';
		} else if (type === 'input') {
			this.text = 'Enter some information for more entries';
		} else {
			this.text = '';
		}
		
		this.input = '';
		this.entries = 1;
		this.type = type;
		this.id = new Date().getTime();
	};

	$scope.entryActionTypes = ['link', 'social', 'youtube-video'];
	$scope.entryActions = $window.KingSumoAdminGlobals.entryActions || [];
	$scope.addActionType = "0";
	$scope.socialMediaPlatforms = ['facebook', 'instagram', 'twitter', 'youtube'];

	$scope.init = function() {
		// If post status is auto-draft then this is a brand new giveaway.
		// Let's default some actions for them to fill out.
		if (KingSumoAdminGlobals.post.post_status === 'auto-draft') {
			var types = ['link', 'social'];
			for (var i = types.length - 1; i >= 0; i--) {
				$scope.addAction(types[i]);
				console.log(types[i]);
			}
		} 
	};

	$scope.addAction = function(type) {
		$scope.entryActions.push(new EntryAction(type));
		$scope.addActionType = "0";
	};

	$scope.changePlatform = function(index) {
		var action = $scope.entryActions[index]
		action.text = "Follow us on " + action.platform;
		action.username = '';
		action.url = '';
	};

	$scope.usesURLField = function(index) {
		var action = $scope.entryActions[index];

		if (action.type === 'social' && !$scope.usesUsernameField(index)) {
			return true;

		} else if (action.type === 'link' || action.type === 'youtube-video') {
			return true;
		}
	};

	$scope.usesUsernameField = function(index) {
		var action = $scope.entryActions[index];
		return action.type === 'social' && ['instagram', 'twitter'].indexOf($scope.entryActions[index].platform) !== -1;
	};

	$scope.deleteAction = function(index) {
		$scope.entryActions.splice(index, 1);
	};

	$scope.validateActionUrl = function(action) {
		if (action.url.substring(0, 1) !== '/' && action.url.substring(0, 7) !== 'http://' && action.url.substring(0, 8) !== 'https://') {
			action.url = 'http://' + action.url;
		}

		return action;
	};

	$scope.capitalizeFirstLetter = function(string) {
    	return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
	};

	$scope.getActionDisplayName = function(type) {
		switch (type) {
			case 'link':
				return 'Click a Link';
			case 'social':
				return 'Follow Social Media';
			case 'youtube-video':
				return 'Watch a YouTube Video';
			default:
				return '';
		}
	};

	$scope.init();

}]);
