/******/ (function(modules) { // webpackBootstrap
/******/ 	// install a JSONP callback for chunk loading
/******/ 	function webpackJsonpCallback(data) {
/******/ 		var chunkIds = data[0];
/******/ 		var moreModules = data[1];
/******/ 		var executeModules = data[2];
/******/
/******/ 		// add "moreModules" to the modules object,
/******/ 		// then flag all "chunkIds" as loaded and fire callback
/******/ 		var moduleId, chunkId, i = 0, resolves = [];
/******/ 		for(;i < chunkIds.length; i++) {
/******/ 			chunkId = chunkIds[i];
/******/ 			if(Object.prototype.hasOwnProperty.call(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 				resolves.push(installedChunks[chunkId][0]);
/******/ 			}
/******/ 			installedChunks[chunkId] = 0;
/******/ 		}
/******/ 		for(moduleId in moreModules) {
/******/ 			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
/******/ 				modules[moduleId] = moreModules[moduleId];
/******/ 			}
/******/ 		}
/******/ 		if(parentJsonpFunction) parentJsonpFunction(data);
/******/
/******/ 		while(resolves.length) {
/******/ 			resolves.shift()();
/******/ 		}
/******/
/******/ 		// add entry modules from loaded chunk to deferred list
/******/ 		deferredModules.push.apply(deferredModules, executeModules || []);
/******/
/******/ 		// run deferred modules when all chunks ready
/******/ 		return checkDeferredModules();
/******/ 	};
/******/ 	function checkDeferredModules() {
/******/ 		var result;
/******/ 		for(var i = 0; i < deferredModules.length; i++) {
/******/ 			var deferredModule = deferredModules[i];
/******/ 			var fulfilled = true;
/******/ 			for(var j = 1; j < deferredModule.length; j++) {
/******/ 				var depId = deferredModule[j];
/******/ 				if(installedChunks[depId] !== 0) fulfilled = false;
/******/ 			}
/******/ 			if(fulfilled) {
/******/ 				deferredModules.splice(i--, 1);
/******/ 				result = __webpack_require__(__webpack_require__.s = deferredModule[0]);
/******/ 			}
/******/ 		}
/******/
/******/ 		return result;
/******/ 	}
/******/
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// object to store loaded and loading chunks
/******/ 	// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 	// Promise = chunk loading, 0 = chunk loaded
/******/ 	var installedChunks = {
/******/ 		0: 0
/******/ 	};
/******/
/******/ 	var deferredModules = [];
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	var jsonpArray = window["webpackJsonp"] = window["webpackJsonp"] || [];
/******/ 	var oldJsonpFunction = jsonpArray.push.bind(jsonpArray);
/******/ 	jsonpArray.push = webpackJsonpCallback;
/******/ 	jsonpArray = jsonpArray.slice();
/******/ 	for(var i = 0; i < jsonpArray.length; i++) webpackJsonpCallback(jsonpArray[i]);
/******/ 	var parentJsonpFunction = oldJsonpFunction;
/******/
/******/
/******/ 	// add entry module to deferred list
/******/ 	deferredModules.push([6,2]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return NotificationModel; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var NotificationModel = /*#__PURE__*/function () {
  function NotificationModel() {
    _classCallCheck(this, NotificationModel);

    NotificationModel.initialize();
  }

  _createClass(NotificationModel, null, [{
    key: "initialize",
    value: function initialize() {
      NotificationModel.flags = {
        showNotification: 0
      };
      NotificationModel.notification = {
        message: '',
        status: ''
      };
    }
  }]);

  return NotificationModel;
}();


NotificationModel.initialize();

/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Campaign; });
/* harmony import */ var _CampaignDashboardModel__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(3);
/* harmony import */ var _Functions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(4);
/* harmony import */ var _NotificationModel__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(0);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }





var Campaign = /*#__PURE__*/function () {
  function Campaign(urlParams) {
    _classCallCheck(this, Campaign);

    Campaign.initialize();
    this.setCurrentCampaign(Campaign.currentCampaignID);
  }

  _createClass(Campaign, [{
    key: "setCurrentCampaign",
    value: function setCurrentCampaign(campaignId) {
      var campaignData = {};

      if (campaignId > 0) {
        campaignData = _CampaignDashboardModel__WEBPACK_IMPORTED_MODULE_0__[/* default */ "a"].data.campaigns.filter(function (x) {
          return x.campaignId == campaignId;
        })[0];
      }

      Campaign.details = {
        title: campaignData.title || '',
        targetLink: campaignData.targetLink || afwcDashboardParams.home_url,
        slug: campaignData.slug || '',
        campaignId: campaignData.campaignId || 0,
        shortDescription: campaignData.shortDescription || '',
        body: campaignData.body || '',
        status: campaignData.status || 'Draft',
        metaData: campaignData.metaData || {}
      };
    }
  }], [{
    key: "initialize",
    value: function initialize() {
      Campaign.details = {
        title: '',
        targetLink: afwcDashboardParams.home_url,
        slug: '',
        campaignId: 0,
        shortDescription: '',
        body: '',
        status: 'Draft',
        metaData: {}
      };
    }
  }, {
    key: "newCampaign",
    value: function newCampaign() {
      Campaign.currentCampaignID = -1;
      var newCampaign = new Campaign();
    }
  }, {
    key: "saveCampaign",
    value: function saveCampaign() {
      _Functions__WEBPACK_IMPORTED_MODULE_1__[/* default */ "a"].requestHandler({
        requestData: {
          cmd: 'save_campaign',
          campaign: JSON.stringify(Campaign.details),
          security: afwcDashboardParams.security,
          dashboard: 'afwc_campaign_controller'
        },
        callback: function callback(response) {
          if ("Success" == response.ACK) {
            if (response.last_inserted_id) {
              Campaign.details.campaignId = response.last_inserted_id;
              _CampaignDashboardModel__WEBPACK_IMPORTED_MODULE_0__[/* default */ "a"].data.campaigns.push(Campaign.details);
              _NotificationModel__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"].notification.message = "Campaign created successfully";
            } else {
              _CampaignDashboardModel__WEBPACK_IMPORTED_MODULE_0__[/* default */ "a"].data.campaigns = _CampaignDashboardModel__WEBPACK_IMPORTED_MODULE_0__[/* default */ "a"].data.campaigns.map(function (x) {
                if (x.campaignId == Campaign.details.campaignId) {
                  return Campaign.details;
                } else {
                  return x;
                }
              });
              _NotificationModel__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"].notification.message = "Campaign updated successfully";
            }

            _NotificationModel__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"].flags.showNotification = 1;
            _NotificationModel__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"].notification.status = 'success';
            Campaign.currentCampaignID = 0;
          }
        }
      });
    }
  }, {
    key: "deleteCampaign",
    value: function deleteCampaign() {
      _Functions__WEBPACK_IMPORTED_MODULE_1__[/* default */ "a"].requestHandler({
        requestData: {
          cmd: 'delete_campaign',
          campaign_id: Campaign.currentCampaignID,
          security: afwcDashboardParams.security,
          dashboard: 'afwc_campaign_controller'
        },
        callback: function callback(response) {
          if ("Success" == response.ACK) {
            var cid = Campaign.details.campaignId;
            _CampaignDashboardModel__WEBPACK_IMPORTED_MODULE_0__[/* default */ "a"].data.campaigns = _CampaignDashboardModel__WEBPACK_IMPORTED_MODULE_0__[/* default */ "a"].data.campaigns.filter(function (x) {
              return x.campaignId != cid;
            });
            Campaign.currentCampaignID = 0;
            _NotificationModel__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"].flags.showNotification = 1;
            _NotificationModel__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"].notification.message = "Campaign deleted successfully";
            _NotificationModel__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"].notification.status = 'success';
          }
        }
      });
    }
  }, {
    key: "details",
    get: function get() {
      return Campaign._details;
    },
    set: function set(v) {
      Campaign._details = v;
    }
  }]);

  return Campaign;
}();


Campaign.initialize();

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AFWCLoader; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var AFWCLoader = /*#__PURE__*/function () {
  function AFWCLoader() {
    _classCallCheck(this, AFWCLoader);

    AFWCLoader.showLoader = false;
    AFWCLoader.msg = AFWCLoader.msg ? AFWCLoader.msg : 'Loading';
  }

  _createClass(AFWCLoader, [{
    key: "view",
    value: function view(vnode) {
      return m("div", {
        "class": "absolute w-full mt-48 flex flex-col justify-center text-center items-center space-y-4"
      }, m("div", {
        "class": "text-lg text-gray-600"
      }, AFWCLoader.msg || ''), m("div", {
        "class": "text-indigo-600"
      }, m("svg", {
        xmlns: "http://www.w3.org/2000/svg",
        "class": "w-16 h-16",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 57 57"
      }, m("g", {
        transform: "translate(1 1)",
        "stroke-width": "2",
        fill: "none",
        "fill-rule": "evenodd"
      }, m("circle", {
        cx: "5",
        cy: "50",
        r: "5"
      }, m("animate", {
        attributeName: "cy",
        begin: "0s",
        dur: "2.2s",
        values: "50;5;50;50",
        calcMode: "linear",
        repeatCount: "indefinite"
      }), m("animate", {
        attributeName: "cx",
        begin: "0s",
        dur: "2.2s",
        values: "5;27;49;5",
        calcMode: "linear",
        repeatCount: "indefinite"
      })), m("circle", {
        cx: "27",
        cy: "5",
        r: "5"
      }, m("animate", {
        attributeName: "cy",
        begin: "0s",
        dur: "2.2s",
        from: "5",
        to: "5",
        values: "5;50;50;5",
        calcMode: "linear",
        repeatCount: "indefinite"
      }), m("animate", {
        attributeName: "cx",
        begin: "0s",
        dur: "2.2s",
        from: "27",
        to: "27",
        values: "27;49;5;27",
        calcMode: "linear",
        repeatCount: "indefinite"
      })), m("circle", {
        cx: "49",
        cy: "50",
        r: "5"
      }, m("animate", {
        attributeName: "cy",
        begin: "0s",
        dur: "2.2s",
        values: "50;50;5;50",
        calcMode: "linear",
        repeatCount: "indefinite"
      }), m("animate", {
        attributeName: "cx",
        from: "49",
        to: "49",
        begin: "0s",
        dur: "2.2s",
        values: "49;5;27;49",
        calcMode: "linear",
        repeatCount: "indefinite"
      }))))));
    }
  }]);

  return AFWCLoader;
}();



/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return CampaignDashboardModel; });
/* harmony import */ var _Functions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(4);
/* harmony import */ var _Loader__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }




var CampaignDashboardModel = /*#__PURE__*/function () {
  function CampaignDashboardModel(urlParams) {
    _classCallCheck(this, CampaignDashboardModel);

    CampaignDashboardModel.data = {
      kpi: {},
      campaigns: []
    };
    this.params = urlParams || {};
    this.fetch();
  }

  _createClass(CampaignDashboardModel, [{
    key: "fetch",
    value: function fetch() {
      _Loader__WEBPACK_IMPORTED_MODULE_1__[/* default */ "a"].showLoader = true;
      _Loader__WEBPACK_IMPORTED_MODULE_1__[/* default */ "a"].msg = 'Loading Campaigns';
      _Functions__WEBPACK_IMPORTED_MODULE_0__[/* default */ "a"].requestHandler({
        requestData: {
          cmd: 'fetch_dashboard_data',
          security: afwcDashboardParams.security,
          dashboard: 'afwc_campaign_controller',
          campaign_status: afwcDashboardParams.campaign_status || ''
        },
        callback: function callback(response) {
          if ("Success" == response.ACK) {
            // Campaign.details.campaignId = response.last_inserted_id;
            // CampaignDashboardModel.data.campaigns.push(Campaign.details);
            CampaignDashboardModel.data.kpi = response.result.kpi || {};
            CampaignDashboardModel.data.campaigns = response.result.campaigns || [];
          }

          _Loader__WEBPACK_IMPORTED_MODULE_1__[/* default */ "a"].showLoader = false;
        }
      });
    }
  }], [{
    key: "data",
    get: function get() {
      return CampaignDashboardModel._data;
    },
    set: function set(v) {
      CampaignDashboardModel._data = v;
    }
  }]);

  return CampaignDashboardModel;
}();


CampaignDashboardModel.data = {
  kpi: {},
  campaigns: []
};

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AFWCFunctions; });
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var AFWCFunctions = /*#__PURE__*/function () {
  function AFWCFunctions() {
    _classCallCheck(this, AFWCFunctions);
  }

  _createClass(AFWCFunctions, null, [{
    key: "requestHandler",
    value: function requestHandler(params) {
      var data = new FormData(),
          requestData = {
        security: afwcDashboardParams.security
      };
      requestData = _objectSpread(_objectSpread({}, requestData), params.requestData);

      for (var key in requestData) {
        data.append(key, requestData[key]);
      }

      m.request({
        method: params.method || 'POST',
        url: afwcDashboardParams.ajaxurl,
        params: {
          action: requestData.dashboard
        },
        body: data,
        withCredentials: params.withCredentials || false,
        responseType: params.responseType || "json"
      }).then(function (response) {
        if (params.hasOwnProperty('callback')) {
          params.callback(response);
        }
      });
    }
  }, {
    key: "getDate",
    value: function getDate(dateValue) {
      var now = new Date();
      var startDate,
          endDate = '';

      switch (dateValue) {
        case 'today':
          startDate = now;
          endDate = now;
          break;

        case 'yesterday':
          startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
          endDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
          break;

        case 'this_week':
          startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - (now.getDay() - 1));
          endDate = now;
          break;

        case 'last_week':
          startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - (now.getDay() - 1) - 7);
          endDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - (now.getDay() - 1) - 1);
          break;

        case 'last_4_week':
          startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 29); //for exactly 30 days limit

          endDate = now;
          break;

        case 'this_month':
          startDate = new Date(now.getFullYear(), now.getMonth(), 1);
          endDate = now;
          break;

        case 'last_month':
          startDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
          endDate = new Date(now.getFullYear(), now.getMonth(), 0);
          break;

        case '3_months':
          startDate = new Date(now.getFullYear(), now.getMonth() - 2, 1);
          endDate = now;
          break;

        case '6_months':
          startDate = new Date(now.getFullYear(), now.getMonth() - 5, 1);
          endDate = now;
          break;

        case 'this_year':
          startDate = new Date(now.getFullYear(), 0, 1);
          endDate = now;
          break;

        case 'last_year':
          startDate = new Date(now.getFullYear() - 1, 0, 1);
          endDate = new Date(now.getFullYear(), 0, 0);
          break;

        default:
          startDate = new Date(now.getFullYear(), now.getMonth(), 1);
          endDate = now;
          break;
      } //timezone adjustment for isostring function


      var tzoffset = new Date().getTimezoneOffset() * 60000;
      startDate = new Date(startDate.getTime() - tzoffset);
      endDate = new Date(endDate.getTime() - tzoffset);
      return {
        'startDate': startDate.toISOString().slice(0, 10),
        'endDate': endDate.toISOString().slice(0, 10)
      };
    }
  }]);

  return AFWCFunctions;
}();



/***/ }),
/* 5 */,
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXTERNAL MODULE: ./src/admin/style.css
var style = __webpack_require__(5);

// EXTERNAL MODULE: ./src/admin/models/campaign/Campaign.js
var Campaign = __webpack_require__(1);

// EXTERNAL MODULE: ./src/admin/Functions.js
var Functions = __webpack_require__(4);

// EXTERNAL MODULE: ./src/admin/Loader.js
var Loader = __webpack_require__(2);

// CONCATENATED MODULE: ./src/admin/models/commission/CommissionDashboardModel.js
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }




var CommissionDashboardModel_CommissionDashboardModel = /*#__PURE__*/function () {
  function CommissionDashboardModel(urlParams) {
    _classCallCheck(this, CommissionDashboardModel);

    CommissionDashboardModel.data = {
      kpi: {},
      commissions: []
    };
    this.params = urlParams || {};
    this.fetch();
  }

  _createClass(CommissionDashboardModel, [{
    key: "fetch",
    value: function fetch() {
      Loader["a" /* default */].showLoader = true;
      Loader["a" /* default */].msg = 'Loading Commission Plans';
      Functions["a" /* default */].requestHandler({
        requestData: {
          cmd: 'fetch_dashboard_data',
          security: afwcDashboardParams.security,
          dashboard: 'afwc_commission_controller',
          commission_status: afwcDashboardParams.commission_status || ''
        },
        callback: function callback(response) {
          if ("Success" == response.ACK) {
            var commissions = response.result.commissions || [];
            CommissionDashboardModel.data.commissions = [];
            CommissionDashboardModel.data.planOrder = response.result.plan_order || [];
            CommissionDashboardModel.data.planOrder = Object.keys(CommissionDashboardModel.data.planOrder).map(function (key, id) {
              return parseInt(CommissionDashboardModel.data.planOrder[key]);
            });
            CommissionDashboardModel.reOrder(commissions);
          }

          Loader["a" /* default */].showLoader = false;
        }
      });
    }
  }], [{
    key: "reOrder",
    value: function reOrder(commissions) {
      if (typeof commissions == "undefined") {
        commissions = CommissionDashboardModel.data.commissions;
      }

      var listCommission = [];

      if (commissions && commissions.length > 0 && CommissionDashboardModel.data.planOrder && CommissionDashboardModel.data.planOrder.length > 0) {
        var _loop = function _loop(index) {
          var id = CommissionDashboardModel.data.planOrder[index];
          var current = commissions.filter(function (x) {
            return x.commissionId == id;
          })[0];

          if (current) {
            listCommission.push(current);
          }
        };

        for (var index = 0; index < CommissionDashboardModel.data.planOrder.length; index++) {
          _loop(index);
        }
      } else {
        listCommission = commissions;
      }

      CommissionDashboardModel.data.commissions = listCommission.slice();
    }
  }, {
    key: "savePlanOrder",
    value: function savePlanOrder() {
      Loader["a" /* default */].showLoader = true;
      Functions["a" /* default */].requestHandler({
        requestData: {
          cmd: 'save_plan_order',
          plan_order: JSON.stringify(CommissionDashboardModel.data.planOrder),
          security: afwcDashboardParams.security,
          dashboard: 'afwc_commission_controller'
        },
        callback: function callback(response) {
          Loader["a" /* default */].showLoader = false;
        }
      });
    }
  }, {
    key: "data",
    get: function get() {
      return CommissionDashboardModel._data;
    },
    set: function set(v) {
      CommissionDashboardModel._data = v;
    }
  }]);

  return CommissionDashboardModel;
}();


CommissionDashboardModel_CommissionDashboardModel.data = {
  commissions: []
};
// EXTERNAL MODULE: ./src/admin/models/NotificationModel.js
var NotificationModel = __webpack_require__(0);

// CONCATENATED MODULE: ./src/admin/models/commission/Commission.js
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function Commission_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Commission_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Commission_createClass(Constructor, protoProps, staticProps) { if (protoProps) Commission_defineProperties(Constructor.prototype, protoProps); if (staticProps) Commission_defineProperties(Constructor, staticProps); return Constructor; }





var Commission_Commission = /*#__PURE__*/function () {
  function Commission(urlParams) {
    Commission_classCallCheck(this, Commission);

    Commission.initialize();
    this.setCurrentCommission(Commission.currentCommissionID);
  }

  Commission_createClass(Commission, [{
    key: "setCurrentCommission",
    value: function setCurrentCommission(commissionId) {
      var commissionData = {};

      if (commissionId > 0) {
        commissionData = CommissionDashboardModel_CommissionDashboardModel.data.commissions.filter(function (x) {
          return x.commissionId == commissionId;
        })[0];
      }

      Commission.details = {
        commissionId: commissionData.commissionId || 0,
        name: commissionData.name || '',
        rules: commissionData.rules || '',
        amount: commissionData.amount || '',
        type: commissionData.type || '',
        status: commissionData.status || 'Draft',
        apply_to: commissionData.apply_to || 'all',
        action_for_remaining: commissionData.action_for_remaining || 'continue'
      };

      if (commissionId > 0) {
        this.fetchExtraData(Commission.details.rules);
      }
    }
  }, {
    key: "fetchExtraData",
    value: function fetchExtraData(rules) {
      var ruleGroups = rules.rules;
      var res = {};
      Object.keys(ruleGroups).map(function (key, id) {
        var r = ruleGroups[key].rules;
        Object.keys(r).map(function (k, i) {
          if (!res[r[k].type]) {
            res[r[k].type] = [];
          }

          res[r[k].type] = res[r[k].type].concat(r[k].value);
          res[r[k].type] = _toConsumableArray(new Set(res[r[k].type]));
        });
      });
      Functions["a" /* default */].requestHandler({
        requestData: {
          cmd: 'fetch_extra_data',
          security: afwcDashboardParams.security,
          dashboard: 'afwc_commission_controller',
          data: JSON.stringify(res)
        },
        callback: function callback(response) {
          if ("Success" == response.ACK) {
            Commission.idNameMaps = response.result;
          }
        }
      });
    }
  }], [{
    key: "initialize",
    value: function initialize() {
      Commission.details = {
        commissionId: 0,
        name: '',
        rules: {},
        amount: '',
        type: '',
        status: 'Draft',
        apply_to: 'all',
        action_for_remaining: 'continue'
      };
      var ruleData = afwcDashboardParams.plan_dashboard_data.plan_rule_data;
      var allRules = {};
      Object.values(ruleData).map(function (x) {
        allRules = Object.assign(allRules, x);
      });
      Commission.allRules = allRules;
      Commission.ruleRow = {
        'type': 'affiliate',
        'operator': 'in',
        'value': ''
      };
      Commission.ruleGroup = {
        'condition': 'AND',
        'rules': [Commission.ruleRow]
      };
      Commission.idNameMaps = {};
    }
  }, {
    key: "newCommission",
    value: function newCommission() {
      Commission.currentCommissionID = -1;
      var newCommission = new Commission();
    }
  }, {
    key: "saveCommission",
    value: function saveCommission() {
      Functions["a" /* default */].requestHandler({
        requestData: {
          cmd: 'save_commission',
          commission: JSON.stringify(Commission.details),
          security: afwcDashboardParams.security,
          dashboard: 'afwc_commission_controller'
        },
        callback: function callback(response) {
          if ("Success" == response.ACK) {
            if (response.last_inserted_id) {
              Commission.details.commissionId = response.last_inserted_id;
              CommissionDashboardModel_CommissionDashboardModel.data.commissions.push(Commission.details);
              CommissionDashboardModel_CommissionDashboardModel.data.planOrder.push(response.last_inserted_id);
              NotificationModel["a" /* default */].notification.message = "Commission plan created successfully";
            } else {
              CommissionDashboardModel_CommissionDashboardModel.data.commissions = CommissionDashboardModel_CommissionDashboardModel.data.commissions.map(function (x) {
                if (x.commissionId == Commission.details.commissionId) {
                  return Commission.details;
                } else {
                  return x;
                }
              });
              NotificationModel["a" /* default */].notification.message = "Commission plan updated successfully";
            }

            NotificationModel["a" /* default */].flags.showNotification = 1;
            NotificationModel["a" /* default */].notification.status = 'success';
            Commission.currentCommissionID = 0;
          }
        }
      });
    }
  }, {
    key: "deleteCommission",
    value: function deleteCommission() {
      Functions["a" /* default */].requestHandler({
        requestData: {
          cmd: 'delete_commission',
          commission_id: Commission.currentCommissionID,
          security: afwcDashboardParams.security,
          dashboard: 'afwc_commission_controller'
        },
        callback: function callback(response) {
          if ("Success" == response.ACK) {
            var cid = Commission.details.commissionId;
            CommissionDashboardModel_CommissionDashboardModel.data.commissions = CommissionDashboardModel_CommissionDashboardModel.data.commissions.filter(function (x) {
              return x.commissionId != cid;
            });
            CommissionDashboardModel_CommissionDashboardModel.data.planOrder = CommissionDashboardModel_CommissionDashboardModel.data.planOrder.filter(function (x) {
              return x != cid;
            });
            Commission.currentCommissionID = 0;
            NotificationModel["a" /* default */].flags.showNotification = 1;
            NotificationModel["a" /* default */].notification.message = "Commission plan deleted successfully";
            NotificationModel["a" /* default */].notification.status = 'success';
          }
        }
      });
    }
  }, {
    key: "details",
    get: function get() {
      return Commission._details;
    },
    set: function set(v) {
      Commission._details = v;
    }
  }]);

  return Commission;
}();


Commission_Commission.initialize();
// CONCATENATED MODULE: ./src/admin/models/affiliate/DashboardModel.js
function DashboardModel_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function DashboardModel_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function DashboardModel_createClass(Constructor, protoProps, staticProps) { if (protoProps) DashboardModel_defineProperties(Constructor.prototype, protoProps); if (staticProps) DashboardModel_defineProperties(Constructor, staticProps); return Constructor; }



var DashboardModel_DashboardModel = /*#__PURE__*/function () {
  function DashboardModel() {
    DashboardModel_classCallCheck(this, DashboardModel);

    DashboardModel.data = {
      kpi: {},
      affiliates: [],
      filters: {},
      q: ''
    };
    Loader["a" /* default */].showLoader = false;
    DashboardModel.data.firstcall = true;
    DashboardModel.data.currentAffiliateID = 0;
    DashboardModel.data.start_date = DashboardModel.data.start_date ? DashboardModel.data.start_date : new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().slice(0, 10);
    DashboardModel.data.end_date = DashboardModel.data.end_date ? DashboardModel.data.end_date : new Date().toISOString().slice(0, 10);
    DashboardModel.fetch();
  }

  DashboardModel_createClass(DashboardModel, null, [{
    key: "fetch",
    value: function fetch() {
      Loader["a" /* default */].showLoader = true;
      Loader["a" /* default */].msg = 'Loading Affiliates';
      var data = new FormData(),
          requestData = {
        cmd: 'dashboard_data',
        security: afwcDashboardParams.security,
        from_date: DashboardModel.data.start_date,
        to_date: DashboardModel.data.end_date,
        filters: JSON.stringify(DashboardModel.data.filters),
        q: DashboardModel.data.q
      };

      for (var key in requestData) {
        data.append(key, requestData[key]);
      }

      m.request({
        method: 'POST',
        url: ajaxurl,
        params: {
          action: 'afwc_dashboard_controller'
        },
        body: data,
        withCredentials: false,
        responseType: "json"
      }).then(function (data) {
        return DashboardModel.extractData(data);
      });
    }
  }, {
    key: "extractData",
    value: function extractData(data) {
      DashboardModel.data.kpi = data.kpi || {};
      DashboardModel.data.affiliates = data.affiliateList || [];
      Loader["a" /* default */].showLoader = false;
      var current;

      if (DashboardModel.data.currentAffiliateID > 0) {
        current = DashboardModel.data.affiliates.filter(function (x) {
          return x.affiliate_id == DashboardModel.data.currentAffiliateID;
        })[0];
      }

      if (typeof current === 'undefined') {
        DashboardModel.data.currentAffiliateID = DashboardModel.data.affiliates.length > 0 ? DashboardModel.data.affiliates[0].affiliate_id : 0;
      }
    }
  }, {
    key: "exportAffiliates",
    value: function exportAffiliates(type) {
      var requestData = {
        cmd: 'export_affiliates',
        security: afwcDashboardParams.security,
        from_date: DashboardModel.data.start_date,
        to_date: DashboardModel.data.end_date,
        filters: JSON.stringify(DashboardModel.data.filters),
        q: DashboardModel.data.q,
        action: 'afwc_dashboard_controller',
        type: type
      };
      var url = new URL(afwcDashboardParams.ajaxurl);

      for (var d in requestData) {
        url.searchParams.append(d, requestData[d]);
      }

      window.open(url.href);
    }
  }, {
    key: "data",
    get: function get() {
      return DashboardModel._data;
    },
    set: function set(v) {
      DashboardModel._data = v;
    }
  }]);

  return DashboardModel;
}();


DashboardModel_DashboardModel.data = {
  kpi: {},
  affiliates: [],
  filters: {},
  q: ''
};
// CONCATENATED MODULE: ./src/admin/views/affiliate/SearchBox.js
function SearchBox_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function SearchBox_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function SearchBox_createClass(Constructor, protoProps, staticProps) { if (protoProps) SearchBox_defineProperties(Constructor.prototype, protoProps); if (staticProps) SearchBox_defineProperties(Constructor, staticProps); return Constructor; }




var SearchBox_SearchBox = /*#__PURE__*/function () {
  function SearchBox() {
    SearchBox_classCallCheck(this, SearchBox);

    document.onclick = function (e) {
      if (e.target.id != 'afwc-search-field' && !e.target.closest("#afwc-search-panel")) {
        document.getElementById("afwc-search-panel").classList.add('hidden');
      }

      if (e.target.id != 'afwc-export-btn' && !e.target.closest("#afwc-export-option") && !e.target.closest("#afwc-export-btn")) {
        var x = document.getElementById("afwc-export-option");

        if (x && x.style.display !== "none") {
          x.style.display = "none";
        }
      }
    };
  }

  SearchBox_createClass(SearchBox, [{
    key: "setFilters",
    value: function setFilters(filter, filterVal) {
      this.startDate = document.getElementById('start-date').value;
      this.endDate = document.getElementById('end-date').value;

      if ('reset' == filter) {
        DashboardModel_DashboardModel.data.filters = {};
        DashboardModel_DashboardModel.data.q = '';
        document.getElementById('afwc-search-field').value = '';
        DashboardModel_DashboardModel.fetch();
        return;
      }

      if (!DashboardModel_DashboardModel.data.filters[filter]) {
        DashboardModel_DashboardModel.data.filters[filter] = [];
      }

      if (DashboardModel_DashboardModel.data.filters[filter].indexOf(filterVal) > -1) {
        DashboardModel_DashboardModel.data.filters[filter] = DashboardModel_DashboardModel.data.filters[filter].filter(function (e) {
          return e !== filterVal;
        });
      } else {
        DashboardModel_DashboardModel.data.filters[filter].push(filterVal);
      }

      if (DashboardModel_DashboardModel.data.firstcall && DashboardModel_DashboardModel.data.affiliates.length <= 0 && 'date_filter' !== filter) {
        return;
      }

      DashboardModel_DashboardModel.data.firstcall = false;

      if (filter && 'date_filter' === filter) {
        var dates = Functions["a" /* default */].getDate(filterVal);
        this.startDate = dates.startDate ? dates.startDate : this.startDate;
        this.endDate = dates.endDate ? dates.endDate : this.endDate;
      }

      DashboardModel_DashboardModel.data.start_date = this.startDate;
      DashboardModel_DashboardModel.data.end_date = this.endDate;
      DashboardModel_DashboardModel.fetch();
    }
  }, {
    key: "view",
    value: function view(vnode) {
      var _this = this;

      return m("div", {
        id: "afwc-search-panel",
        style: "max-width:fit-content",
        "class": "hidden absolute w-screen px-2 mt-1 transform -translate-x-1/2 left-7/12 sm:px-0 lg:max-w-xl"
      }, m("div", {
        "class": "rounded-lg shadow-lg"
      }, m("div", {
        "class": "overflow-hidden rounded-lg shadow-xs"
      }, m("div", {
        "class": "relative z-20 p-5 bg-white"
      }, m("div", {
        "class": "flex items-center p-3 -m-3 space-x-2"
      }, m("div", {
        "class": "flex items-center justify-center flex-shrink-0 w-10 h-10 text-indigo-600 rounded-md"
      }, m("svg", {
        "class": "w-6 h-6",
        fill: "none",
        viewBox: "0 0 24 24",
        stroke: "currentColor",
        "stroke-width": "2"
      }, m("path", {
        d: "M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
      }))), m("div", {
        "class": "flex items-center text-xs font-medium text-yellow-800"
      }, Object.keys(afwcDashboardParams.afwc_filters.affiliate_status).length > 0 ? Object.keys(afwcDashboardParams.afwc_filters.affiliate_status).map(function (key, id) {
        return m("a", {
          "class": "m-1 px-3 py-0.5 rounded-full cursor-pointer bg-yellow-50 hover:bg-yellow-300 " + (DashboardModel_DashboardModel.data.filters.affiliate_status && DashboardModel_DashboardModel.data.filters.affiliate_status.indexOf(key) != -1 ? "border-yellow-800 border-solid border" : ""),
          "data-filter": key,
          onclick: function onclick(event) {
            event.preventDefault();
            setTimeout(function () {
              document.getElementById('afwc-search-field').focus();
            }, 5000);

            _this.setFilters('affiliate_status', event.target.getAttribute('data-filter'));
          }
        }, afwcDashboardParams.afwc_filters.affiliate_status[key]);
      }) : "")), m("div", {
        "class": "flex items-center p-3 -m-3 space-x-2"
      }, m("div", {
        "class": "flex items-center justify-center flex-shrink-0 w-10 h-10 text-indigo-600 rounded-md"
      }, m("svg", {
        "class": "w-6 h-6",
        viewBox: "0 0 24 24",
        fill: "currentColor",
        stroke: "none"
      }, m("path", {
        d: "M19 7h-3V6a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-8a3 3 0 0 0-3-3zm-9-1a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4zm-6 4a1 1 0 0 1 1-1h1a2 2 0 0 1-2 2zm1 9a1 1 0 0 1-1-1v-1a2 2 0 0 1 2 2zm15-1a1 1 0 0 1-1 1h-1a2 2 0 0 1 2-2zm0-3a4 4 0 0 0-4 4H8a4 4 0 0 0-4-4v-2a4 4 0 0 0 4-4h8a4 4 0 0 0 4 4zm0-4a2 2 0 0 1-2-2h1a1 1 0 0 1 1 1zm-8 0a3 3 0 1 0 3 3 3 3 0 0 0-3-3zm0 4a1 1 0 1 1 1-1 1 1 0 0 1-1 1z"
      }))), m("div", {
        "class": "flex items-center text-xs font-medium text-green-800"
      }, Object.keys(afwcDashboardParams.afwc_filters.order_status).length > 0 ? Object.keys(afwcDashboardParams.afwc_filters.order_status).map(function (key, id) {
        return m("a", {
          "class": "m-1 px-3 py-0.5 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 " + (DashboardModel_DashboardModel.data.filters.order_status && DashboardModel_DashboardModel.data.filters.order_status.indexOf(key) != -1 ? "border-green-800 border-solid border" : ""),
          "data-filter": key,
          onclick: function onclick(event) {
            event.preventDefault();

            _this.setFilters('order_status', event.target.getAttribute('data-filter'));
          }
        }, afwcDashboardParams.afwc_filters.order_status[key]);
      }) : "")), m("div", {
        "class": "flex items-center p-3 -m-3 space-x-2"
      }, m("div", {
        "class": "flex items-center justify-center flex-shrink-0 w-10 h-10 text-indigo-600 rounded-md"
      }, m("svg", {
        "class": "w-6 h-6",
        viewBox: "0 0 24 24",
        fill: "none",
        stroke: "currentColor",
        "stroke-width": "2"
      }, m("path", {
        d: "M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
      }))), m("div", {
        "class": "flex flex-wrap items-center text-xs font-medium text-blue-800"
      }, Object.keys(afwcDashboardParams.afwc_filters.tags).length > 0 ? Object.keys(afwcDashboardParams.afwc_filters.tags).map(function (key, id) {
        return m("a", {
          "class": "m-1 px-3 py-0.5 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 " + (DashboardModel_DashboardModel.data.filters.tags && DashboardModel_DashboardModel.data.filters.tags.indexOf(key) != -1 ? "border-blue-800 border-solid border" : ""),
          "data-filter": key,
          onclick: function onclick(event) {
            event.preventDefault();

            _this.setFilters('tags', event.target.getAttribute('data-filter'));
          }
        }, afwcDashboardParams.afwc_filters.tags[key]);
      }) : "")), m("div", {
        "class": "flex items-center p-3 -m-3 space-x-2"
      }, m("div", {
        "class": "flex items-center justify-center flex-shrink-0 w-10 h-10 text-indigo-600 rounded-md"
      }, m("svg", {
        "class": "w-6 h-6",
        viewBox: "0 0 24 24",
        fill: "none",
        stroke: "currentColor",
        "stroke-width": "2"
      }, m("path", {
        d: "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
      }))), m("div", {
        "class": "inline-flex flex-wrap items-center text-xs font-medium text-orange-800"
      }, Object.keys(afwcDashboardParams.afwc_filters.date_filter).length > 0 ? Object.keys(afwcDashboardParams.afwc_filters.date_filter).map(function (key, id) {
        return m("a", {
          "class": "m-1 px-3 py-0.5 rounded-full cursor-pointer bg-orange-50 hover:bg-orange-300 " + (DashboardModel_DashboardModel.data.filters.date_filter && DashboardModel_DashboardModel.data.filters.date_filter.indexOf(key) != -1 ? "border-orange-800 border-solid border" : ""),
          "data-filter": key,
          onclick: function onclick(event) {
            event.preventDefault();

            _this.setFilters('date_filter', event.target.getAttribute('data-filter'));
          }
        }, afwcDashboardParams.afwc_filters.date_filter[key]);
      }) : ""))), m("div", {
        "class": "py-5 pl-7 pr-4 bg-gray-50"
      }, m("div", {
        "class": "flex items-center space-x-3"
      }, m("div", {
        "class": "w-4/5"
      }, m("p", {
        "class": "text-sm font-medium text-gray-900"
      }, "Search & Filter"), m("p", {
        "class": "text-xs text-gray-500"
      }, "Click on a label to filter. Type in the search box to search.")), m("div", {
        "class": "text-xs border border-gray rounded-md px-2 py-0.5"
      }, m("a", {
        "class": "cursor-pointer",
        onclick: function onclick(event) {
          event.preventDefault();

          _this.setFilters('reset', '');
        }
      }, "Reset filters")))))));
    }
  }]);

  return SearchBox;
}();


// CONCATENATED MODULE: ./src/admin/models/FeedbackModel.js
function FeedbackModel_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function FeedbackModel_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function FeedbackModel_createClass(Constructor, protoProps, staticProps) { if (protoProps) FeedbackModel_defineProperties(Constructor.prototype, protoProps); if (staticProps) FeedbackModel_defineProperties(Constructor, staticProps); return Constructor; }



var FeedbackModel_FeedbackModel = /*#__PURE__*/function () {
  function FeedbackModel() {
    FeedbackModel_classCallCheck(this, FeedbackModel);

    FeedbackModel.initialize();
  }

  FeedbackModel_createClass(FeedbackModel, null, [{
    key: "initialize",
    value: function initialize() {
      FeedbackModel.flags = {
        showFeedback: 0
      };
      FeedbackModel.data = {
        update_action: ''
      };
    }
  }, {
    key: "updateFeedback",
    value: function updateFeedback() {
      Functions["a" /* default */].requestHandler({
        requestData: {
          cmd: 'update_feedback',
          security: afwcDashboardParams.security,
          dashboard: 'afwc_dashboard_controller',
          update_action: FeedbackModel.data.update_action || ''
        },
        callback: function callback(response) {
          if ("Success" == response.ACK) {
            FeedbackModel.flags.showFeedback = 0;
            afwcDashboardParams.can_ask_for_feedback = 0;

            if ('review' === FeedbackModel.data.update_action) {
              window.open(afwcDashboardParams.review_link);
            }
          }
        }
      });
    }
  }]);

  return FeedbackModel;
}();


FeedbackModel_FeedbackModel.initialize();
// CONCATENATED MODULE: ./src/admin/Feedback.js
function Feedback_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Feedback_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Feedback_createClass(Constructor, protoProps, staticProps) { if (protoProps) Feedback_defineProperties(Constructor.prototype, protoProps); if (staticProps) Feedback_defineProperties(Constructor, staticProps); return Constructor; }



var Feedback_AFWCFeedback = /*#__PURE__*/function () {
  function AFWCFeedback() {
    Feedback_classCallCheck(this, AFWCFeedback);
  }

  Feedback_createClass(AFWCFeedback, [{
    key: "closeFeedback",
    value: function closeFeedback() {
      FeedbackModel_FeedbackModel.data.update_action = 'close';
      FeedbackModel_FeedbackModel.updateFeedback(); // setTimeout((FeedbackModel.flags.showFeedback = 0),2000);
    }
  }, {
    key: "addReview",
    value: function addReview() {
      FeedbackModel_FeedbackModel.data.update_action = 'review';
      FeedbackModel_FeedbackModel.updateFeedback();
    }
  }, {
    key: "view",
    value: function view() {
      var _this = this;

      return m("div", {
        "class": "fixed inset-0 ml-36	flex pointer-events-none items-end justify-start px-4 py-6 sm:p-6 sm:items-start sm:justify-start " + (FeedbackModel_FeedbackModel.flags.showFeedback === 0 ? "hidden" : ""),
        style: "z-index: 200000;"
      }, m("div", {
        "class": "w-full max-w-sm bg-white rounded-lg shadow-lg bottom-0 fixed pointer-events-auto"
      }, m("div", {
        "class": "overflow-hidden rounded-lg shadow-xs text-white"
      }, m("div", {
        "class": "bg-indigo-400\tp-4"
      }, m("div", {
        "class": "flex items-center"
      }, m("div", {
        "class": "flex-shrink-0"
      }, m("svg", {
        "class": "w-6 h-6",
        stroke: "currentColor",
        fill: "none",
        "stroke-width": "2",
        viewBox: "0 0 24 24"
      }, m("path", {
        d: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
      }))), m("div", {
        "class": "ml-3 flex-1"
      }, m("p", {
        "class": "text-sm leading-5"
      }, "Liked ", m("span", {
        "class": "text-pink-900 font-semibold"
      }, "Affiliate for WooCommerce"), " plugin?"), m("p", {
        "class": "text-sm leading-5"
      }, "Leave us a 5-star rating ", m("a", {
        "class": "cursor-pointer underline text-purple-900 font-semibold",
        onclick: function onclick() {
          _this.addReview();
        }
      }, "here"), " and boost our motivation! A big thanks in advance from StoreApps team!")), m("div", {
        "class": "flex flex-shrink-0 ml-4"
      }, m("button", {
        type: "button",
        "class": "afwc-feedback-close inline-flex p-1 rounded-md hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out",
        onclick: function onclick() {
          _this.closeFeedback();
        }
      }, m("svg", {
        "class": "h-6 w-6 text-white",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 24 24"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M6 18L18 6M6 6l12 12"
      })))))))));
    }
  }]);

  return AFWCFeedback;
}();


// CONCATENATED MODULE: ./src/admin/views/NavBar.js
function NavBar_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function NavBar_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function NavBar_createClass(Constructor, protoProps, staticProps) { if (protoProps) NavBar_defineProperties(Constructor.prototype, protoProps); if (staticProps) NavBar_defineProperties(Constructor, staticProps); return Constructor; }








var NavBar_AFWNavBar = /*#__PURE__*/function () {
  function AFWNavBar(urlParams) {
    NavBar_classCallCheck(this, AFWNavBar);

    this.timeout = null;
  }

  NavBar_createClass(AFWNavBar, [{
    key: "refreshRoute",
    value: function refreshRoute() {
      DashboardModel_DashboardModel.data.start_date = document.getElementById('start-date').value;
      DashboardModel_DashboardModel.data.end_date = document.getElementById('end-date').value;
      DashboardModel_DashboardModel.fetch();
    }
  }, {
    key: "setSearchTerm",
    value: function setSearchTerm(searchTerm) {
      clearTimeout(this.timeout);
      DashboardModel_DashboardModel.data.start_date = document.getElementById('start-date').value;
      DashboardModel_DashboardModel.data.end_date = document.getElementById('end-date').value;
      DashboardModel_DashboardModel.data.q = searchTerm;

      if (searchTerm === '') {
        DashboardModel_DashboardModel.fetch();
      }

      if (searchTerm.length < 3) {
        return;
      }

      if (DashboardModel_DashboardModel.data.firstcall && DashboardModel_DashboardModel.data.affiliates.length <= 0) {
        return;
      }

      Loader["a" /* default */].showLoader = true;
      this.timeout = setTimeout(function () {
        DashboardModel_DashboardModel.fetch();
      }, 4000);
    }
  }, {
    key: "view",
    value: function view(vnode) {
      var _this = this;

      return m("div", {
        "class": "bg-gray-800"
      }, m("nav", {
        "class": "max-w-7xl mx-auto px-4 xl:px-8 py-2 lg:py-2"
      }, m("div", {
        "class": "flex flex-col lg:items-center lg:flex-row lg:justify-between"
      }, m("div", {
        "class": "flex items-center"
      }, m("div", {
        "class": "flex-shrink-0"
      }, m("h2", {
        "class": "text-2xl font-bold leading-7 text-gray-100 sm:text-3xl sm:leading-9"
      }, "Affiliates")), m("div", {
        "class": ""
      }, m("div", {
        "class": "ml-10 lg:ml-6 xl:ml-10 flex items-baseline"
      }, m("a", {
        href: "#",
        "class": "px-3 py-2 rounded-md text-md mr-2 font-medium text-white focus:outline-none focus:text-white focus:bg-gray-700 " + (m.route.get() === '/dashboard' ? " bg-gray-900" : "")
      }, "Dashboard"), m("a", {
        href: "#",
        "class": "px-3 py-2 rounded-md text-md mr-2 font-medium text-white focus:outline-none focus:text-white focus:bg-gray-700 " + (m.route.get() === '/campaigns' ? " bg-gray-900" : ""),
        onclick: function onclick(e) {
          m.route.set('/campaigns');
          e.preventDefault();
        }
      }, "Campaigns"), m("a", {
        href: "#",
        "class": "px-3 py-2 rounded-md text-md font-medium text-white focus:outline-none focus:text-white focus:bg-gray-700 " + (m.route.get() === '/plans' ? " bg-gray-900" : ""),
        onclick: function onclick(e) {
          m.route.set('/plans');
          e.preventDefault();
        }
      }, "Plans")))), m("div", {
        "class": "flex items-center text-right mt-2 lg:mt-0"
      }, m.route.get() === '/campaigns' ? m("div", {
        "class": "flex items-center mt-2 text-right lg:mt-0"
      }, m("div", {
        "class": "mr-4"
      }, m("span", {
        "class": "rounded-md shadow-sm"
      }, m("button", {
        type: "button",
        "class": "inline-flex justify-center w-full px-4 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo",
        onclick: function onclick() {
          Campaign["a" /* default */].newCampaign();
        }
      }, m("svg", {
        "class": "w-5 h-5 mr-2",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 24 24",
        "stroke-width": "2"
      }, m("path", {
        d: "M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"
      })), "Add a Campaign")))) : "", m.route.get() === '/plans' ? m("div", {
        "class": "flex items-center mt-2 text-right lg:mt-0"
      }, m("div", {
        "class": "mr-4"
      }, m("span", {
        "class": "rounded-md shadow-sm"
      }, m("button", {
        type: "button",
        "class": "inline-flex justify-center w-full px-4 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo",
        onclick: function onclick() {
          Commission_Commission.newCommission();
        }
      }, m("svg", {
        "class": "w-5 h-5 mr-2",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 24 24",
        "stroke-width": "2"
      }, m("path", {
        d: "M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"
      })), "Add a Plan")))) : "", m.route.get() === '/dashboard' ? m("div", {
        "class": "flex items-center px-1 text-sm text-gray-400 bg-gray-900 rounded-md lg:mx-2 xl:mx-2"
      }, m("div", {
        "class": "flex lg:ml-0"
      }, m("label", {
        "for": "search_field",
        "class": "sr-only"
      }, "Search"), m("div", {
        "class": "relative w-full"
      }, m("div", {
        "class": "absolute inset-y-0 left-0 flex items-center pointer-events-none"
      }, m("svg", {
        "class": "w-5 h-5 ml-1",
        fill: "currentColor",
        viewBox: "0 0 20 20"
      }, m("path", {
        "fill-rule": "evenodd",
        "clip-rule": "evenodd",
        d: "M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
      }))), m("input", {
        onfocusin: function onfocusin() {
          document.getElementById("afwc-search-panel").classList.remove('hidden');
        },
        oninput: function oninput(event) {
          _this.setSearchTerm(event.target.value);
        },
        id: "afwc-search-field",
        "class": "py-1 pr-3 text-xs bg-transparent border-transparent form-input pl-7 focus:bg-white focus:border-gray-200",
        placeholder: "Search"
      }))), m("div", {
        "class": "flex items-center text-gray-400 text-sm bg-gray-900 lg:mx-2 xl:mx-3 rounded-md"
      }, m("div", {
        "class": "flex items-center"
      }, m("input", {
        id: "start-date",
        type: "date",
        value: DashboardModel_DashboardModel.data.start_date,
        "class": "form-input w-34 sm:leading-5 py-1 px-1 text-xs bg-transparent border-transparent focus:bg-white focus:border-gray-200",
        placeholder: "start date",
        onchange: function onchange() {
          _this.refreshRoute();
        }
      }), m("span", {
        "class": "px-1 sm:leading-5 pointer-events-none"
      }, "to"), m("input", {
        id: "end-date",
        type: "date",
        value: DashboardModel_DashboardModel.data.end_date,
        "class": "form-input w-34 sm:leading-5 py-1 px-1 text-xs bg-transparent border-transparent focus:bg-white focus:border-gray-200",
        placeholder: "end date",
        onchange: function onchange() {
          _this.refreshRoute();
        }
      })))) : "", m("div", {
        "class": "flex items-center ml-4 lg:ml-2"
      }, m("a", {
        href: "https://docs.woocommerce.com/document/affiliate-for-woocommerce/",
        target: "_blank",
        title: "Docs",
        "class": "p-1 ml-3 border-transparent text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out"
      }, m("svg", {
        "class": "h-6 w-6",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 24 24"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
      }))), m("a", {
        href: afwcDashboardParams.settingsLink,
        target: "_blank",
        title: "Settings",
        "class": "ml-3 p-1 border-transparent text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out"
      }, m("svg", {
        "class": "h-6 w-6",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 24 24"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
      }), m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M15 12a3 3 0 11-6 0 3 3 0 016 0z"
      }))))))), m(SearchBox_SearchBox, null), Loader["a" /* default */].showLoader ? m(Loader["a" /* default */], null) : null, m(Feedback_AFWCFeedback, null));
    }
  }]);

  return AFWNavBar;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/KPI.js
function KPI_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function KPI_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function KPI_createClass(Constructor, protoProps, staticProps) { if (protoProps) KPI_defineProperties(Constructor.prototype, protoProps); if (staticProps) KPI_defineProperties(Constructor, staticProps); return Constructor; }



var KPI_AFWKPI = /*#__PURE__*/function () {
  function AFWKPI() {
    KPI_classCallCheck(this, AFWKPI);
  }

  KPI_createClass(AFWKPI, [{
    key: "view",
    value: function view() {
      return m("header", {
        "class": "bg-white shadow"
      }, m("div", {
        "class": "max-w-7xl mx-auto py-8 sm:px-3 xl:px-8"
      }, m("section", {
        "class": "md:flex md:items-center md:justify-between"
      }, m("div", {
        "class": "flex sm:items-top"
      }, m("div", {
        "class": "font-bold leading-tight text-gray-900"
      }, m("p", {
        "class": "text-5xl leading-none font-bold text-indigo-700 flex items-center "
      }, m("span", {
        "class": "text-2xl font-normal mr-1"
      }, afwcDashboardParams.currencySymbol), " ", DashboardModel_DashboardModel.data.kpi.net_affiliates_sales, " ", m("span", {
        "class": "text-2xl font-medium ml-1"
      })), m("p", {
        "class": "mt-1 leading-tight font-medium text-gray-500"
      }, DashboardModel_DashboardModel.data.kpi.percent_of_total_sales, "% of Total Revenue"))), m("div", {
        "class": "flex sm:items-center text-gray-400"
      }, m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-bold text-gray-700"
      }, DashboardModel_DashboardModel.data.affiliates.length), m("p", {
        "class": "mt-1 leading-6 font-medium text-gray-500"
      }, "Affiliates")), m("svg", {
        "class": "fill-current w-3 h-3 mx-4",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      })), m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-bold text-gray-700"
      }, DashboardModel_DashboardModel.data.kpi.visitors_count), m("p", {
        "class": "mt-1 leading-6 font-medium text-gray-500"
      }, "Visitors")), m("svg", {
        "class": "fill-current w-3 h-3 mx-4",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      })), m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-bold text-gray-700"
      }, DashboardModel_DashboardModel.data.kpi.all_customers_count), m("p", {
        "class": "mt-1 leading-6 font-medium text-gray-500"
      }, "Customers")), m("svg", {
        "class": "fill-current w-3 h-3 mx-4",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      })), m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-bold text-gray-700"
      }, DashboardModel_DashboardModel.data.kpi.conversion_rate, "%"), m("p", {
        "class": "mt-1 leading-6 font-medium text-gray-500"
      }, "Conversion Rate"))))));
    }
  }]);

  return AFWKPI;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/PendingPayouts.js
function PendingPayouts_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function PendingPayouts_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function PendingPayouts_createClass(Constructor, protoProps, staticProps) { if (protoProps) PendingPayouts_defineProperties(Constructor.prototype, protoProps); if (staticProps) PendingPayouts_defineProperties(Constructor, staticProps); return Constructor; }



var PendingPayouts_PendingPayouts = /*#__PURE__*/function () {
  function PendingPayouts() {
    PendingPayouts_classCallCheck(this, PendingPayouts);
  }

  PendingPayouts_createClass(PendingPayouts, [{
    key: "view",
    value: function view() {
      return m("div", {
        "class": "my-3 mx-3 py-2 rounded-md"
      }, m("div", {
        "class": "flex items-center"
      }, m("div", {
        "class": "flex-shrink-0 flex items-center justify-center h-6 w-6 rounded-full bg-teal-50 text-teal-500"
      }, m("svg", {
        "class": "h-4 w-4",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 24 24"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
      }))), m("h3", {
        "class": "ml-1 text-base leading-6 font-medium text-teal-500"
      }, "Pending Payouts")), m("div", {
        "class": "mt-2"
      }, m("p", {
        "class": "text-sm leading-5 text-gray-400"
      }, DashboardModel_DashboardModel.data.kpi.unpaid_affiliates, " ", parseInt(DashboardModel_DashboardModel.data.kpi.unpaid_affiliates) === 1 ? "affiliate" : "affiliates", " needs to be paid total commission of ", afwcDashboardParams.currencySymbol, DashboardModel_DashboardModel.data.kpi.unpaid_commissions, ".")));
    }
  }]);

  return PendingPayouts;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/AffiliateList.js
function AffiliateList_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function AffiliateList_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function AffiliateList_createClass(Constructor, protoProps, staticProps) { if (protoProps) AffiliateList_defineProperties(Constructor.prototype, protoProps); if (staticProps) AffiliateList_defineProperties(Constructor, staticProps); return Constructor; }



var AffiliateList_List = /*#__PURE__*/function () {
  function List() {
    AffiliateList_classCallCheck(this, List);
  }

  AffiliateList_createClass(List, [{
    key: "view",
    value: function view() {
      return m("nav", {
        "class": "py-3 text-gray-500 max-h-screen overflow-auto"
      }, m("ul", null, DashboardModel_DashboardModel.data.affiliates.map(function (item, key) {
        return m("li", {
          "class": (0 === key ? "rounded-tl-lg " : DashboardModel_DashboardModel.data.affiliates.length - 1 === key ? "rounded-bl-lg " : "") + "border-b border-gray-200 bg-gray-50 overflow-hidden",
          onclick: function onclick() {
            DashboardModel_DashboardModel.data.currentAffiliateID = item.affiliate_id;
          }
        }, m("a", {
          href: "#",
          "class": "block py-3 hover:bg-white focus:outline-none focus:bg-white transition duration-150 ease-in-out" + (DashboardModel_DashboardModel.data.currentAffiliateID == item.affiliate_id ? " bg-white outline-none" : "")
        }, m("div", {
          "class": "flex items-center pl-4 py-2"
        }, m("div", {
          "class": (item.pending ? "text-red-400 " : DashboardModel_DashboardModel.data.currentAffiliateID == item.affiliate_id ? "text-gray-700 " : "") + "font-medium pr-4 flex-1 sm:truncate"
        }, item.name), m("div", {
          "class": (item.pending ? "text-red-400 " : parseFloat(item.earned_commissions) > 0 ? "text-teal-500 " : "text-gray-500") + "text-sm leading-none numeric"
        }, afwcDashboardParams.currencySymbol, item.earned_commissions), m("div", {
          "class": "mx-2"
        }, m("svg", {
          "class": "h-5 w-5 text-gray-400",
          fill: "currentColor",
          viewBox: "0 0 20 20"
        }, m("path", {
          "fill-rule": "evenodd",
          d: "M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z",
          "clip-rule": "evenodd"
        }))))));
      })));
    }
  }]);

  return List;
}();


// CONCATENATED MODULE: ./src/admin/models/affiliate/Affiliate.js
function Affiliate_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Affiliate_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Affiliate_createClass(Constructor, protoProps, staticProps) { if (protoProps) Affiliate_defineProperties(Constructor.prototype, protoProps); if (staticProps) Affiliate_defineProperties(Constructor, staticProps); return Constructor; }

var Affiliate = /*#__PURE__*/function () {
  function Affiliate(urlParams) {
    Affiliate_classCallCheck(this, Affiliate);

    Affiliate.initialize();
    this.params = urlParams || {};
    this.fetch();
  }

  Affiliate_createClass(Affiliate, [{
    key: "fetch",
    value: function fetch() {
      var _this = this;

      var data = new FormData(),
          requestData = {
        cmd: 'affiliate_details',
        security: afwcDashboardParams.security,
        from_date: this.params.startDate,
        to_date: this.params.endDate,
        affiliate_id: this.params.id
      };

      for (var key in requestData) {
        data.append(key, requestData[key]);
      }

      m.request({
        method: 'POST',
        url: ajaxurl,
        params: {
          action: 'afwc_dashboard_controller'
        },
        body: data,
        withCredentials: false,
        responseType: "json"
      }).then(function (data) {
        return _this.extractData(data);
      });
    }
  }, {
    key: "extractData",
    value: function extractData(data) {
      Affiliate.details = {
        name: data.name || '',
        email: data.email || '',
        affiliateId: data.affiliate_id || 0,
        editURL: data.edit_url || '',
        referralURL: data.referral_url || '',
        paypalEmail: data.paypal_email || '',
        avatarURL: data.avatar_url || '',
        lastPayoutDetails: data.last_payout_details || {},
        formattedJoinDuration: data.formatted_join_duration || '',
        stats: data.stats || {
          current: {},
          allTime: {}
        },
        orders: data.orders_details || [],
        payouts: data.payout_history || [],
        tags: data.tags || {},
        coupons: data.coupons || {},
        commission: data.commission || {},
        products: data.top_products || {},
        isReferralCouponEnable: data.is_referral_coupon_enable || ''
      };

      if (data.pending) {
        Affiliate.details.pending = data.pending;
      }
    }
  }], [{
    key: "initialize",
    value: function initialize() {
      Affiliate.details = {
        name: '',
        email: '',
        affiliateId: 0,
        editURL: '',
        referralURL: '',
        paypalEmail: '',
        avatarURL: '',
        lastPayoutDetails: {},
        formattedJoinDuration: '',
        stats: {
          current: {},
          allTime: {}
        },
        orders: [],
        payouts: [],
        tags: {},
        coupons: {},
        commission: {},
        products: {},
        isReferralCouponEnable: ''
      };
    }
  }, {
    key: "details",
    get: function get() {
      return Affiliate._details;
    },
    set: function set(v) {
      Affiliate._details = v;
    }
  }]);

  return Affiliate;
}();


Affiliate.initialize();
// CONCATENATED MODULE: ./src/admin/views/affiliate/affiliate-details/Header.js
function Header_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Header_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Header_createClass(Constructor, protoProps, staticProps) { if (protoProps) Header_defineProperties(Constructor.prototype, protoProps); if (staticProps) Header_defineProperties(Constructor, staticProps); return Constructor; }



var Header_DetailsHeader = /*#__PURE__*/function () {
  function DetailsHeader() {
    Header_classCallCheck(this, DetailsHeader);

    DetailsHeader.isOrdersTab = true;
    this.tabCss = "whitespace-no-wrap py-4 px-1 border-b-2 font-medium text-sm text-gray-400 leading-5 focus:outline-none focus:text-indigo-800 focus:border-indigo-700";
  }

  Header_createClass(DetailsHeader, [{
    key: "view",
    value: function view() {
      return m("header", {
        "class": "flex items-center justify-between mb-2 border-b border-gray-200 -mx-6 -my-6 px-6 pt-6"
      }, m("div", {
        "class": "flex-shrink-0 mb-2"
      }, m("img", {
        "class": "h-12 w-12 rounded-full",
        src: Affiliate.details.avatarURL,
        alt: ""
      })), m("div", {
        "class": "flex-1 ml-3 mb-2"
      }, m("div", {
        "class": "flex items-center"
      }, m("h3", {
        "class": "text-l block font-bold leading-6 text-gray-900 sm:py-2 sm:text-3xl sm:leading-6 sm:truncate"
      }, Affiliate.details.name), m("span", {
        "class": Affiliate.details.pending ? "invisible " : "visible " + "hidden xl:inline-flex ml-3 px-2 text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-500"
      }, "Unpaid Commission: ", afwcDashboardParams.currencySymbol, Affiliate.details.stats.current.unpaid_commissions || '0.00'))), m("div", {
        "class": Affiliate.details.pending ? "invisible " : "visible " + "flex mx-10"
      }, m("div", {
        "class": "sm:hidden"
      }, m("select", {
        "aria-label": "Selected tab",
        "class": "mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150"
      }, m("option", {
        selected: ""
      }, "Orders"), m("option", null, "Payouts"))), m("div", {
        "class": "hidden sm:block"
      }, m("div", {
        "class": ""
      }, m("nav", {
        "class": "-mb-px flex"
      }, m("a", {
        href: "#",
        "class": DetailsHeader.isOrdersTab ? this.tabCss + " text-indigo-600 border-indigo-400" : this.tabCss + " border-transparent",
        onclick: function onclick() {
          DetailsHeader.isOrdersTab = true;
        }
      }, "Orders"), m("a", {
        href: "#",
        "class": (DetailsHeader.isOrdersTab ? this.tabCss + " border-transparent" : this.tabCss + " text-indigo-600 border-indigo-400") + " ml-8",
        onclick: function onclick() {
          DetailsHeader.isOrdersTab = false;
        }
      }, "Payouts"))))), m("div", {
        "class": "flex"
      }, m("a", {
        href: "mailto:" + Affiliate.details.email,
        target: "_blank",
        title: "Email Affiliate",
        "class": "p-1 border-2 border-transparent text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out"
      }, m("svg", {
        fill: "none",
        viewBox: "0 0 24 24",
        stroke: "currentColor",
        "class": "w-6 h-6"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
      }))), m("a", {
        href: Affiliate.details.editURL,
        target: "_blank",
        title: "Manage Affiliate",
        "class": "ml-3 p-1 border-2 border-transparent text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out"
      }, m("svg", {
        "class": "h-6 w-6",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 24 24"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
      }), m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M15 12a3 3 0 11-6 0 3 3 0 016 0z"
      })))));
    }
  }], [{
    key: "isOrdersTab",
    get: function get() {
      return DetailsHeader._isOrdersTab;
    },
    set: function set(v) {
      DetailsHeader._isOrdersTab = v;
    }
  }]);

  return DetailsHeader;
}();


Header_DetailsHeader.isOrdersTab = true;
// CONCATENATED MODULE: ./src/admin/views/affiliate/affiliate-details/KPI.js
function affiliate_details_KPI_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function affiliate_details_KPI_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function affiliate_details_KPI_createClass(Constructor, protoProps, staticProps) { if (protoProps) affiliate_details_KPI_defineProperties(Constructor.prototype, protoProps); if (staticProps) affiliate_details_KPI_defineProperties(Constructor, staticProps); return Constructor; }



var KPI_DetailsKPI = /*#__PURE__*/function () {
  affiliate_details_KPI_createClass(DetailsKPI, [{
    key: "initialize",
    value: function initialize() {
      this.revenue = Affiliate.details.stats.current.net_affiliates_sales || '0.00';
      this.commission = Affiliate.details.stats.current.earned_commissions || '0.00';
    }
  }]);

  function DetailsKPI() {
    affiliate_details_KPI_classCallCheck(this, DetailsKPI);

    this.initialize();
  }

  affiliate_details_KPI_createClass(DetailsKPI, [{
    key: "view",
    value: function view() {
      this.initialize();
      return m("section", {
        "class": "flex flex-col lg:flex-row lg:items-center lg:justify-between text-sm py-4 my-6 text-gray-800"
      }, m("div", {
        "class": "flex items-center justify-between"
      }, m("div", {
        "class": "mr-3"
      }, m("p", {
        "class": "text-2xl leading-none font-medium"
      }, Affiliate.details.stats.current.visitors_count || 0), m("p", {
        "class": "mt-1 leading-6 text-gray-400"
      }, "Visitors")), m("svg", {
        "class": "text-gray-300 fill-current w-3 h-3 mx-3",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      })), m("div", {
        "class": "ml-3"
      }, m("p", {
        "class": "text-2xl leading-none font-medium"
      }, Affiliate.details.stats.current.customers_count || 0), m("p", {
        "class": "mt-1 leading-6 text-gray-400"
      }, "Customers")), m("svg", {
        "class": "text-gray-300 fill-current w-3 h-3 mx-3",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      })), m("div", {
        "class": "ml-3"
      }, m("p", {
        "class": "text-2xl leading-none font-medium"
      }, Affiliate.details.stats.current.conversion_rate || 0, "%"), m("p", {
        "class": "mt-1 leading-6 text-gray-400"
      }, "Conversion")), m("svg", {
        "class": "hidden lg:inline-block text-gray-300 fill-current w-3 h-3 mx-3",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      }))), m("div", {
        "class": "flex items-center justify-between mt-6 lg:mt-0"
      }, m("div", {
        "class": "flex sm:items-center"
      }, m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-medium flex items-center"
      }, m("span", {
        "class": "text-sm font-normal text-gray-600 mr-1"
      }, afwcDashboardParams.currencySymbol), " ", this.revenue.split(".")[0], " ", m("span", {
        "class": "text-sm"
      }, ".", this.revenue.split(".")[1])), m("p", {
        "class": "mt-1 leading-6 text-gray-400"
      }, "Revenue"))), m("svg", {
        "class": "text-gray-300 fill-current w-3 h-3 mx-3",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      })), m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-medium flex items-center"
      }, m("span", {
        "class": "text-sm font-normal text-gray-600 mr-1"
      }, afwcDashboardParams.currencySymbol), " ", this.commission.split(".")[0], " ", m("span", {
        "class": "text-sm"
      }, ".", this.commission.split(".")[1])), m("p", {
        "class": "mt-1 leading-6 text-gray-400"
      }, "Commission"))));
    }
  }]);

  return DetailsKPI;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/affiliate-details/OtherDetails.js
function OtherDetails_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function OtherDetails_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function OtherDetails_createClass(Constructor, protoProps, staticProps) { if (protoProps) OtherDetails_defineProperties(Constructor.prototype, protoProps); if (staticProps) OtherDetails_defineProperties(Constructor, staticProps); return Constructor; }



var OtherDetails_OtherDetails = /*#__PURE__*/function () {
  function OtherDetails() {
    OtherDetails_classCallCheck(this, OtherDetails);
  }

  OtherDetails_createClass(OtherDetails, [{
    key: "view",
    value: function view(vnode) {
      return m("div", {
        "class": "items-center justify-between"
      }, m("div", {
        "class": "flex"
      }, m("h4", {
        "class": "mb-2 text-sm font-medium text-gray-700 uppercase"
      }, "Other Details"), m("a", {
        href: Affiliate.details.editURL,
        target: "_blank",
        title: "Manage Affiliate",
        "class": "ml-3 border-2 border-transparent text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out"
      }, m("svg", {
        fill: "none",
        viewBox: "0 0 24 24",
        stroke: "currentColor",
        "class": "w-4 h-4"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
      })))), m("div", {
        "class": "overflow-hidden"
      }, m("table", {
        "class": "min-w-full"
      }, m("tbody", {
        "class": "bg-white"
      }, m("tr", null, m("td", {
        "class": "px-4 py-3 text-sm font-medium leading-5 text-gray-600 whitespace-no-wrap"
      }, "Link"), m("td", {
        "class": "px-4 py-3 text-sm leading-5 text-right text-gray-500 whitespace-no-wrap numeric"
      }, m("div", {
        "class": "mt-1 text-xs text-right text-gray-500"
      }, m("span", null, m("code", null, Affiliate.details.referralURL))))), Affiliate.details.isReferralCouponEnable == 'yes' ? m("tr", null, m("td", {
        "class": "px-4 py-3 text-sm font-medium leading-5 text-gray-600 whitespace-no-wrap"
      }, "Referral Coupon"), m("td", {
        "class": "px-4 py-3 text-sm leading-5 text-right text-gray-500 whitespace-no-wrap numeric"
      }, Object.keys(Affiliate.details.coupons).length > 0 ? Object.keys(Affiliate.details.coupons).map(function (key, id) {
        return m("span", {
          "class": "m-1 px-2 py-0.5 font-mono"
        }, Affiliate.details.coupons[key]);
      }) : "No coupons")) : '', m("tr", null, m("td", {
        "class": "px-4 py-3 text-sm font-medium leading-5 text-gray-600 whitespace-no-wrap"
      }, "Tags"), m("td", {
        "class": "flex flex-wrap items-center justify-end px-4 py-3 text-sm leading-5 text-right text-gray-500 whitespace-no-wrap"
      }, Object.keys(Affiliate.details.tags).length > 0 ? Object.keys(Affiliate.details.tags).map(function (key, id) {
        return m("span", {
          "class": "m-1 px-3 py-0.5 rounded-full bg-gray-50"
        }, Affiliate.details.tags[key]);
      }) : 'No tags assigned'))))));
    }
  }]);

  return OtherDetails;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/affiliate-details/TopProducts.js
function TopProducts_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function TopProducts_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function TopProducts_createClass(Constructor, protoProps, staticProps) { if (protoProps) TopProducts_defineProperties(Constructor.prototype, protoProps); if (staticProps) TopProducts_defineProperties(Constructor, staticProps); return Constructor; }



var TopProducts_TopProducts = /*#__PURE__*/function () {
  function TopProducts() {
    TopProducts_classCallCheck(this, TopProducts);
  }

  TopProducts_createClass(TopProducts, [{
    key: "view",
    value: function view(vnode) {
      return m("div", {
        "class": "mr-4 w-1/2 items-center justify-between mt-6 lg:mt-0"
      }, m("h4", {
        "class": "flex-1 mb-2 text-sm font-medium text-gray-700 uppercase"
      }, "Top Products"), m("div", {
        "class": "overflow-hidden"
      }, m("table", {
        "class": "min-w-full"
      }, m("tbody", {
        "class": "bg-white"
      }, Object.keys(Affiliate.details.products).length > 0 && Object.keys(Affiliate.details.products.rows).length > 0 ? Object.keys(Affiliate.details.products.rows).map(function (key, id) {
        return m("tr", null, m("td", {
          "class": "px-6 py-3 text-sm font-medium leading-5 text-gray-600 whitespace-no-wrap border-b border-gray-200"
        }, Affiliate.details.products.rows[key].product), m("td", {
          "class": "px-6 py-3 text-sm leading-5 text-right text-gray-500 whitespace-no-wrap border-b border-gray-200 numeric"
        }, afwcDashboardParams.currencySymbol, Affiliate.details.products.rows[key].sales));
      }) : ""))));
    }
  }]);

  return TopProducts;
}();


// CONCATENATED MODULE: ./src/admin/models/affiliate/Orders.js
function Orders_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Orders_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Orders_createClass(Constructor, protoProps, staticProps) { if (protoProps) Orders_defineProperties(Constructor.prototype, protoProps); if (staticProps) Orders_defineProperties(Constructor, staticProps); return Constructor; }




var Orders_Orders = /*#__PURE__*/function () {
  function Orders(urlParams) {
    Orders_classCallCheck(this, Orders);

    Orders.initialize();
    this.params = urlParams || {};
  }

  Orders_createClass(Orders, [{
    key: "fetch",
    value: function fetch() {
      var data = new FormData(),
          requestData = {
        cmd: 'order_details',
        security: afwcDashboardParams.security,
        from_date: this.params.startDate,
        to_date: this.params.endDate,
        affiliate_id: this.params.id,
        page: this.params.currentPage
      };

      for (var key in requestData) {
        data.append(key, requestData[key]);
      }

      m.request({
        method: 'POST',
        url: ajaxurl,
        params: {
          action: 'afwc_dashboard_controller'
        },
        body: data,
        withCredentials: false,
        responseType: "json"
      }).then(function (data) {
        if (data.length > 0) {
          Orders.flags.moreOrdersLoaded = true;
          Affiliate.details.orders = Affiliate.details.orders.concat(data);
        } else {
          Orders.flags.moreOrdersLoaded = false;
          Orders.flags.loadMore = false;
        }
      });
    }
  }, {
    key: "updateCommissionStatus",
    value: function updateCommissionStatus() {
      var _this = this;

      var data = new FormData(),
          requestData = {
        cmd: 'update_commission_status',
        security: afwcDashboardParams.security,
        order_ids: JSON.stringify(this.params.ids),
        status: this.params.status
      };

      for (var key in requestData) {
        data.append(key, requestData[key]);
      }

      m.request({
        method: 'POST',
        url: ajaxurl,
        params: {
          action: 'afwc_dashboard_controller'
        },
        body: data,
        withCredentials: false,
        responseType: "json"
      }).then(function (resp) {
        if ("Success" === resp.ACK) {
          var unpaidCommission = Number(parseFloat(Affiliate.details.stats.current.unpaid_commissions || 0).toFixed(2)),
              allTimeUnpaidCommission = Number(parseFloat(Affiliate.details.stats.allTime.unpaid_commissions || 0).toFixed(2)),
              allTimePaidCommission = Number(parseFloat(Affiliate.details.stats.allTime.paid_commissions || 0).toFixed(2)),
              totalUnpaidCommission = Number(parseFloat(DashboardModel_DashboardModel.data.kpi.unpaid_commissions || 0).toFixed(2)),
              currentCommission = 0;
          Affiliate.details.orders.forEach(function (order, index) {
            if (_this.params.ids.indexOf(order.order_id) !== -1) {
              currentCommission = Number(parseFloat(Affiliate.details.orders[index].commission || 0).toFixed(2));

              if ("unpaid" === _this.params.status && "unpaid" !== Affiliate.details.orders[index].status) {
                Affiliate.details.stats.current.unpaid_commissions = Number(parseFloat(unpaidCommission + currentCommission).toFixed(2));
                Affiliate.details.stats.allTime.unpaid_commissions = Number(parseFloat(allTimeUnpaidCommission + currentCommission).toFixed(2));
                DashboardModel_DashboardModel.data.kpi.unpaid_commissions = Number(parseFloat(totalUnpaidCommission + currentCommission).toFixed(2));
              } else if ("unpaid" !== _this.params.status && "unpaid" === Affiliate.details.orders[index].status) {
                Affiliate.details.stats.current.unpaid_commissions = unpaidCommission > 0 ? Number(parseFloat(unpaidCommission - currentCommission).toFixed(2)) : unpaidCommission;
                Affiliate.details.stats.allTime.unpaid_commissions = allTimeUnpaidCommission > 0 ? Number(parseFloat(allTimeUnpaidCommission - currentCommission).toFixed(2)) : allTimeUnpaidCommission;
                DashboardModel_DashboardModel.data.kpi.unpaid_commissions = totalUnpaidCommission > 0 ? Number(parseFloat(totalUnpaidCommission - currentCommission).toFixed(2)) : totalUnpaidCommission;
              }

              if ("paid" === _this.params.status && "paid" !== Affiliate.details.orders[index].status) {
                Affiliate.details.stats.allTime.paid_commissions = Number(parseFloat(allTimePaidCommission + currentCommission).toFixed(2));
              } else if ("paid" !== _this.params.status && "paid" === Affiliate.details.orders[index].status) {
                Affiliate.details.stats.allTime.paid_commissions = allTimePaidCommission > 0 ? Number(parseFloat(allTimePaidCommission - currentCommission).toFixed(2)) : allTimePaidCommission;
              }

              Affiliate.details.orders[index].status = _this.params.status;
            }
          });
          Affiliate.details.stats.current.unpaid_commissions = parseFloat(Affiliate.details.stats.current.unpaid_commissions).toFixed(2);
          Affiliate.details.stats.allTime.unpaid_commissions = parseFloat(Affiliate.details.stats.allTime.unpaid_commissions).toFixed(2);
          Affiliate.details.stats.allTime.paid_commissions = parseFloat(Affiliate.details.stats.allTime.paid_commissions).toFixed(2);
          DashboardModel_DashboardModel.data.kpi.unpaid_commissions = parseFloat(DashboardModel_DashboardModel.data.kpi.unpaid_commissions).toFixed(2);
          Orders.flags.clearSelections = true;
        }
      });
    }
  }], [{
    key: "initialize",
    value: function initialize() {
      Orders.flags = {
        loadMore: true,
        clearSelections: false,
        moreOrdersLoaded: false
      };
    }
  }, {
    key: "flags",
    get: function get() {
      return Orders._flags;
    },
    set: function set(v) {
      Orders._flags = v;
    }
  }]);

  return Orders;
}();


Orders_Orders.initialize();
// CONCATENATED MODULE: ./src/admin/views/affiliate/affiliate-details/order/Table.js
function Table_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Table_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Table_createClass(Constructor, protoProps, staticProps) { if (protoProps) Table_defineProperties(Constructor.prototype, protoProps); if (staticProps) Table_defineProperties(Constructor, staticProps); return Constructor; }




var Table_OrderTable = /*#__PURE__*/function () {
  function OrderTable() {
    Table_classCallCheck(this, OrderTable);

    OrderTable.initialize();
  }

  Table_createClass(OrderTable, [{
    key: "view",
    value: function view() {
      return m("table", {
        "class": "min-w-full"
      }, m("thead", null, m("tr", null, m("th", {
        "class": "pl-6 pr-0 py-3 border-b border-gray-200 bg-gray-50 w-6 flex-0"
      }, m("input", {
        id: "select[0]",
        checked: OrderTable.selectedOrders.selectAll,
        type: "checkbox",
        "class": "form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out",
        onclick: function onclick(event) {
          OrderTable.handleRowSelection(Affiliate.details.orders, event.target.checked);
        }
      })), m("th", {
        "class": "px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
      }, "Date"), m("th", {
        "class": "px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
      }, "Order"), m("th", {
        "class": "px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
      }, "Commission"), m("th", {
        "class": "hidden xl:table-cell px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
      }, "Customer"), m("th", {
        "class": "px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase text-right"
      }, "Medium"))), m("tbody", {
        "class": "bg-white"
      }, Affiliate.details.orders.map(function (order) {
        return m("tr", null, m("td", {
          "class": "pl-6 pr-0 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-800 w-6 flex-0"
        }, m("input", {
          id: "select[12]",
          checked: OrderTable.selectedOrders.ids.hasOwnProperty(order.order_id),
          type: "checkbox",
          "class": "form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out",
          onclick: function onclick(event) {
            OrderTable.handleRowSelection(order, event.target.checked);
          }
        })), m("td", {
          "class": "px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"
        }, m("time", {
          datetime: order.datetime
        }, order.datetime)), m("td", {
          "class": "px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-800"
        }, m("div", {
          "class": "flex items-center"
        }, m("a", {
          href: order.order_url,
          target: "_blank",
          "class": "text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline numeric"
        }, "#", order.order_id), m("span", {
          "class": "ml-3 text-gray-600"
        }, order.order_status), m("span", {
          "class": "hidden xl:inline-block ml-3"
        }, order.currency, order.order_total))), m("td", {
          "class": "px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"
        }, m("span", {
          "class": ("rejected" === order.status ? "line-through " : "") + "font-medium"
        }, order.currency, order.commission), m("span", {
          "class": ("rejected" === order.status ? "text-red-400" : "unpaid" === order.status ? "text-teal-400" : "text-gray-400") + " ml-3"
        }, order.status.charAt(0).toUpperCase() + order.status.slice(1))), m("td", {
          "class": "hidden xl:table-cell px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500 truncate"
        }, m("a", {
          href: order.customer_orders_url,
          target: "_blank",
          "class": "text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline numeric"
        }, order.billing_name)), m("td", {
          "class": "px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-xs uppercase leading-5 text-gray-500 text-right"
        }, order.referral_type));
      })));
    }
  }], [{
    key: "initialize",
    value: function initialize() {
      OrderTable.selectedOrders = {
        ids: {},
        commission: 0.00,
        unpaidCommission: 0.00,
        selectAll: false
      };
    }
  }, {
    key: "handleRowSelection",
    value: function handleRowSelection(orders, checked) {
      orders = Array.isArray(orders) ? orders : [orders];
      OrderTable.selectedOrders.selectAll = orders.length > 1 && checked ? true : false;
      var commission = Number(parseFloat(OrderTable.selectedOrders.commission || 0).toFixed(2)),
          unpaidCommission = Number(parseFloat(OrderTable.selectedOrders.unpaidCommission || 0).toFixed(2)),
          currentOrderCommission = 0;
      orders.map(function (orderObj) {
        currentOrderCommission = Number(parseFloat(orderObj.commission).toFixed(2));

        if (OrderTable.selectedOrders.ids.hasOwnProperty(orderObj.order_id) && !checked) {
          delete OrderTable.selectedOrders.ids[orderObj.order_id];
          commission = "rejected" !== orderObj.status && commission > 0 ? Number(parseFloat(commission - currentOrderCommission).toFixed(2)) : commission;
          unpaidCommission = "unpaid" === orderObj.status && unpaidCommission > 0 ? Number(parseFloat(unpaidCommission - currentOrderCommission).toFixed(2)) : unpaidCommission;
        } else if (false === OrderTable.selectedOrders.ids.hasOwnProperty(orderObj.order_id) && checked) {
          OrderTable.selectedOrders.ids[orderObj.order_id] = {
            commission: currentOrderCommission,
            pendingPayout: "unpaid" === orderObj.status,
            date: orderObj.datetime
          };
          commission = "rejected" !== orderObj.status ? Number(parseFloat(commission + currentOrderCommission).toFixed(2)) : commission;
          unpaidCommission = "unpaid" === orderObj.status ? Number(parseFloat(unpaidCommission + currentOrderCommission).toFixed(2)) : unpaidCommission;
        }
      });
      OrderTable.selectedOrders.commission = parseFloat(commission).toFixed(2);
      OrderTable.selectedOrders.unpaidCommission = parseFloat(unpaidCommission).toFixed(2);
    }
  }, {
    key: "selectedOrders",
    get: function get() {
      return OrderTable._selectedOrders;
    },
    set: function set(v) {
      OrderTable._selectedOrders = v;
    }
  }]);

  return OrderTable;
}();


Table_OrderTable.initialize();
// CONCATENATED MODULE: ./src/admin/models/affiliate/Payouts.js
function Payouts_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Payouts_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Payouts_createClass(Constructor, protoProps, staticProps) { if (protoProps) Payouts_defineProperties(Constructor.prototype, protoProps); if (staticProps) Payouts_defineProperties(Constructor, staticProps); return Constructor; }





var Payouts_Payouts = /*#__PURE__*/function () {
  Payouts_createClass(Payouts, null, [{
    key: "initialize",
    value: function initialize() {
      Payouts.flags = {
        loadMore: true,
        PayoutProcessed: false
      };
    }
  }]);

  function Payouts(urlParams) {
    Payouts_classCallCheck(this, Payouts);

    Payouts.initialize();
    this.params = urlParams || {};
  }

  Payouts_createClass(Payouts, [{
    key: "fetch",
    value: function fetch() {
      var data = new FormData(),
          requestData = {
        cmd: 'payout_details',
        security: afwcDashboardParams.security,
        from_date: this.params.startDate,
        to_date: this.params.endDate,
        affiliate_id: this.params.id,
        page: this.params.currentPage
      };

      for (var key in requestData) {
        data.append(key, requestData[key]);
      }

      m.request({
        method: 'POST',
        url: ajaxurl,
        params: {
          action: 'afwc_dashboard_controller'
        },
        body: data,
        withCredentials: false,
        responseType: "json"
      }).then(function (data) {
        if (data.length > 0) {
          Affiliate.details.payouts = Affiliate.details.payouts.concat(data);
        } else {
          Payouts.flags.loadMore = false;
        }
      });
    }
  }, {
    key: "processPayout",
    value: function processPayout() {
      var _this = this;

      var data = new FormData(),
          requestData = {
        cmd: 'process_payout',
        security: afwcDashboardParams.security,
        method: this.params.method,
        affiliates: JSON.stringify(this.params.affiliates),
        currency: this.params.currency,
        selected_orders: JSON.stringify(this.params.selectedOrders),
        date: this.params.date,
        note: this.params.note
      };

      for (var key in requestData) {
        data.append(key, requestData[key]);
      }

      m.request({
        method: 'POST',
        url: ajaxurl,
        params: {
          action: 'afwc_dashboard_controller'
        },
        body: data,
        withCredentials: false,
        responseType: "json"
      }).then(function (resp) {
        if ("Success" === resp.ACK && Object.keys(resp.last_added_payout_data).length > 0) {
          var unpaidCommission = Number(parseFloat(Affiliate.details.stats.current.unpaid_commissions || 0).toFixed(2)),
              allTimeUnpaidCommission = Number(parseFloat(Affiliate.details.stats.allTime.unpaid_commissions || 0).toFixed(2)),
              allTimePaidCommission = Number(parseFloat(Affiliate.details.stats.allTime.paid_commissions || 0).toFixed(2)),
              totalUnpaidCommission = Number(parseFloat(DashboardModel_DashboardModel.data.kpi.unpaid_commissions || 0).toFixed(2)),
              currentCommission = resp.last_added_payout_data.hasOwnProperty('amount') ? Number(parseFloat(resp.last_added_payout_data.amount || 0).toFixed(2)) : 0;
          Affiliate.details.stats.current.unpaid_commissions = unpaidCommission > 0 ? Number(parseFloat(unpaidCommission - currentCommission).toFixed(2)) : unpaidCommission;
          Affiliate.details.stats.allTime.unpaid_commissions = allTimeUnpaidCommission > 0 ? Number(parseFloat(allTimeUnpaidCommission - currentCommission).toFixed(2)) : allTimeUnpaidCommission;
          Affiliate.details.stats.allTime.paid_commissions = allTimePaidCommission > 0 ? Number(parseFloat(allTimePaidCommission + currentCommission).toFixed(2)) : allTimePaidCommission;
          DashboardModel_DashboardModel.data.kpi.unpaid_commissions = totalUnpaidCommission > 0 ? Number(parseFloat(totalUnpaidCommission - currentCommission).toFixed(2)) : totalUnpaidCommission;
          Affiliate.details.payouts.unshift(resp.last_added_payout_data);
          Affiliate.details.orders.map(function (order, index) {
            if (_this.params.selectedOrderIds.indexOf(order.order_id) > -1) {
              Affiliate.details.orders[index].status = 'paid';
            }
          });
          Affiliate.details.stats.current.unpaid_commissions = parseFloat(Affiliate.details.stats.current.unpaid_commissions).toFixed(2);
          Affiliate.details.stats.allTime.unpaid_commissions = parseFloat(Affiliate.details.stats.allTime.unpaid_commissions).toFixed(2);
          Affiliate.details.stats.allTime.paid_commissions = parseFloat(Affiliate.details.stats.allTime.paid_commissions).toFixed(2);
          DashboardModel_DashboardModel.data.kpi.unpaid_commissions = parseFloat(DashboardModel_DashboardModel.data.kpi.unpaid_commissions).toFixed(2);
          Payouts.flags.PayoutProcessed = true;
          NotificationModel["a" /* default */].flags.showNotification = 1;
          NotificationModel["a" /* default */].notification.message = "Payout processed successfully";
          NotificationModel["a" /* default */].notification.status = 'success';
        } else {
          Payouts.flags.PayoutProcessed = false;
          NotificationModel["a" /* default */].flags.showNotification = 1;
          NotificationModel["a" /* default */].notification.message = resp.error;
          NotificationModel["a" /* default */].notification.status = 'error'; // alert(resp.error);
        }
      });
    }
  }], [{
    key: "flags",
    get: function get() {
      return Payouts._flags;
    },
    set: function set(v) {
      Payouts._flags = v;
    }
  }]);

  return Payouts;
}();


Payouts_Payouts.initialize();
// CONCATENATED MODULE: ./src/admin/views/affiliate/SendPayout.js
function SendPayout_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function SendPayout_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function SendPayout_createClass(Constructor, protoProps, staticProps) { if (protoProps) SendPayout_defineProperties(Constructor.prototype, protoProps); if (staticProps) SendPayout_defineProperties(Constructor, staticProps); return Constructor; }





var SendPayout_SendPayout = /*#__PURE__*/function () {
  SendPayout_createClass(SendPayout, [{
    key: "initialize",
    value: function initialize(params) {
      this.commission = params.commission || '0.00';
      this.name = params.name || '';
    }
  }], [{
    key: "initializeFlags",
    value: function initializeFlags() {
      SendPayout.flags = {
        show: false,
        payoutMethod: Affiliate.details.paypalEmail != "" ? "paypal" : "paypal-manual"
      };
    }
  }]);

  function SendPayout(vnode) {
    SendPayout_classCallCheck(this, SendPayout);

    SendPayout.initializeFlags();
    this.initialize(vnode.attrs);
  }

  SendPayout_createClass(SendPayout, [{
    key: "onupdate",
    value: function onupdate(vnode) {
      this.initialize(vnode.attrs);
    }
  }, {
    key: "processPayout",
    value: function processPayout(event) {
      event.preventDefault();
      var selectedOrders = [],
          selectedOrderIds = [],
          unpaidCommission = 0.00;
      Object.keys(Table_OrderTable.selectedOrders.ids).map(function (orderId) {
        if (Table_OrderTable.selectedOrders.ids[orderId].pendingPayout) {
          unpaidCommission += parseFloat(Table_OrderTable.selectedOrders.ids[orderId].commission);
          selectedOrderIds.push(orderId);
          selectedOrders.push({
            order_id: orderId,
            commission: Table_OrderTable.selectedOrders.ids[orderId].commission,
            date: Table_OrderTable.selectedOrders.ids[orderId].date
          });
        }
      });
      var params = {
        affiliates: [{
          id: Affiliate.details.affiliateId,
          email: Affiliate.details.paypalEmail,
          amount: unpaidCommission,
          unique_id: 'afwc_mass_payment'
        }],
        selectedOrders: selectedOrders,
        selectedOrderIds: selectedOrderIds,
        method: SendPayout.flags.payoutMethod,
        currency: afwcDashboardParams.currencySymbol,
        date: "date" === event.target[1].type ? event.target[1].value : '',
        note: "textarea" === event.target[1].type ? event.target[1].value : event.target[2].value
      };
      new Payouts_Payouts(params).processPayout();
    }
  }, {
    key: "view",
    value: function view(vnode) {
      if (Payouts_Payouts.flags.PayoutProcessed) {
        SendPayout.initializeFlags();
        this.initialize(vnode.attrs);
        Payouts_Payouts.flags.PayoutProcessed = false;
      }

      return (// x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        m("div", {
          "class": (SendPayout.flags.show ? "visible " : "invisible ") + "modal fixed w-full h-full top-0 left-0 sm:flex sm:items-center sm:justify-center"
        }, m("div", {
          "class": "modal-overlay absolute w-full h-full top-0 left-0 bg-gray-800 opacity-50"
        }), m("div", {
          "x-show": "open",
          "class": "bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl"
        }, m("form", {
          "class": "px-6 py-6 m-auto shadow rounded-lg bg-white border",
          onsubmit: this.processPayout
        }, m("div", null, m("h3", {
          "class": "text-lg leading-6 font-medium text-gray-900"
        }, "Pay ", m("strong", null, afwcDashboardParams.currencySymbol, this.commission), " to ", m("strong", null, this.name)), m("p", {
          "class": "mt-1 max-w-2xl text-sm leading-5 text-gray-500"
        }, "Send outstanding commission and/or record the payout.")), m("div", {
          "class": "mt-6 sm:mt-5"
        }, m("div", {
          "class": "mt-4 sm:mt-3"
        }, m("div", {
          "class": "mt-4 sm:mt-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5"
        }, m("label", {
          "for": "method",
          "class": "block text-sm font-medium leading-5 text-gray-500 sm:mt-px sm:pt-2"
        }, "Payment Method"), m("div", {
          "class": "mt-1 sm:mt-0 sm:col-span-2"
        }, m("div", {
          "class": "max-w-xs rounded-md shadow-sm"
        }, m("select", {
          id: "method",
          "class": "block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5",
          onchange: function onchange(event) {
            SendPayout.flags.payoutMethod = event.target.value;
          }
        }, m("option", {
          value: "paypal",
          disabled: Affiliate.details.paypalEmail != "" ? false : true
        }, "PayPal: Pay & Record"), m("option", {
          value: "paypal-manual"
        }, "PayPal: Record Only"), m("option", {
          value: "other"
        }, "Other"))))), "paypal" !== SendPayout.flags.payoutMethod ? m("div", {
          "class": "sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5"
        }, m("label", {
          "for": "date",
          "class": "block text-sm font-medium leading-5 text-gray-500 sm:mt-px sm:pt-2"
        }, "Record date"), m("div", {
          "class": "mt-1 sm:mt-0 sm:col-span-2"
        }, m("div", {
          "class": "max-w-xs rounded-md shadow-sm"
        }, m("input", {
          type: "date",
          value: new Date().toISOString().slice(0, 10),
          "class": "form-input w-32 sm:leading-5 py-1 text-xs bg-transparent border-transparent focus:bg-white focus:border-gray-200",
          placeholder: "payout date"
        })))) : '', m("div", {
          "class": "mt-4 sm:mt-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5"
        }, m("label", {
          "for": "notes",
          "class": "block text-sm font-medium leading-5 text-gray-500 sm:mt-px sm:pt-2"
        }, "Notes"), m("div", {
          "class": "mt-1 sm:mt-0 sm:col-span-2"
        }, m("div", {
          "class": "max-w-xs rounded-md shadow-sm"
        }, m("textarea", {
          id: "notes",
          rows: "3",
          "class": "form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 p-2"
        })))), m("div", {
          "class": "mt-8 border-t border-gray-200 pt-5"
        }, m("div", {
          "class": "flex justify-end"
        }, m("span", {
          "class": "inline-flex rounded-md shadow-sm"
        }, m("button", {
          type: "button",
          "class": "py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-500 hover:text-gray-700 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out",
          onclick: function onclick() {
            SendPayout.flags.show = false;
          }
        }, "Cancel")), m("span", {
          "class": "ml-3 inline-flex rounded-md shadow-sm"
        }, m("button", {
          type: "submit",
          "class": "inline-flex justify-center py-2 px-6 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
        }, "Send")))))))))
      );
    }
  }], [{
    key: "flags",
    get: function get() {
      return SendPayout._flags;
    },
    set: function set(v) {
      SendPayout._flags = v;
    }
  }]);

  return SendPayout;
}();


SendPayout_SendPayout.initializeFlags();
// CONCATENATED MODULE: ./src/admin/views/affiliate/affiliate-details/order/Details.js
function Details_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Details_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Details_createClass(Constructor, protoProps, staticProps) { if (protoProps) Details_defineProperties(Constructor.prototype, protoProps); if (staticProps) Details_defineProperties(Constructor, staticProps); return Constructor; }








var Details_OrderDetails = /*#__PURE__*/function () {
  function OrderDetails(vnode) {
    Details_classCallCheck(this, OrderDetails);

    this.params = {
      id: vnode.attrs.id || 0,
      startDate: vnode.attrs.startDate || '',
      endDate: vnode.attrs.endDate || '',
      currentPage: 1
    };
  }

  Details_createClass(OrderDetails, [{
    key: "loadOrders",
    value: function loadOrders() {
      this.params.currentPage = ++this.params.currentPage;
      this.model = new Orders_Orders(this.params);
      this.model.fetch();
    }
  }, {
    key: "onupdate",
    value: function onupdate(vnode) {
      var oldId = this.params.id;
      this.params = Object.assign(this.params, vnode.attrs);

      if (oldId != vnode.attrs.id) {
        this.params.currentPage = 1;
      }
    }
  }, {
    key: "updateCommissionStatus",
    value: function updateCommissionStatus() {
      if (Object.keys(Table_OrderTable.selectedOrders.ids).length > 0) {
        var batchAction = document.querySelector('[aria-label="Batch Actions"]');
        new Orders_Orders({
          ids: Object.keys(Table_OrderTable.selectedOrders.ids),
          status: batchAction.value
        }).updateCommissionStatus();
        NotificationModel["a" /* default */].flags.showNotification = 1;
        NotificationModel["a" /* default */].notification.message = "Commission status updated successfully";
        NotificationModel["a" /* default */].notification.status = 'success';
      } else {
        NotificationModel["a" /* default */].flags.showNotification = 1;
        NotificationModel["a" /* default */].notification.message = "Please select a order";
        NotificationModel["a" /* default */].notification.status = 'error';
      }
    }
  }, {
    key: "view",
    value: function view(vnode) {
      var _this = this;

      if (this.params.id != vnode.attrs.id) {
        Table_OrderTable.initialize();
        Orders_Orders.flags.loadMore = true;
      }

      if (Orders_Orders.flags.moreOrdersLoaded && Table_OrderTable.selectedOrders.selectAll) {
        Table_OrderTable.handleRowSelection(Affiliate.details.orders, true);
      }

      if (Orders_Orders.flags.clearSelections) {
        Table_OrderTable.initialize();
        Orders_Orders.flags.clearSelections = false;
      }

      if (Payouts_Payouts.flags.PayoutProcessed) {
        Table_OrderTable.initialize();
      }

      return m("section", {
        "class": "mb-4 mt-0 overflow-x-auto"
      }, m("div", {
        "class": "flex items-center pb-3"
      }, m("div", {
        "class": "ml-3"
      }, m("div", {
        "class": (Table_OrderTable.selectedOrders.unpaidCommission > 0 ? "" : "opacity-50 cursor-not-allowed ") + "cursor-pointer",
        onclick: function onclick() {
          SendPayout_SendPayout.flags.show = Table_OrderTable.selectedOrders.unpaidCommission > 0 ? true : false;
        }
      }, m("a", {
        "class": "px-2 text-sm font-medium leading-6 text-indigo-600 hover:text-indigo-800"
      }, "Send Payout: ", afwcDashboardParams.currencySymbol, Table_OrderTable.selectedOrders.unpaidCommission))), m("h4", {
        "class": "flex-1 text-lg text-gray-700"
      }), m("span", {
        "class": "text-gray-400 mr-4 text-sm"
      }, Object.keys(Table_OrderTable.selectedOrders.ids).length, " ", Object.keys(Table_OrderTable.selectedOrders.ids).length > 1 ? "orders" : 'order', ", ", afwcDashboardParams.currencySymbol, Table_OrderTable.selectedOrders.commission, " commission"), m("select", {
        "aria-label": "Batch Actions",
        "class": "form-select h-full py-2 pl-2 bg-transparent text-gray-500 sm:text-sm sm:leading-5 disabled:opacity-75"
      }, m("option", {
        value: "paid"
      }, "Mark as Paid"), m("option", {
        value: "unpaid"
      }, "Mark as Unpaid"), m("option", {
        value: "rejected"
      }, "Mark as Rejected")), m("div", {
        "class": "rounded-md shadow-sm ml-4"
      }, m("button", {
        type: "submit",
        "class": (Object.keys(Table_OrderTable.selectedOrders.ids).length > 0 ? "" : "opacity-50 cursor-not-allowed ") + "flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray active:bg-gray-700 transition duration-150 ease-in-out disabled:opacity-75",
        onclick: this.updateCommissionStatus
      }, "Update"))), m(SendPayout_SendPayout, {
        commission: Table_OrderTable.selectedOrders.unpaidCommission,
        name: Affiliate.details.name
      }), m("section", {
        "class": "overflow-y-auto",
        style: "height:45vh;"
      }, m(Table_OrderTable, null)), Orders_Orders.flags.loadMore && Affiliate.details.orders.length > 0 && Affiliate.details.orders.length % 5 == 0 ? m("div", {
        "class": "my-3"
      }, m("div", {
        "class": "pb-2 text-center border-b border-gray-200 text-center"
      }, m("a", {
        href: "#",
        "class": "py-2 px-4 text-sm font-medium text-indigo-400 uppercase",
        onclick: function onclick() {
          _this.loadOrders();
        }
      }, "Load More"))) : '');
    }
  }]);

  return OrderDetails;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/affiliate-details/payout/Table.js
function payout_Table_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function payout_Table_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function payout_Table_createClass(Constructor, protoProps, staticProps) { if (protoProps) payout_Table_defineProperties(Constructor.prototype, protoProps); if (staticProps) payout_Table_defineProperties(Constructor, staticProps); return Constructor; }



var Table_PayoutTable = /*#__PURE__*/function () {
  function PayoutTable() {
    payout_Table_classCallCheck(this, PayoutTable);
  }

  payout_Table_createClass(PayoutTable, [{
    key: "view",
    value: function view() {
      return m("table", {
        "class": "min-w-full"
      }, m("thead", null, m("tr", null, m("th", {
        "class": "px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
      }, "Date"), m("th", {
        "class": "px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
      }, "Amount"), m("th", {
        "class": "px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
      }, "Method"), m("th", {
        "class": "px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase text-right"
      }, "Notes"))), m("tbody", {
        "class": "bg-white"
      }, Affiliate.details.payouts.map(function (payout) {
        return m("tr", null, m("td", {
          "class": "px-6 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"
        }, m("time", {
          datetime: payout.datetime
        }, payout.datetime)), m("td", {
          "class": "px-6 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium text-gray-900"
        }, m("div", {
          "class": "flex items-center flex-row-reverse"
        }, m("span", {
          "class": "numeric text-gray-600"
        }, payout.currency, payout.amount), m("span", {
          "class": (payout.hasOwnProperty('order_count') && payout.hasOwnProperty('from_date') && payout.hasOwnProperty('to_date') ? "visible " : "invisible ") + "hidden xl:inline mx-3 text-gray-400 text-xs"
        }, "for ", payout.order_count || '-', " ", payout.order_count > 1 ? "orders" : "order", " from ", payout.from_date || '-', " to ", payout.to_date || '-'))), m("td", {
          "class": "px-6 py-3 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"
        }, payout.method), m("td", {
          "class": "px-6 py-3 whitespace-no-wrap border-b border-gray-200 text-xs leading-5 text-gray-500 text-right"
        }, payout.payout_notes));
      })));
    }
  }]);

  return PayoutTable;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/affiliate-details/payout/Details.js
function payout_Details_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function payout_Details_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function payout_Details_createClass(Constructor, protoProps, staticProps) { if (protoProps) payout_Details_defineProperties(Constructor.prototype, protoProps); if (staticProps) payout_Details_defineProperties(Constructor, staticProps); return Constructor; }





var Details_PayoutDetails = /*#__PURE__*/function () {
  function PayoutDetails(vnode) {
    payout_Details_classCallCheck(this, PayoutDetails);

    this.params = {
      id: vnode.attrs.id || 0,
      startDate: vnode.attrs.startDate || '',
      endDate: vnode.attrs.endDate || '',
      currentPage: 1
    };
  }

  payout_Details_createClass(PayoutDetails, [{
    key: "loadPayouts",
    value: function loadPayouts() {
      this.params.currentPage = ++this.params.currentPage;
      new Payouts_Payouts(this.params).fetch();
    }
  }, {
    key: "onupdate",
    value: function onupdate(vnode) {
      if (this.params.id != vnode.attrs.id) {
        this.params.id = vnode.attrs.id;
        this.params.currentPage = 1;
      }
    }
  }, {
    key: "view",
    value: function view(vnode) {
      var _this = this;

      if (this.params.id != vnode.attrs.id) {
        Payouts_Payouts.flags.loadMore = true;
      }

      this.commisions = Affiliate.details.stats.allTime.earned_commissions || '0.00';
      this.paidCommisions = Affiliate.details.stats.allTime.paid_commissions || '0.00';
      this.unpaidCommisions = Affiliate.details.stats.allTime.unpaid_commissions || '0.00';
      return m("section", null, m("section", {
        "class": "flex items-center justify-between text-sm py-4 my-6 text-gray-800"
      }, m("div", {
        "class": "flex-1 flex items-center justify-between"
      }, m("div", {
        "class": "mr-3"
      }, m("div", {
        "class": "text-2xl leading-none font-medium flex items-center"
      }, m("span", {
        "class": "text-sm font-normal text-gray-600 mr-1"
      }, afwcDashboardParams.currencySymbol), " ", this.commisions.split(".")[0], " ", m("span", {
        "class": "text-sm"
      }, ".", this.commisions.split(".")[1])), m("div", {
        "class": "mt-1 leading-6 text-gray-400"
      }, "Commission (All Time)")), m("div", {
        "class": "ml-3"
      }, m("div", {
        "class": "text-2xl leading-none font-medium flex items-center"
      }, m("span", {
        "class": "text-sm font-normal text-gray-600 mr-1"
      }, afwcDashboardParams.currencySymbol), " ", this.paidCommisions.split(".")[0], " ", m("span", {
        "class": "text-sm"
      }, ".", this.paidCommisions.split(".")[1])), m("div", {
        "class": "mt-1 leading-6 text-gray-400"
      }, "Paid (All Time)")), m("div", {
        "class": "ml-3"
      }, m("div", {
        "class": "text-2xl leading-none font-medium flex items-center"
      }, m("span", {
        "class": "text-sm font-normal text-gray-600 mr-1"
      }, afwcDashboardParams.currencySymbol), " ", this.unpaidCommisions.split(".")[0], " ", m("span", {
        "class": "text-sm"
      }, ".", this.unpaidCommisions.split(".")[1])), m("div", {
        "class": "mt-1 leading-6 text-gray-400"
      }, "Unpaid (All Time)")))), m("section", {
        "class": "my-6"
      }, m("section", {
        "class": "overflow-y-auto",
        style: "height:45vh;"
      }, m(Table_PayoutTable, null)), Payouts_Payouts.flags.loadMore && Affiliate.details.payouts.length > 0 && Affiliate.details.payouts.length % 5 == 0 ? m("div", {
        "class": "my-3"
      }, m("div", {
        "class": "pb-2 text-center border-b border-gray-200 text-center"
      }, m("a", {
        href: "#",
        "class": "py-2 px-4 text-sm font-medium text-indigo-400 uppercase",
        onclick: function onclick() {
          _this.loadPayouts();
        }
      }, "Load More"))) : ''));
    }
  }]);

  return PayoutDetails;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/AffiliateDetail.js
function AffiliateDetail_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function AffiliateDetail_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function AffiliateDetail_createClass(Constructor, protoProps, staticProps) { if (protoProps) AffiliateDetail_defineProperties(Constructor.prototype, protoProps); if (staticProps) AffiliateDetail_defineProperties(Constructor, staticProps); return Constructor; }









var AffiliateDetail_AffiliateDetail = /*#__PURE__*/function () {
  function AffiliateDetail(vnode) {
    AffiliateDetail_classCallCheck(this, AffiliateDetail);

    this.params = {
      id: vnode.attrs.id || 0,
      startDate: vnode.attrs.startDate || '',
      endDate: vnode.attrs.endDate || ''
    };
  }

  AffiliateDetail_createClass(AffiliateDetail, [{
    key: "refreshModel",
    value: function refreshModel(vnode) {
      var oldId = this.params.id,
          oldStartDate = this.params.startDate,
          oldEndDate = this.params.endDate;
      this.params = Object.assign(this.params, vnode.attrs);

      if (oldId != vnode.attrs.id || oldStartDate != vnode.attrs.startDate || oldEndDate != vnode.attrs.endDate) {
        this.model = new Affiliate(this.params);
      }
    }
  }, {
    key: "oncreate",
    value: function oncreate(vnode) {
      this.model = new Affiliate(this.params);
    }
  }, {
    key: "onupdate",
    value: function onupdate(vnode) {
      this.refreshModel(vnode);
    }
  }, {
    key: "view",
    value: function view(vnode) {
      return m("section", {
        "class": (Affiliate.details.pending ? "border-t-4 border-red-400 " : "") + "px-6 py-6 sm:w-3/4 rounded-lg bg-white shadow"
      }, Affiliate.details.pending ? m("article", {
        "class": "h-screen w-full"
      }, m(Header_DetailsHeader, null), m("article", {
        "class": "h-screen w-full sm:flex sm:items-center sm:justify-center"
      }, m("a", {
        href: Affiliate.details.editURL,
        target: "_blank",
        "class": "inline-flex justify-center py-4 px-6 border border-transparent text-lg leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
      }, "Review & Approve Affiliate"))) : m("article", {
        "class": "w-full"
      }, m(Header_DetailsHeader, null), m(KPI_DetailsKPI, null), Header_DetailsHeader.isOrdersTab ? m(Details_OrderDetails, {
        id: this.params.id,
        startDate: this.params.startDate,
        endDate: this.params.endDate
      }) : m(Details_PayoutDetails, {
        id: this.params.id,
        startDate: this.params.startDate,
        endDate: this.params.endDate
      }), m("section", {
        "class": "flex mt-2 mb-4 mt-0 overflow-x-auto"
      }, m(OtherDetails_OtherDetails, null), m(TopProducts_TopProducts, null))));
    }
  }]);

  return AffiliateDetail;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/ExportButton.js
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function ExportButton_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function ExportButton_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function ExportButton_createClass(Constructor, protoProps, staticProps) { if (protoProps) ExportButton_defineProperties(Constructor.prototype, protoProps); if (staticProps) ExportButton_defineProperties(Constructor, staticProps); return Constructor; }



var ExportButton_ExportButton = /*#__PURE__*/function () {
  function ExportButton() {
    ExportButton_classCallCheck(this, ExportButton);

    this.type = "standard";
  }

  ExportButton_createClass(ExportButton, [{
    key: "showOptions",
    value: function showOptions(e) {
      var x = document.getElementById("afwc-export-option");

      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
    }
  }, {
    key: "exportAffiliates",
    value: function exportAffiliates(type) {
      this.type = type;
      DashboardModel_DashboardModel.exportAffiliates(this.type);
      var x = document.getElementById("afwc-export-option");
      x.style.display = "none";
    }
  }, {
    key: "view",
    value: function view(vnode) {
      var _this = this;

      return m("div", {
        "class": "relative"
      }, m("button", {
        type: "button",
        id: "afwc-export-btn",
        onclick: function onclick(e) {
          _this.showOptions(e);
        },
        "class": "w-2/3 rounded-md shadow-sm cursor-pointer relative w-3/5 rounded-md border bg-white pl-3 pr-10 py-2 text-left focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition ease-in-out duration-150 sm:text-sm sm:leading-5"
      }, m("div", {
        "class": "flex items-center space-x-3"
      }, m("span", {
        "class": "font-normal flex truncate"
      }, m("span", {
        "class": "flex items-center pl-2 mr-2 pointer-events-none"
      }, m("svg", {
        "class": "h-5 w-5 text-gray-400",
        fill: "currentColor",
        viewBox: "0 0 20 20",
        xmlns: "http://www.w3.org/2000/svg"
      }, m("path", {
        "fill-rule": "evenodd",
        d: "M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z",
        "clip-rule": "evenodd"
      }))), "Export")), m("span", {
        "class": "absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
      }, m("svg", {
        "class": "h-5 w-5 text-gray-400",
        viewBox: "0 0 20 20",
        fill: "none",
        stroke: "currentColor"
      }, m("path", {
        d: "M7 7l3-3 3 3m0 6l-3 3-3-3",
        "stroke-width": "1.5",
        "stroke-linecap": "round",
        "stroke-linejoin": "round"
      })))), m("div", {
        id: "afwc-export-option",
        "class": "absolute mt-1 w-3/5 rounded-md bg-white shadow-lg",
        style: "display: none;"
      }, m("ul", {
        tabindex: "-1",
        role: "listbox",
        "class": "max-h-56 rounded-md py-1 text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5"
      }, m("li", _defineProperty({
        id: "listbox-item-0",
        "data-type": "standard",
        onclick: function onclick(event) {
          _this.exportAffiliates(event.target.getAttribute('data-type'));
        },
        "class": "{ 'text-white bg-indigo-600': selected === 0, 'text-gray-900': !(selected === 0) }"
      }, "class", "cursor-pointer select-none relative py-2 pl-3 pr-9 text-gray-900"), "Standard CSV"), m("li", _defineProperty({
        id: "listbox-item-0",
        "data-type": "mass_payment",
        onclick: function onclick(event) {
          _this.exportAffiliates(event.target.getAttribute('data-type'));
        },
        "class": "{ 'text-white bg-indigo-600': selected === 0, 'text-gray-900': !(selected === 0) }"
      }, "class", "cursor-pointer select-none relative py-2 pl-3 pr-9 text-gray-900"), "Mass Payout CSV"))));
    }
  }]);

  return ExportButton;
}();


// CONCATENATED MODULE: ./src/admin/views/affiliate/Main.js
function Main_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Main_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Main_createClass(Constructor, protoProps, staticProps) { if (protoProps) Main_defineProperties(Constructor.prototype, protoProps); if (staticProps) Main_defineProperties(Constructor, staticProps); return Constructor; }







var Main_Main = /*#__PURE__*/function () {
  Main_createClass(Main, [{
    key: "initialize",
    value: function initialize(params) {
      this.startDate = params.startDate || '';
      this.endDate = params.endDate || '';
    }
  }]);

  function Main(vnode) {
    Main_classCallCheck(this, Main);

    this.initialize(vnode.attrs);
  }

  Main_createClass(Main, [{
    key: "view",
    value: function view(vnode) {
      this.initialize(vnode.attrs);
      return m("main", {
        "class": "max-w-7xl mx-auto my-8 py-6 sm:px-3 xl:px-8 sm:flex sm:items-top"
      }, m("section", {
        "class": "sm:w-1/4"
      }, m(PendingPayouts_PendingPayouts, null), m(ExportButton_ExportButton, null), m(AffiliateList_List, null)), m(AffiliateDetail_AffiliateDetail, {
        id: DashboardModel_DashboardModel.data.currentAffiliateID,
        startDate: this.startDate,
        endDate: this.endDate
      }));
    }
  }]);

  return Main;
}();


// CONCATENATED MODULE: ./src/admin/Notification.js
function Notification_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Notification_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Notification_createClass(Constructor, protoProps, staticProps) { if (protoProps) Notification_defineProperties(Constructor.prototype, protoProps); if (staticProps) Notification_defineProperties(Constructor, staticProps); return Constructor; }




var Notification_AFWCNotifications = /*#__PURE__*/function () {
  function AFWCNotifications() {
    Notification_classCallCheck(this, AFWCNotifications);
  }

  Notification_createClass(AFWCNotifications, [{
    key: "showFeedback",
    value: function showFeedback() {
      if (NotificationModel["a" /* default */].notification.status === 'success' && afwcDashboardParams.can_ask_for_feedback) {
        FeedbackModel_FeedbackModel.flags.showFeedback = 1;
      }
    }
  }, {
    key: "view",
    value: function view() {
      var _this = this;

      setTimeout(function () {
        if (NotificationModel["a" /* default */].flags.showNotification) {
          var notificationCloseBtn = document.getElementsByClassName('afwc-notification-close');

          if (notificationCloseBtn.length > 0) {
            notificationCloseBtn[0].click();
          }

          _this.showFeedback();
        }
      }, 8000);
      return m("div", {
        "class": "fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end",
        style: "z-index: 200000;"
      }, m("div", {
        "class": "w-full max-w-sm bg-white rounded-lg shadow-lg pointer-events-auto bottom-0 fixed"
      }, m("div", {
        "class": "overflow-hidden rounded-lg shadow-xs text-white"
      }, m("div", {
        "class": (NotificationModel["a" /* default */].notification.status != 'success' ? NotificationModel["a" /* default */].notification.status == 'failed' ? 'bg-red-600' : 'bg-yellow-300' : 'bg-green-400') + " p-4"
      }, m("div", {
        "class": "flex items-center"
      }, m("div", {
        "class": "flex-shrink-0"
      }, m("svg", {
        "class": "w-6 h-6",
        stroke: "currentColor",
        fill: "none",
        "stroke-width": "2",
        viewBox: "0 0 24 24"
      }, m("path", {
        d: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
      }))), m("div", {
        "class": "ml-3 flex-1"
      }, m("p", {
        "class": "text-sm leading-5"
      }, NotificationModel["a" /* default */].notification.message)), m("div", {
        "class": "flex flex-shrink-0 ml-4"
      }, m("button", {
        type: "button",
        "class": "afwc-notification-close inline-flex p-1 rounded-md hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out",
        onclick: function onclick() {
          _this.showFeedback();

          setTimeout(NotificationModel["a" /* default */].flags.showNotification = 0, 2000);
        }
      }, m("svg", {
        "class": "h-6 w-6 text-white",
        stroke: "currentColor",
        fill: "none",
        viewBox: "0 0 24 24"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M6 18L18 6M6 6l12 12"
      })))))))));
    }
  }]);

  return AFWCNotifications;
}();


// CONCATENATED MODULE: ./src/admin/views/Dashboard.js
function Dashboard_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Dashboard_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Dashboard_createClass(Constructor, protoProps, staticProps) { if (protoProps) Dashboard_defineProperties(Constructor.prototype, protoProps); if (staticProps) Dashboard_defineProperties(Constructor, staticProps); return Constructor; }









var Dashboard_Dashboard = /*#__PURE__*/function () {
  Dashboard_createClass(Dashboard, [{
    key: "initialize",
    value: function initialize(params) {
      // this.urlParams = ( Object.keys(params).length ) ? params : { start_date: new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().slice(0,10), end_date: new Date().toISOString().slice(0,10)};
      this.urlParams = {};
      this.model = new DashboardModel_DashboardModel(this.urlParams);
    }
  }]);

  function Dashboard(vnode) {
    Dashboard_classCallCheck(this, Dashboard);

    this.initialize(vnode.attrs);
  }

  Dashboard_createClass(Dashboard, [{
    key: "onupdate",
    value: function onupdate(vnode) {
      if (this.urlParams != vnode.attrs && Object.keys(vnode.attrs).length) {
        this.initialize(vnode.attrs);
      }
    }
  }, {
    key: "view",
    value: function view() {
      return m('[', null, m(NavBar_AFWNavBar, {
        startDate: DashboardModel_DashboardModel.data.start_date,
        endDate: DashboardModel_DashboardModel.data.end_date
      }), Object.keys(DashboardModel_DashboardModel.data.kpi).length > 0 && DashboardModel_DashboardModel.data.affiliates.length > 0 ? m('[', null, m(KPI_AFWKPI, null), m(Main_Main, {
        startDate: DashboardModel_DashboardModel.data.start_date,
        endDate: DashboardModel_DashboardModel.data.end_date
      })) : !Loader["a" /* default */].showLoader ? m("header", {
        "class": "bg-white shadow"
      }, m("div", {
        "class": "max-w-7xl mx-auto py-40 sm:px-3 xl:px-8 text-gray-500"
      }, m("p", {
        "class": "text-xl font-medium leading-8 text-center"
      }, "No affiliates yet."), m("div", {
        "class": "text-lg mx-auto leading-8 max-w-sm mt-6 space-y-4"
      }, m("p", null, "Go to \"Settings\" and select some user roles to automatically make them affiliates."), m("p", null, "Or edit your affiliate registration form (link available in Settings) and link it from your site."), m("p", null, "Or go to an individual user's profile in WordPress and make them an affiliate.")))) : "", NotificationModel["a" /* default */].flags.showNotification ? m(Notification_AFWCNotifications, null) : null);
    }
  }]);

  return Dashboard;
}();


// EXTERNAL MODULE: ./src/admin/models/campaign/CampaignDashboardModel.js
var CampaignDashboardModel = __webpack_require__(3);

// CONCATENATED MODULE: ./src/admin/views/campaign/CampaignKPI.js
function CampaignKPI_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CampaignKPI_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CampaignKPI_createClass(Constructor, protoProps, staticProps) { if (protoProps) CampaignKPI_defineProperties(Constructor.prototype, protoProps); if (staticProps) CampaignKPI_defineProperties(Constructor, staticProps); return Constructor; }



var CampaignKPI_CampaignKPI = /*#__PURE__*/function () {
  function CampaignKPI() {
    CampaignKPI_classCallCheck(this, CampaignKPI);
  }

  CampaignKPI_createClass(CampaignKPI, [{
    key: "view",
    value: function view() {
      return m("header", {
        "class": "bg-white shadow"
      }, m("div", {
        "class": "max-w-7xl mx-auto py-8 sm:px-3 xl:px-8"
      }, m("section", {
        "class": "md:flex md:items-center md:justify-between"
      }, m("div", {
        "class": "flex sm:items-center text-gray-400"
      }, m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-bold text-gray-700"
      }, CampaignDashboardModel["a" /* default */].data.kpi.total_hits), m("p", {
        "class": "mt-1 leading-6 font-medium text-gray-500"
      }, "Visitors")), m("svg", {
        "class": "fill-current w-3 h-3 mx-4",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      })), m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-bold text-gray-700"
      }, CampaignDashboardModel["a" /* default */].data.kpi.total_orders), m("p", {
        "class": "mt-1 leading-6 font-medium text-gray-500"
      }, "Orders")), m("svg", {
        "class": "fill-current w-3 h-3 mx-4",
        xmlns: "http://www.w3.org/2000/svg",
        viewBox: "0 0 320 512"
      }, m("path", {
        d: "M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"
      })), m("div", {
        "class": ""
      }, m("p", {
        "class": "text-2xl leading-none font-bold text-gray-700"
      }, CampaignDashboardModel["a" /* default */].data.kpi.conversion, "%"), m("p", {
        "class": "mt-1 leading-6 font-medium text-gray-500"
      }, "Conversion Rate"))))));
    }
  }]);

  return CampaignKPI;
}();


// CONCATENATED MODULE: ./src/admin/views/campaign/CampaignDetail.js
function CampaignDetail_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CampaignDetail_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CampaignDetail_createClass(Constructor, protoProps, staticProps) { if (protoProps) CampaignDetail_defineProperties(Constructor.prototype, protoProps); if (staticProps) CampaignDetail_defineProperties(Constructor, staticProps); return Constructor; }





var CampaignDetail_CampaignDetail = /*#__PURE__*/function () {
  function CampaignDetail(vnode) {
    CampaignDetail_classCallCheck(this, CampaignDetail);

    this.params = {
      id: vnode.attrs.id || 0
    };
  }

  CampaignDetail_createClass(CampaignDetail, [{
    key: "oncreate",
    value: function oncreate(vnode) {
      wp.editor.remove('campaign-editor-' + Campaign["a" /* default */].details.campaignId);
      var settings = {
        tinymce: {
          wpautop: true,
          toolbar1: 'bold, italic, strikethrough, bullist, numlist, blockquote, hr, alignleft, aligncenter, alignright, link, unlink, wp_more, spellchecker, fullscreen, wp_adv',
          toolbar2: 'formatselect, underline, alignjustify, forecolor, pastetext, removeformat, charmap, outdent, indent, undo, redo, wp_help'
        },
        quicktags: true,
        mediaButtons: true,
        textarea_rows: 40
      };
      var d = document.getElementById('campaign-editor-' + Campaign["a" /* default */].details.campaignId);
      wp.editor.initialize('campaign-editor-' + Campaign["a" /* default */].details.campaignId, settings);
      tinymce.execCommand('mceAddEditor', true, 'campaign-editor-' + Campaign["a" /* default */].details.campaignId);
      var activeEditor = tinyMCE.get('campaign-editor-' + Campaign["a" /* default */].details.campaignId);
      activeEditor.setContent(Campaign["a" /* default */].details.body);
      d.value = Campaign["a" /* default */].details.body || '';
    }
  }, {
    key: "updateCampaign",
    value: function updateCampaign() {
      //validation
      var title = document.getElementById('campaign-title').value;
      var d = document.getElementById('campaign-editor-' + Campaign["a" /* default */].details.campaignId);

      if (title == '') {
        NotificationModel["a" /* default */].flags.showNotification = 1;
        NotificationModel["a" /* default */].notification.message = "Please add campaign title";
        NotificationModel["a" /* default */].notification.status = 'error';
        return;
      } else {
        Campaign["a" /* default */].details.status = document.getElementById('campaign-status').value;
        Campaign["a" /* default */].details.slug = document.getElementById('campaign-slug').value;
        Campaign["a" /* default */].details.targetLink = document.getElementById('campaign-link').value;
        Campaign["a" /* default */].details.shortDescription = document.getElementById('campaign-short').value;
        var activeEditor = tinyMCE.get('campaign-editor-' + Campaign["a" /* default */].details.campaignId);
        var content;

        if (activeEditor) {
          content = activeEditor.getContent();
        } else {
          content = d.value;
        }

        Campaign["a" /* default */].details.body = content;
        Campaign["a" /* default */].saveCampaign();
      }
    }
  }, {
    key: "view",
    value: function view(vnode) {
      var _this = this;

      var displayLink = '';

      if (Campaign["a" /* default */].details.slug != '') {
        displayLink += (Campaign["a" /* default */].details.targetLink.match(/[\?]/g) ? '&' : '?') + 'utm_campaign=' + Campaign["a" /* default */].details.slug;
        displayLink += (displayLink.match(/[\?]/g) ? '&' : '?') + 'ref={affiliate_id}';
      } else {
        displayLink += (Campaign["a" /* default */].details.targetLink.match(/[\?]/g) ? '&' : '?') + 'ref={affiliate_id}';
      }

      displayLink = Campaign["a" /* default */].details.targetLink + displayLink;
      return m("aside", {
        "class": "fixed inset-0 overflow-hidden",
        style: "z-index: 120000"
      }, m("div", {
        "class": "absolute inset-0 overflow-hidden"
      }, m("div", {
        "class": "absolute inset-0 bg-gray-500 transition-opacity opacity-75"
      }), m("section", {
        "class": "absolute inset-y-0 right-0 pl-20 max-w-full flex"
      }, m("div", {
        "class": "w-screen max-w-4xl"
      }, m("div", {
        "class": "h-full flex flex-col space-y-6 bg-white shadow-xl overflow-y-scroll"
      }, m("header", {
        "class": "px-4 py-2 sticky top-0 bg-gray-100",
        style: "z-index:120001"
      }, m("div", {
        "class": "flex items-center justify-between space-x-3"
      }, m("h2", {
        "class": "text-lg leading-7 font-medium text-gray-900"
      }, "Campaign Detail"), m("div", {
        "class": "h-7 flex items-center space-x-3"
      }, m('[', null, m("label", {
        "for": "campaign-status",
        "class": "hidden sr-only"
      }, "Status"), m("select", {
        id: "campaign-status",
        "class": "form-select pl-3 pr-10 py-2 text-base leading-6 border-gray-100 focus:outline-none focus:shadow-outline-blue  focus:border-blue-100 sm:text-sm sm:leading-5 text-gray-500",
        "aria-label": "Campaign status"
      }, m("option", {
        value: "Active",
        selected: Campaign["a" /* default */].details.status == "Active" ? "selected" : ""
      }, "Active"), m("option", {
        value: "Draft",
        selected: Campaign["a" /* default */].details.status == "Draft" ? "selected" : ""
      }, "Draft"))), m("button", {
        type: "button",
        "class": "inline-flex justify-center px-4 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo",
        onclick: function onclick() {
          _this.updateCampaign();
        }
      }, "Save"), m("button", {
        onclick: function onclick() {
          Campaign["a" /* default */].deleteCampaign();
        },
        "aria-label": "Delete campaign",
        type: "button",
        "class": "text-gray-400 hover:text-red-600 text-red-800 transition ease-in-out duration-150"
      }, m("svg", {
        fill: "none",
        stroke: "currentColor",
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        viewBox: "0 0 24 24",
        "class": "w-6 h-6"
      }, m("path", {
        d: "M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
      }))), m("span", {
        "class": "px-2"
      }), m("button", {
        "aria-label": "Close panel",
        "class": "text-gray-400 hover:text-gray-500 transition ease-in-out duration-150",
        onclick: function onclick() {
          Campaign["a" /* default */].currentCampaignID = 0;
        }
      }, m("svg", {
        "class": "h-6 w-6",
        fill: "none",
        viewBox: "0 0 24 24",
        stroke: "currentColor"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M6 18L18 6M6 6l12 12"
      })))))), m("div", {
        "class": "relative flex-1 px-4 sm:px-6"
      }, m("div", {
        "class": "absolute inset-0 px-4 sm:px-6"
      }, m("div", {
        "class": "h-full space-y-6"
      }, m("div", {
        "class": "space-y-1"
      }, m("div", {
        "class": "flex items-baseline space-x-3"
      }, m("label", {
        "for": "campaign-title",
        "class": "text-sm font-medium leading-5 text-gray-900"
      }, "Name"), m("p", {
        "class": "text-xs text-gray-500"
      }, "A descriptive title for this campaign - so affiliates can easily understand purpose of the campaign.")), m("div", {
        "class": "relative rounded-md shadow-sm"
      }, m("input", {
        id: "campaign-title",
        "class": "form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150",
        value: Campaign["a" /* default */].details.title || "",
        oninput: function oninput(e) {
          Campaign["a" /* default */].details.title = e.target.value;
          Campaign["a" /* default */].details.slug = e.target.value.toLowerCase().replace(/[^\w ]+/g, "").replace(/ +/g, "-");
        }
      }))), m("div", {
        "class": "space-y-1"
      }, m("div", {
        "class": "flex items-baseline space-x-3"
      }, m("label", {
        "for": "campaign-slug",
        "class": "text-sm font-medium leading-5 text-gray-900"
      }, "Slug"), m("p", {
        "class": "text-xs text-gray-500"
      }, "Short text in links for this campaign -", m("code", {
        "class": "font-mono"
      }, displayLink))), m("div", {
        "class": "relative rounded-md shadow-sm w-2/3"
      }, m("input", {
        id: "campaign-slug",
        "class": "form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150",
        value: Campaign["a" /* default */].details.slug || ""
      }))), m("div", {
        "class": "space-y-1"
      }, m("div", {
        "class": "flex items-baseline space-x-3"
      }, m("label", {
        "for": "campaign-link",
        "class": "text-sm font-medium leading-5 text-gray-900"
      }, "Destination Link"), m("p", {
        "class": "text-xs text-gray-500"
      }, "Want to take campaign visitors to a specific product / landing page? Or shop / home page? Enter it here.")), m("div", {
        "class": "relative rounded-md shadow-sm"
      }, m("input", {
        id: "campaign-link",
        "class": "form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150",
        value: Campaign["a" /* default */].details.targetLink || ""
      }))), m("div", {
        "class": "space-y-1"
      }, m("div", {
        "class": "flex items-baseline space-x-3"
      }, m("label", {
        "for": "campaign-short",
        "class": "text-sm font-medium leading-5 text-gray-900"
      }, "Short Description"), m("p", {
        "class": "text-xs text-gray-500"
      }, "This will show along with campaign name before people see all the details. Summarize the campaign here.")), m("div", {
        "class": "relative rounded-md shadow-sm"
      }, m("textarea", {
        id: "campaign-short",
        rows: "3",
        "class": "form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150"
      }, Campaign["a" /* default */].details.shortDescription || ""))), m("div", {
        "class": "space-y-1"
      }, m("div", {
        "class": "flex flex-col space-y-2"
      }, m("label", {
        "for": "campaign-editor-" + Campaign["a" /* default */].details.campaignId,
        "class": "text-sm font-medium leading-5 text-gray-900"
      }, "Full Description"), m("p", {
        "class": "text-xs text-gray-500"
      }, "Use this editor to insert all the details, rules, creatives, swipes, videos, guidelines or anything else you want to present to affiliate partners. Format it well so that they can easily copy out for their own usage."), m("p", {
        "class": "text-xs text-gray-500"
      }, "If you prefer, create a .zip file with multiple assets. Upload it to your WordPress Media, and link the file from here. You can also link to any external resources as usual.")), m("div", {
        "class": "relative pt-3"
      }, m("textarea", {
        id: "campaign-editor-" + Campaign["a" /* default */].details.campaignId,
        row: "60",
        "class": "wp-editor-area",
        style: "height: 300px;",
        autocomplete: "off",
        cols: "40"
      }, Campaign["a" /* default */].details.body || "")))))))), NotificationModel["a" /* default */].flags.showNotification ? m(Notification_AFWCNotifications, null) : null)));
    }
  }]);

  return CampaignDetail;
}();


// CONCATENATED MODULE: ./src/admin/views/campaign/CampaignList.js
function CampaignList_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CampaignList_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CampaignList_createClass(Constructor, protoProps, staticProps) { if (protoProps) CampaignList_defineProperties(Constructor.prototype, protoProps); if (staticProps) CampaignList_defineProperties(Constructor, staticProps); return Constructor; }





var CampaignList_CampaignList = /*#__PURE__*/function () {
  function CampaignList(vnode) {
    CampaignList_classCallCheck(this, CampaignList);

    this.params = {
      id: vnode.attrs.id || 0
    };
  }

  CampaignList_createClass(CampaignList, [{
    key: "refreshModel",
    value: function refreshModel(currentCampaignId) {
      this.params.id = currentCampaignId;
      this.model = new Campaign["a" /* default */]();
    }
  }, {
    key: "view",
    value: function view() {
      var _this = this;

      return m("section", null, m("h3", {
        "class": "text-lg font-bold text-gray-700 sm:text-3xl leading-8"
      }, "Campaigns"), m("ul", {
        "class": "text-gray-600 py-3 max-h-screen overflow-auto"
      }, CampaignDashboardModel["a" /* default */].data.campaigns.map(function (item, key) {
        return m("li", {
          "class": "my-6 overflow-hidden text-xs text-gray-400 transition duration-150 ease-in-out bg-white rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50",
          onclick: function onclick() {
            Campaign["a" /* default */].currentCampaignID = item.campaignId;

            _this.refreshModel(Campaign["a" /* default */].currentCampaignID);
          }
        }, m("a", {
          "class": "block py-3 cursor-pointer hover:bg-white focus:outline-none focus:bg-white transition duration-150 ease-in-out" + (Campaign["a" /* default */].currentCampaignID == item.campaignId ? " bg-white outline-none" : "")
        }, m("div", {
          "class": "flex items-center pl-4 py-2"
        }, m("div", {
          "class": (item.status === 'Draft' ? "text-gray-400" : "text-gray-700") + " text-lg pr-4 flex-1 sm:truncate leading-6"
        }, item.title), m("div", {
          "class": "mx-2"
        }, m("svg", {
          "class": "h-5 w-5 text-gray-400",
          fill: "currentColor",
          viewBox: "0 0 20 20"
        }, m("path", {
          "fill-rule": "evenodd",
          d: "M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z",
          "clip-rule": "evenodd"
        }))))));
      })), Campaign["a" /* default */].currentCampaignID !== 0 ? m(CampaignDetail_CampaignDetail, {
        id: Campaign["a" /* default */].currentCampaignID
      }) : '');
    }
  }]);

  return CampaignList;
}();


Campaign["a" /* default */].currentCampaignID = 0;
// CONCATENATED MODULE: ./src/admin/views/campaign/CampaignMain.js
function CampaignMain_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CampaignMain_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CampaignMain_createClass(Constructor, protoProps, staticProps) { if (protoProps) CampaignMain_defineProperties(Constructor.prototype, protoProps); if (staticProps) CampaignMain_defineProperties(Constructor, staticProps); return Constructor; }



var CampaignMain_CampaignMain = /*#__PURE__*/function () {
  function CampaignMain() {
    CampaignMain_classCallCheck(this, CampaignMain);
  }

  CampaignMain_createClass(CampaignMain, [{
    key: "view",
    value: function view(vnode) {
      return m("main", {
        "class": "max-w-7xl mx-auto my-4 py-2 px-8 grid gap-8 grid-cols-3"
      }, m("section", {
        "class": "col-span-2 overflow-y-auto"
      }, m(CampaignList_CampaignList, null)), m("section", {
        "class": "col-span-1 space-y-6 text-gray-500 pt-12 pr-8"
      }, m("p", null, "Campaigns help you organize your marketing and promotional materials (all the logos, banners, swipes, videos, guidelines, giveaways, social media assets and what have you...) in different \"buckets\"."), m("p", null, "Create a campaign for common media assets, or holiday / offer specific materials, or social media shares, or to run an affiliate competition or anything else that makes sense."), m("p", null, "Each campaign can have a destination landing page of its own and will get a unique link. When affiliates share the campaign's affiliated link, visitors will arrive on the destination link and will be attributed to the campaign."), m("p", null, "Add high quality, well designed graphic assets, email swipes and any other material you'd like to provide to affiliate partners  in the campaign. They can copy / download those assets and quickly start promoting."), m("p", null, "If you find it difficult to add assets / format the campaign page, put everything together in a .zip file and link it from the campaign description so partners can download them easily."), m("p", null, "We track referrals and conversions at both affiliate and campaign level. We will add more reports soon.")));
    }
  }]);

  return CampaignMain;
}();


// CONCATENATED MODULE: ./src/admin/views/CampaignsDashboard.js
function CampaignsDashboard_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CampaignsDashboard_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CampaignsDashboard_createClass(Constructor, protoProps, staticProps) { if (protoProps) CampaignsDashboard_defineProperties(Constructor.prototype, protoProps); if (staticProps) CampaignsDashboard_defineProperties(Constructor, staticProps); return Constructor; }











var CampaignsDashboard_CampaignsDashboard = /*#__PURE__*/function () {
  CampaignsDashboard_createClass(CampaignsDashboard, [{
    key: "initialize",
    value: function initialize(params) {
      this.urlParams = Object.keys(params).length ? params : {
        start_date: new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().slice(0, 10),
        end_date: new Date().toISOString().slice(0, 10)
      };
      this.model = new CampaignDashboardModel["a" /* default */](this.urlParams);
    }
  }]);

  function CampaignsDashboard(vnode) {
    CampaignsDashboard_classCallCheck(this, CampaignsDashboard);

    this.initialize(vnode.attrs);
  }

  CampaignsDashboard_createClass(CampaignsDashboard, [{
    key: "onupdate",
    value: function onupdate(vnode) {
      if (this.urlParams != vnode.attrs && Object.keys(vnode.attrs).length) {
        this.initialize(vnode.attrs);
      }
    }
  }, {
    key: "view",
    value: function view() {
      return m("div", null, m(NavBar_AFWNavBar, {
        startDate: this.urlParams.start_date,
        endDate: this.urlParams.end_date
      }), CampaignDashboardModel["a" /* default */].data.campaigns.length > 0 ? m('[', null, m(CampaignKPI_CampaignKPI, null), m(CampaignMain_CampaignMain, null)) : !Loader["a" /* default */].showLoader ? m("header", {
        "class": "bg-white shadow"
      }, m("div", {
        "class": "max-w-7xl mx-auto py-40 sm:px-3 xl:px-8 text-center text-gray-500"
      }, m("p", {
        "class": "text-xl font-medium leading-8"
      }, "No campaigns yet."), m("div", {
        "class": "text-lg mx-auto leading-8 max-w-md mt-6 space-y-4"
      }, m("p", null, "Go ahead, click that \"Add a Campaign\" button in the top bar to create your first campaign."))), Campaign["a" /* default */].currentCampaignID === -1 ? m(CampaignDetail_CampaignDetail, {
        id: Campaign["a" /* default */].currentCampaignID
      }) : '') : "", NotificationModel["a" /* default */].flags.showNotification ? m(Notification_AFWCNotifications, null) : null);
    }
  }]);

  return CampaignsDashboard;
}();


// CONCATENATED MODULE: ./src/admin/views/commission/CommissionRuleCondition.js
function CommissionRuleCondition_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CommissionRuleCondition_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CommissionRuleCondition_createClass(Constructor, protoProps, staticProps) { if (protoProps) CommissionRuleCondition_defineProperties(Constructor.prototype, protoProps); if (staticProps) CommissionRuleCondition_defineProperties(Constructor, staticProps); return Constructor; }



var CommissionRuleCondition_CommissionRuleCondition = /*#__PURE__*/function () {
  function CommissionRuleCondition(vnode) {
    var _this = this;

    CommissionRuleCondition_classCallCheck(this, CommissionRuleCondition);

    this.ruleRow = vnode.attrs.ruleRow;
    this.type = vnode.attrs.ruleRow.type;
    this.index = vnode.attrs.index;
    this.key = vnode.attrs.key;
    this.operator = vnode.attrs.ruleRow.operator;
    this.value = vnode.attrs.ruleRow.value;
    this.callbackToParent = vnode.attrs.callbackToParent;
    this.possibleOperators = Commission_Commission.allRules[this.type].possibleOperators;
    this.isOnlyOne = vnode.attrs.isOnlyOne;
    var res = this.possibleOperators.filter(function (x) {
      return _this.operator == x.op;
    });
    this._operator = res.length > 0 ? res[0] : {};
    this.select_id = Math.random().toString(36).substring(7);
  }

  CommissionRuleCondition_createClass(CommissionRuleCondition, [{
    key: "oncreate",
    value: function oncreate(vnode) {
      this.intializeSelectwoo();
    }
  }, {
    key: "onupdate",
    value: function onupdate(vnode) {
      this.index = vnode.attrs.index;
    }
  }, {
    key: "getDetails",
    value: function getDetails() {
      return {
        type: this.type,
        operator: this.operator,
        value: this.value,
        key: this.key
      };
    }
  }, {
    key: "refreshValues",
    value: function refreshValues(type) {
      this.value = '';
      this.type = type;
      this.possibleOperators = Commission_Commission.allRules[this.type].possibleOperators; //select2 changes after type select change

      this.intializeSelectwoo();
      this.informParent();
    }
  }, {
    key: "intializeSelectwoo",
    value: function intializeSelectwoo() {
      var _this2 = this;

      var type = this.type;
      var vowelRegex = '^[aieouAIEOU].*';
      var matched = type.match(vowelRegex);
      var placeholder = "Search for ";
      placeholder = matched ? placeholder + "an " : placeholder + "a ";
      placeholder = placeholder + type.replace(/_/g, ' '); //load select 2

      var select2_args = {
        placeholder: placeholder,
        dropdownParent: jQuery('#afwc-commission-details'),
        minimumInputLength: 3,
        escapeMarkup: function escapeMarkup(m) {
          return m;
        },
        ajax: {
          url: ajaxurl,
          dataType: 'json',
          delay: 1000,
          data: function data(params) {
            return {
              term: params.term,
              type: type,
              action: 'afwc_json_search_rule_values',
              security: afwcDashboardParams.security
            };
          },
          processResults: function processResults(data) {
            var terms = [];

            if (data) {
              jQuery.each(data, function (id, text) {
                terms.push({
                  id: id,
                  text: text
                });
              });
            }

            return {
              results: terms
            };
          },
          cache: true
        }
      };
      jQuery('#' + this.key).selectWoo(select2_args);
      jQuery('#' + this.key).on("change", function (e) {
        _this2.value = jQuery('#' + _this2.key).val();

        _this2.informParent();
      });
    }
  }, {
    key: "renderFieldSelect",
    value: function renderFieldSelect(ruleRow) {
      var _this3 = this;

      var planData = afwcDashboardParams.plan_dashboard_data.plan_rule_data;
      var type = this.type || '';
      return m("select", {
        "class": "afwc-rule-select py-1 pl-3 pr-8 text-sm font-medium leading-5 capitalize text-gray-700 bg-transparent form-select",
        onchange: function onchange(e) {
          _this3.refreshValues(e.target.value);
        }
      }, Object.keys(planData).map(function (key, id) {
        return m("optgroup", {
          label: key
        }, Object.keys(planData[key]).map(function (key1, id1) {
          return m("option", {
            value: key1,
            selected: type === key1 ? "selected" : ""
          }, key1.replace("_", " "));
        }));
      }));
    }
  }, {
    key: "renderOperatorSelect",
    value: function renderOperatorSelect(ruleRow) {
      var _this4 = this;

      var operator = this.operator || '';
      return m("select", {
        "class": "py-1 pl-3 pr-8 text-sm leading-5 text-gray-500 bg-transparent form-select",
        onchange: function onchange(e) {
          _this4.listenOpChange(e);
        }
      }, this.possibleOperators.map(function (key) {
        return m("option", {
          value: key['op'],
          selected: operator === key['op'] ? "selected" : ""
        }, key['label']);
      }));
    }
  }, {
    key: "listenOpChange",
    value: function listenOpChange(e) {
      var _this5 = this;

      this.operator = e.target.value;
      var res = this.possibleOperators.filter(function (x) {
        return _this5.operator == x.op;
      });
      this._operator = res.length > 0 ? res[0] : {};
      this.informParent();
    }
  }, {
    key: "informParent",
    value: function informParent() {
      this.callbackToParent(this.getDetails(), "update", this.index);
    }
  }, {
    key: "deleteRule",
    value: function deleteRule() {
      this.callbackToParent(null, "delete", this.index);
    }
  }, {
    key: "renderValues",
    value: function renderValues(ruleRow) {
      var _this6 = this;

      //according to operator display select2 or text box
      var type = 'single';
      var idNameMaps = Commission_Commission.idNameMaps[this.type] || {}; //hack to multiple values in select

      jQuery('#' + this.key).val('');
      type = this._operator.type;
      return m("div", {
        "class": "flex-1 relative"
      }, 'single' === type ? m("input", {
        "class": "w-full max-w-fullpy-1 text-sm leading-5 text-gray-700 form-input",
        value: this.value,
        oninput: function oninput(e) {
          _this6.value = e.target.value;

          _this6.informParent();
        }
      }) : m("select", {
        id: this.key,
        "class": "afwc-rule-value-select max-w-full py-1 pl-3 pr-8 text-sm leading-5 text-gray-500 bg-transparent form-select w-full block",
        "data-action": "afwc_json_search_rule_values",
        multiple: "multiple"
      }, this.value.length > 0 ? this.value.map(function (key) {
        return idNameMaps[key] ? m("option", {
          value: key,
          selected: "selected"
        }, idNameMaps[key]) : '';
      }) : ''));
    }
  }, {
    key: "addConditionRow",
    value: function addConditionRow(ruleRow) {
      var _this7 = this;

      return m("div", {
        "class": "flex items-center gap-2 w-full"
      }, this.renderFieldSelect(ruleRow), this.renderOperatorSelect(ruleRow), this.renderValues(ruleRow), m("div", {
        "class": "flex-shrink-0"
      }, m("button", {
        type: "button",
        onclick: function onclick() {
          _this7.deleteRule();
        },
        "class": (this.isOnlyOne ? "invisible" : "") + " flex items-center justify-center p-1 text-xs leading-5 text-gray-300 transition duration-150 ease-in-out border border-transparent rounded-md hover:text-red-600 hover:border-red-600 focus:outline-none focus:shadow-outline-red focus:border-red-600"
      }, m("svg", {
        fill: "none",
        stroke: "currentColor",
        "class": "w-5 h-5"
      }, m("svg", {
        id: "icon-remove-condition",
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        viewBox: "0 0 24 24"
      }, m("path", {
        d: "M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
      }))))));
    }
  }, {
    key: "view",
    value: function view(vnode) {
      this.isOnlyOne = vnode.attrs.isOnlyOne;
      return m('[', null, this.addConditionRow(this.ruleRow));
    }
  }]);

  return CommissionRuleCondition;
}();


// CONCATENATED MODULE: ./src/admin/views/commission/CommissionRuleGroup.js
function CommissionRuleGroup_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CommissionRuleGroup_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CommissionRuleGroup_createClass(Constructor, protoProps, staticProps) { if (protoProps) CommissionRuleGroup_defineProperties(Constructor.prototype, protoProps); if (staticProps) CommissionRuleGroup_defineProperties(Constructor, staticProps); return Constructor; }





var CommissionRuleGroup_CommissionRuleGroup = /*#__PURE__*/function () {
  function CommissionRuleGroup(vnode) {
    CommissionRuleGroup_classCallCheck(this, CommissionRuleGroup);

    this.callbackToParent = vnode.attrs.callbackToParent;
    this.index = vnode.attrs.index;
    this.rules = vnode.attrs.ruleGroup.rules;
    this.key = vnode.attrs.key;
    this.rules = this.rules.map(function (x) {
      x.key = Math.random().toString(36).substr(7);
      return x;
    });
    this.ruleGroupCondition = vnode.attrs.ruleGroup.condition;
  }

  CommissionRuleGroup_createClass(CommissionRuleGroup, [{
    key: "updateData",
    value: function updateData(data, operation, index) {
      if (operation == "update") {
        this.rules[index] = data;
      } else if (operation == "delete") {
        this.rules.splice(index, 1);
      }

      this.informParent();
    }
  }, {
    key: "onupdate",
    value: function onupdate(vnode) {
      this.index = vnode.attrs.index;
    }
  }, {
    key: "addNewRule",
    value: function addNewRule() {
      var newRule = JSON.parse(JSON.stringify(Commission_Commission.ruleRow));
      newRule.key = Math.random().toString(36).substr(7);
      this.rules.push(newRule);
      this.informParent();
    }
  }, {
    key: "getDetails",
    value: function getDetails() {
      return {
        condition: this.ruleGroupCondition,
        rules: this.rules,
        key: this.key
      };
    }
  }, {
    key: "informParent",
    value: function informParent() {
      this.callbackToParent(this.getDetails(), "update", this.index);
    }
  }, {
    key: "addNewRuleGroup",
    value: function addNewRuleGroup() {
      this.callbackToParent(null, "add", null);
    }
  }, {
    key: "deleteRuleGroup",
    value: function deleteRuleGroup() {
      this.callbackToParent(null, "delete", this.index);
    }
  }, {
    key: "view",
    value: function view(vnode) {
      var _this = this;

      return m("div", {
        "class": "px-4 pt-3 pb-2 mb-6 space-y-3 bg-gray-100 border-gray-200 rounded-md"
      }, m("div", {
        "class": "flex items-center gap-2 text-xs text-gray-400"
      }, " ", m("span", null, "This group is a \"pass\" when"), m("div", {
        "class": "flex items-center"
      }, m("select", {
        "aria-label": "match-type",
        "class": "h-full py-0.5 pl-3 pr-8 font-medium text-gray-700 bg-transparent form-select text-xs",
        onchange: function onchange(e) {
          _this.ruleGroupCondition = e.target.value;

          _this.informParent();
        }
      }, m("option", {
        value: "AND",
        selected: this.ruleGroupCondition === "AND" ? "selected" : ""
      }, "all"), m("option", {
        value: "OR",
        selected: this.ruleGroupCondition === "OR" ? "selected" : ""
      }, "at least one"))), " ", m("span", null, "of the following conditions is true."), " "), this.rules.map(function (ruleObj, id) {
        return m(CommissionRuleCondition_CommissionRuleCondition, {
          key: ruleObj.key,
          isOnlyOne: _this.rules.length === 1,
          index: id,
          callbackToParent: _this.updateData.bind(_this),
          ruleRow: ruleObj
        });
      }), m("div", {
        "class": "flex items-center gap-6 pt-1"
      }, m("button", {
        type: "button",
        "class": "flex items-center gap-1 justify-center px-1 py-0.5 text-xs leading-5 text-gray-400 transition duration-150 ease-in-out border border-transparent rounded-md hover:text-green-600 hover:border-green-600 focus:outline-none focus:shadow-outline-green focus:border-green-600",
        onclick: function onclick() {
          _this.addNewRule();
        }
      }, m("svg", {
        fill: "none",
        stroke: "currentColor",
        "class": "w-5 h-5"
      }, m("svg", {
        id: "icon-add-condition",
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        viewBox: "0 0 24 24"
      }, m("path", {
        d: "M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
      }))), " Add a condition "), " ", m("span", {
        "class": "flex-1"
      }), m("button", {
        type: "button",
        "class": "flex items-center gap-1 justify-center px-1 py-0.5 text-xs leading-5 text-gray-400 transition duration-150 ease-in-out border border-transparent rounded-md hover:text-indigo-600 hover:border-indigo-600 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-600",
        onclick: function onclick() {
          _this.addNewRuleGroup();
        }
      }, m("svg", {
        fill: "none",
        stroke: "currentColor",
        "class": "w-5 h-5"
      }, m("svg", {
        id: "icon-add-group",
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        viewBox: "0 0 24 24"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
      }))), " Add another group "), !vnode.attrs.isOnlyOne ? m("button", {
        type: "button",
        "class": "flex items-center gap-1 justify-center px-1 py-0.5 text-xs leading-5 text-gray-400 transition duration-150 ease-in-out border border-transparent rounded-md hover:text-red-600 hover:border-red-600 focus:outline-none focus:shadow-outline-red focus:border-red-600",
        onclick: function onclick() {
          _this.deleteRuleGroup();
        }
      }, m("svg", {
        fill: "none",
        stroke: "currentColor",
        "class": "w-5 h-5"
      }, m("svg", {
        id: "icon-remove-group",
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        viewBox: "0 0 24 24"
      }, m("path", {
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        d: "M9 13h6M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
      }))), " Remove this group ") : ""));
    }
  }]);

  return CommissionRuleGroup;
}();


// CONCATENATED MODULE: ./src/admin/views/commission/CommissionDetail.js
function CommissionDetail_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CommissionDetail_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CommissionDetail_createClass(Constructor, protoProps, staticProps) { if (protoProps) CommissionDetail_defineProperties(Constructor.prototype, protoProps); if (staticProps) CommissionDetail_defineProperties(Constructor, staticProps); return Constructor; }





var CommissionDetail_CommissionDetail = /*#__PURE__*/function () {
  function CommissionDetail(vnode) {
    CommissionDetail_classCallCheck(this, CommissionDetail);

    this.planCondition = Commission_Commission.details.rules.condition ? Commission_Commission.details.rules.condition : 'AND';
    this.ruleGroups = Commission_Commission.details.rules.rules ? Commission_Commission.details.rules.rules : [Commission_Commission.ruleGroup];
    this.commissionId = Commission_Commission.details.commissionId;
    this.name = Commission_Commission.details.name;
    this.type = Commission_Commission.details.type || 'Percentage';
    this.amount = Commission_Commission.details.amount;
    this.status = Commission_Commission.details.status || 'Active';
    this.apply_to = Commission_Commission.details.apply_to || 'all';
    this.action_for_remaining = Commission_Commission.details.action_for_remaining || 'continue';
    this.ruleGroups = this.ruleGroups.map(function (x) {
      x.key = Math.random().toString(36).substr(7);
      return x;
    });
  }

  CommissionDetail_createClass(CommissionDetail, [{
    key: "addNewRuleGroup",
    value: function addNewRuleGroup() {
      var newRuleGroup = JSON.parse(JSON.stringify(Commission_Commission.ruleGroup));
      newRuleGroup.key = Math.random().toString(36).substr(7);
      this.ruleGroups.push(newRuleGroup);
    }
  }, {
    key: "updateData",
    value: function updateData(data, operation, index) {
      if (operation == "update") {
        this.ruleGroups[index] = data;
      } else if (operation == "add") {
        this.addNewRuleGroup();
      } else if (operation == "delete") {
        this.ruleGroups.splice(index, 1);
      }
    }
  }, {
    key: "checkEmptyRule",
    value: function checkEmptyRule() {
      return this.ruleGroups.filter(function (ruleGroup) {
        return ruleGroup.rules.filter(function (rule) {
          return rule.value === '';
        }).length > 0;
      }).length > 0;
    }
  }, {
    key: "updateCommission",
    value: function updateCommission() {
      var details = this.getDetails();

      if (details.name == '') {
        NotificationModel["a" /* default */].flags.showNotification = 1;
        NotificationModel["a" /* default */].notification.message = "Please add commission title";
        NotificationModel["a" /* default */].notification.status = 'error';
        return;
      } else if (this.checkEmptyRule()) {
        NotificationModel["a" /* default */].flags.showNotification = 1;
        NotificationModel["a" /* default */].notification.message = "Empty rule values are not allowed";
        NotificationModel["a" /* default */].notification.status = 'error';
        return;
      } else if (details.amount == '') {
        NotificationModel["a" /* default */].flags.showNotification = 1;
        NotificationModel["a" /* default */].notification.message = "Empty amount value is not allowed";
        NotificationModel["a" /* default */].notification.status = 'error';
        return;
      } else if (details.type === 'Percentage' && details.amount >= 100) {
        NotificationModel["a" /* default */].flags.showNotification = 1;
        NotificationModel["a" /* default */].notification.message = "Add proper value of amount";
        NotificationModel["a" /* default */].notification.status = 'error';
        return;
      }

      details.rules.rules = details.rules.rules.map(function (x) {
        x.rules = x.rules.map(function (y) {
          delete y.key;
          return y;
        });
        delete x.key;
        return x;
      });
      Commission_Commission.details = details;
      Commission_Commission.saveCommission();
    }
  }, {
    key: "getDetails",
    value: function getDetails() {
      var details = {};
      details.rules = {
        condition: this.planCondition,
        rules: this.ruleGroups
      };
      details.name = this.name;
      details.amount = this.amount;
      details.type = this.type;
      details.status = this.status;
      details.apply_to = this.apply_to;
      details.action_for_remaining = this.action_for_remaining;
      details.commissionId = this.commissionId;
      return details;
    }
  }, {
    key: "view",
    value: function view(vnode) {
      var _this = this;

      return m("aside", {
        id: "afwc-commission-details",
        style: "z-index: 120000;",
        "class": "fixed inset-x-0 bottom-0 lg:inset-0 lg:flex lg:items-center lg:justify-end"
      }, m("div", {
        "class": "fixed inset-0 transition-opacity"
      }, m("div", {
        "class": "absolute inset-0 bg-gray-500 opacity-75"
      })), m("section", {
        "class": "w-full h-full max-w-6xl max-h-full mt-12 overflow-scroll transition-all transform bg-gray-50 lg:ml-1/6 lg:mt-0"
      }, m("div", {
        "class": "sticky top-0 z-10 w-full bg-gray-100"
      }, m("nav", {
        "class": "px-4 py-2 xl:px-4 lg:py-2"
      }, m("div", {
        "class": "flex items-center justify-between"
      }, m("div", {
        "class": "flex items-center flex-1 gap-2"
      }, m("label", {
        "for": "name",
        "class": "text-sm font-medium text-gray-700 uppercase"
      }, "Name"), m("input", {
        id: "name",
        "class": "w-full text-xl font-medium leading-7 text-gray-700 form-input",
        placeholder: "Enter a name for this commission plan",
        value: this.name || "",
        oninput: function oninput(e) {
          _this.name = e.target.value;
        }
      })), m("div", {
        "class": "flex items-center mt-2 text-right lg:mt-0"
      }, m("div", {
        "class": "flex items-center gap-4 ml-4 lg:ml-2"
      }, m('[', null, m("label", {
        "for": "commission-status",
        "class": "hidden sr-only"
      }, "Status"), m("select", {
        id: "commission-status",
        "class": "form-select pl-3 pr-10 py-2 text-base leading-6 border-gray-100 focus:outline-none focus:shadow-outline-blue  focus:border-blue-100 sm:text-sm sm:leading-5 text-gray-500",
        "aria-label": "Commission status",
        onchange: function onchange(e) {
          _this.status = e.target.value;
        }
      }, m("option", {
        value: "Active",
        selected: this.status == "Active" ? "selected" : ""
      }, "Active"), m("option", {
        value: "Draft",
        selected: this.status == "Draft" ? "selected" : ""
      }, "Draft"))), m("span", {
        "class": "rounded-md"
      }, m("button", {
        onclick: function onclick() {
          _this.updateCommission();
        },
        type: "button",
        "class": "inline-flex justify-center w-full px-5 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo"
      }, "Save")), m("button", {
        onclick: function onclick() {
          Commission_Commission.deleteCommission();
        },
        type: "button",
        "class": "p-1 transition duration-150 ease-in-out border-transparent rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 hover:text-red-600 text-red-800"
      }, m("svg", {
        fill: "none",
        stroke: "currentColor",
        "stroke-linecap": "round",
        "stroke-linejoin": "round",
        "stroke-width": "2",
        viewBox: "0 0 24 24",
        "class": "w-5 h-5"
      }, m("path", {
        d: "M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
      }))), m("button", {
        onclick: function onclick() {
          Commission_Commission.currentCommissionID = 0;
        },
        "class": "p-1 text-gray-400 transition duration-150 ease-in-out border-transparent rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100"
      }, m("svg", {
        "class": "w-6 h-6",
        stroke: "currentColor",
        "stroke-width": "2",
        fill: "none",
        viewBox: "0 0 24 24"
      }, m("path", {
        d: "M6 18L18 6M6 6l12 12"
      })))))))), m("div", {
        "class": "grid grid-cols-12 gap-8 p-8 text-gray-600"
      }, m("div", {
        "class": "col-span-3 text-sm leading-5"
      }, m("div", {
        "class": "mb-3 font-medium text-gray-700 uppercase"
      }, "Commission"), m("div", {
        "class": "relative rounded-md shadow-sm mb-3"
      }, m("input", {
        id: "commission",
        "class": "block w-full pl-2 pr-12 form-input sm:text-sm sm:leading-5",
        placeholder: "20.00",
        value: this.amount,
        oninput: function oninput(e) {
          _this.amount = e.target.value;
        }
      }), m("div", {
        "class": "absolute inset-y-0 right-0 flex items-center"
      }, m("select", {
        "aria-label": "Commission Type",
        "class": "h-full py-0 pl-2 pr-8 text-gray-500 bg-transparent border-transparent form-select sm:text-sm sm:leading-5",
        id: "commission_type",
        onchange: function onchange(e) {
          _this.type = e.target.value;
        }
      }, m("option", {
        value: "Percentage",
        selected: this.type == "Percentage" ? "selected" : ""
      }, "%"), m("option", {
        value: "Flat",
        selected: this.type == "Flat" ? "selected" : ""
      }, "$")))), m("div", {
        "class": "text-xs pt-8 mb-2 font-medium text-gray-600 uppercase"
      }, "Apply to"), m("div", {
        "class": "space-y-3 text-gray-500"
      }, Object.keys(afwcDashboardParams.plan_dashboard_data.apply_to).map(function (key, id) {
        return m("div", {
          "class": "flex items-center"
        }, m("label", null, m("input", {
          type: "radio",
          "class": "mr-2 text-indigo-600 transition duration-150 ease-in-out form-radio",
          name: "apply_to",
          value: key,
          onchange: function onchange(e) {
            _this.apply_to = e.target.value;
          },
          checked: _this.apply_to === key ? "checked" : ""
        }), afwcDashboardParams.plan_dashboard_data.apply_to[key]));
      })), m("div", {
        "class": "text-xs pt-8 mb-2 font-medium text-gray-600 uppercase"
      }, "And then, for remaining products in the order..."), m("div", {
        "class": "space-y-3 text-gray-500"
      }, Object.keys(afwcDashboardParams.plan_dashboard_data.action_for_remaining).map(function (key, id) {
        return m("div", {
          "class": "flex items-center"
        }, m("label", null, m("input", {
          type: "radio",
          "class": "mr-2 text-indigo-600 transition duration-150 ease-in-out form-radio",
          name: "action_for_remaining",
          value: key,
          onchange: function onchange(e) {
            _this.action_for_remaining = e.target.value;
          },
          checked: _this.action_for_remaining === key ? "checked" : ""
        }), afwcDashboardParams.plan_dashboard_data.action_for_remaining[key]));
      }))), m("div", {
        "class": "col-span-9 text-sm leading-5 text-gray-500"
      }, m("div", {
        "class": "flex items-baseline gap-2 mb-3 -mt-1"
      }, m("div", {
        "class": "mb-3 font-medium text-gray-700 uppercase"
      }, "When"), m("div", {
        "class": "inline-flex items-center gap-2 text-sm text-gray-500"
      }, m("div", {
        "class": "flex items-center"
      }, m("select", {
        "aria-label": "match-type",
        "class": "h-full py-1 pl-3 pr-8 font-medium text-gray-700 bg-transparent form-select sm:text-sm sm:leading-5",
        onchange: function onchange(e) {
          _this.planCondition = e.target.value;
        }
      }, m("option", {
        value: "AND",
        selected: this.planCondition == "AND" ? "selected" : ""
      }, "all"), m("option", {
        value: "OR",
        selected: this.planCondition == "OR" ? "selected" : ""
      }, "at least one"))), " ", m("span", null, "condition groups pass"), " ")), this.ruleGroups.map(function (ruleGroupObj, id) {
        return m(CommissionRuleGroup_CommissionRuleGroup, {
          key: ruleGroupObj.key,
          index: id,
          isOnlyOne: _this.ruleGroups.length === 1,
          callbackToParent: _this.updateData.bind(_this),
          ruleGroup: ruleGroupObj
        });
      })))));
    }
  }]);

  return CommissionDetail;
}();


// CONCATENATED MODULE: ./src/admin/views/commission/CommissionList.js
function CommissionList_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CommissionList_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CommissionList_createClass(Constructor, protoProps, staticProps) { if (protoProps) CommissionList_defineProperties(Constructor.prototype, protoProps); if (staticProps) CommissionList_defineProperties(Constructor, staticProps); return Constructor; }







var CommissionList_CommissionList = /*#__PURE__*/function () {
  function CommissionList(vnode) {
    CommissionList_classCallCheck(this, CommissionList);

    this.params = {
      id: vnode.attrs.id || 0
    };
    this.currentDraggingItem = null;
    this.currentOrderString = null;
    this.showSaveBtn = null;
  }

  CommissionList_createClass(CommissionList, [{
    key: "refreshModel",
    value: function refreshModel(currentCommissionId) {
      this.params.id = currentCommissionId;
      this.model = new Commission_Commission();
    }
  }, {
    key: "dragStart",
    value: function dragStart(e, item) {
      this.currentDraggingItem = item;
      if (!this.currentOrderString) this.currentOrderString = JSON.stringify(CommissionDashboardModel_CommissionDashboardModel.data.planOrder);
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/html', null);
    }
  }, {
    key: "drop",
    value: function drop(e, item) {
      e.preventDefault();
      var dropId = parseInt(item.commissionId);

      if (dropId !== parseInt(this.currentDraggingItem.commissionId)) {
        var from = CommissionDashboardModel_CommissionDashboardModel.data.planOrder.indexOf(parseInt(this.currentDraggingItem.commissionId));
        var to = CommissionDashboardModel_CommissionDashboardModel.data.planOrder.indexOf(dropId);
        CommissionDashboardModel_CommissionDashboardModel.data.planOrder.splice(to, 0, CommissionDashboardModel_CommissionDashboardModel.data.planOrder.splice(from, 1)[0]);
      }

      CommissionDashboardModel_CommissionDashboardModel.reOrder();

      if (this.currentOrderString != JSON.stringify(CommissionDashboardModel_CommissionDashboardModel.data.planOrder)) {
        this.showSaveBtn = true;
      } else {
        this.showSaveBtn = false;
      }
    }
  }, {
    key: "dragOver",
    value: function dragOver(e) {
      e.preventDefault();
    }
  }, {
    key: "savePlanOrder",
    value: function savePlanOrder() {
      CommissionDashboardModel_CommissionDashboardModel.savePlanOrder();
      this.showSaveBtn = false;
      NotificationModel["a" /* default */].flags.showNotification = 1;
      NotificationModel["a" /* default */].notification.message = "Plan order saved successfully";
      NotificationModel["a" /* default */].notification.status = 'success';
    }
  }, {
    key: "view",
    value: function view() {
      var _this = this;

      return m("section", null, m("h3", {
        "class": "text-lg font-bold text-gray-700 sm:text-3xl leading-8"
      }, "Commission Plans"), m("div", {
        "class": "text-right px-2"
      }, m("a", {
        "class": (!this.showSaveBtn ? "invisible" : "") + " text-sm font-medium leading-6 text-indigo-600 hover:text-indigo-800",
        href: "#",
        onclick: function onclick(e) {
          e.preventDefault();

          _this.savePlanOrder();
        }
      }, "Save Order")), m("ul", {
        "class": "text-gray-600 py-2 max-h-screen overflow-auto"
      }, CommissionDashboardModel_CommissionDashboardModel.data.commissions.map(function (item, key) {
        return m("li", {
          "data-id": item.commissionId,
          "class": "my-6 overflow-hidden text-xs text-gray-400 transition duration-150 ease-in-out bg-white rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50",
          onclick: function onclick() {
            Commission_Commission.currentCommissionID = item.commissionId;

            _this.refreshModel(Commission_Commission.currentCommissionID);
          },
          draggable: "true",
          ondragstart: function ondragstart(e) {
            _this.dragStart(e, item);
          },
          ondrop: function ondrop(e) {
            return _this.drop(e, item);
          },
          ondragover: function ondragover(e) {
            _this.dragOver(e);
          }
        }, m("a", {
          "class": "block py-3 cursor-pointer hover:bg-white focus:outline-none focus:bg-white transition duration-150 ease-in-out" + (Commission_Commission.currentCommissionID == item.commissionId ? " bg-white outline-none" : "")
        }, m("div", {
          "class": "flex items-center pl-4 py-2"
        }, m("div", {
          "class": "mx-2"
        }, m("svg", {
          className: "w-4 h-4",
          fill: "none",
          stroke: "currentColor",
          viewBox: "0 0 24 24",
          xmlns: "http://www.w3.org/2000/svg"
        }, m("path", {
          strokeLinecap: "round",
          strokeLinejoin: "round",
          strokeWidth: 2,
          d: "M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
        }))), m("div", {
          "class": (item.status === 'Draft' ? "text-gray-400" : "text-gray-700") + " text-lg pr-4 flex-1 sm:truncate leading-6"
        }, item.name), m("div", {
          "class": "mx-2"
        }, m("svg", {
          "class": "h-5 w-5 text-gray-400",
          fill: "currentColor",
          viewBox: "0 0 20 20"
        }, m("path", {
          "fill-rule": "evenodd",
          d: "M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z",
          "clip-rule": "evenodd"
        }))))));
      })), Commission_Commission.currentCommissionID !== 0 ? m(CommissionDetail_CommissionDetail, {
        id: Commission_Commission.currentCommissionID
      }) : '', NotificationModel["a" /* default */].flags.showNotification ? m(Notification_AFWCNotifications, null) : null);
    }
  }]);

  return CommissionList;
}();


Commission_Commission.currentCommissionID = 0;
// CONCATENATED MODULE: ./src/admin/views/commission/CommissionMain.js
function CommissionMain_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CommissionMain_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CommissionMain_createClass(Constructor, protoProps, staticProps) { if (protoProps) CommissionMain_defineProperties(Constructor.prototype, protoProps); if (staticProps) CommissionMain_defineProperties(Constructor, staticProps); return Constructor; }



var CommissionMain_CommissionMain = /*#__PURE__*/function () {
  function CommissionMain() {
    CommissionMain_classCallCheck(this, CommissionMain);
  }

  CommissionMain_createClass(CommissionMain, [{
    key: "view",
    value: function view(vnode) {
      return m("main", {
        "class": "max-w-7xl mx-auto my-4 py-2 px-8 grid gap-8 grid-cols-3"
      }, m("section", {
        "class": "col-span-2 overflow-y-auto"
      }, m(CommissionList_CommissionList, null)), m("section", {
        "class": "col-span-1 space-y-6 text-gray-500 pt-20 pr-8"
      }, m("p", null, "While calculating affiliate commission, plans are validated one after the other, starting from the top. Drag and Drop to reorder and prioritize plans. Click on Save Order to save.")));
    }
  }]);

  return CommissionMain;
}();


// CONCATENATED MODULE: ./src/admin/views/CommissionDashboard.js
function CommissionDashboard_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CommissionDashboard_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CommissionDashboard_createClass(Constructor, protoProps, staticProps) { if (protoProps) CommissionDashboard_defineProperties(Constructor.prototype, protoProps); if (staticProps) CommissionDashboard_defineProperties(Constructor, staticProps); return Constructor; }










var CommissionDashboard_CommissionsDashboard = /*#__PURE__*/function () {
  CommissionDashboard_createClass(CommissionsDashboard, [{
    key: "initialize",
    value: function initialize(params) {
      this.urlParams = Object.keys(params).length ? params : {
        start_date: new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().slice(0, 10),
        end_date: new Date().toISOString().slice(0, 10)
      };
      this.model = new CommissionDashboardModel_CommissionDashboardModel(this.urlParams);
    }
  }]);

  function CommissionsDashboard(vnode) {
    CommissionDashboard_classCallCheck(this, CommissionsDashboard);

    this.initialize(vnode.attrs);
  }

  CommissionDashboard_createClass(CommissionsDashboard, [{
    key: "onupdate",
    value: function onupdate(vnode) {
      if (this.urlParams != vnode.attrs && Object.keys(vnode.attrs).length) {
        this.initialize(vnode.attrs);
      }
    }
  }, {
    key: "view",
    value: function view() {
      return m("div", null, m(NavBar_AFWNavBar, {
        startDate: this.urlParams.start_date,
        endDate: this.urlParams.end_date
      }), CommissionDashboardModel_CommissionDashboardModel.data.commissions.length > 0 ? m('[', null, m(CommissionMain_CommissionMain, null)) : !Loader["a" /* default */].showLoader ? m("header", {
        "class": "bg-white shadow"
      }, m("div", {
        "class": "max-w-7xl mx-auto py-40 sm:px-3 xl:px-8 text-center text-gray-500"
      }, m("p", {
        "class": "text-xl font-medium leading-8"
      }, "No commission plans yet."), m("div", {
        "class": "text-lg mx-auto leading-8 max-w-md mt-6 space-y-4"
      }, m("p", null, "Go ahead, click that \"Add a Plan\" button in the top bar to create your first commission."))), Commission_Commission.currentCommissionID === -1 ? m(CommissionDetail_CommissionDetail, {
        id: Commission_Commission.currentCommissionID
      }) : '') : "", NotificationModel["a" /* default */].flags.showNotification ? m(Notification_AFWCNotifications, null) : null);
    }
  }]);

  return CommissionsDashboard;
}();


// CONCATENATED MODULE: ./src/admin/index.js




var admin_element = document.getElementById('afw-admin-dasboard');
m.route(admin_element, "/dashboard", {
  "/dashboard": {
    view: function view(vnode) {
      return [m(Dashboard_Dashboard, vnode.attrs)];
    }
  },
  "/campaigns": {
    view: function view(vnode) {
      return [m(CampaignsDashboard_CampaignsDashboard, vnode.attrs)];
    }
  },
  "/plans": {
    view: function view(vnode) {
      return [m(CommissionDashboard_CommissionsDashboard, vnode.attrs)];
    }
  }
});

/***/ })
/******/ ]);