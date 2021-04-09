/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 7);
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
  }, {
    key: "getDateTime",
    value: function getDateTime(dateStr) {
      var currentTimestr = new Date().toTimeString();
      var str = new Date(dateStr).toDateString();
      return new Date(str + " " + currentTimestr).toUTCString();
    }
  }]);

  return AFWCFunctions;
}();



/***/ }),
/* 5 */,
/* 6 */,
/* 7 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXTERNAL MODULE: ./src/admin/models/campaign/CampaignDashboardModel.js
var CampaignDashboardModel = __webpack_require__(3);

// EXTERNAL MODULE: ./src/admin/models/campaign/Campaign.js
var Campaign = __webpack_require__(1);

// CONCATENATED MODULE: ./src/frontend/views/CampaignDetail.js
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var CampaignDetail_CampaignDetail = /*#__PURE__*/function () {
  function CampaignDetail() {
    _classCallCheck(this, CampaignDetail);
  }

  _createClass(CampaignDetail, [{
    key: "view",
    value: function view(vnode) {
      if (Campaign["a" /* default */].details.slug != '') {
        Campaign["a" /* default */].details.targetLink += (Campaign["a" /* default */].details.targetLink.match(/[\?]/g) ? '&' : '?') + 'utm_campaign=' + Campaign["a" /* default */].details.slug;
      }

      Campaign["a" /* default */].details.targetLink += (Campaign["a" /* default */].details.targetLink.match(/[\?]/g) ? '&' : '?') + afwcDashboardParams.pname + '=' + afwcDashboardParams.affiliate_id;
      return m("aside", {
        "class": "fixed inset-0 overflow-hidden",
        style: "z-index:120000"
      }, m("div", {
        "class": "absolute inset-0 overflow-hidden"
      }, m("div", {
        "class": "absolute inset-0 bg-gray-500 transition-opacity opacity-75"
      }), m("section", {
        "class": "absolute inset-y-0 right-0 pl-20 max-w-full flex"
      }, m("div", {
        "class": "w-screen max-w-4xl"
      }, m("div", {
        "class": "h-full flex flex-col space-y-6 py-4 bg-white shadow-xl overflow-y-scroll"
      }, m("header", {
        "class": "px-4 sm:px-6"
      }, m("div", {
        "class": "flex items-start justify-between space-x-3"
      }, m("h2", {
        "class": "text-3xl leading-8 text-gray-900"
      }, Campaign["a" /* default */].details.title || ""), m("div", {
        "class": "h-7 flex items-center space-x-3"
      }, m("button", {
        "aria-label": "Close panel",
        "class": "leading-none text-gray-400 hover:text-gray-500 transition ease-in-out duration-150 p-0",
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
      }, m("p", {
        "class": "text-lg"
      }, Campaign["a" /* default */].details.shortDescription || ""), m("p", {
        "class": "mt-4"
      }, "Link: ", m("code", null, Campaign["a" /* default */].details.targetLink || "")), m("div", {
        "class": "mt-12 mb-4"
      }, m.trust(Campaign["a" /* default */].details.body || "")))))))));
    }
  }]);

  return CampaignDetail;
}();


// CONCATENATED MODULE: ./src/frontend/views/CampaignList.js
function CampaignList_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function CampaignList_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function CampaignList_createClass(Constructor, protoProps, staticProps) { if (protoProps) CampaignList_defineProperties(Constructor.prototype, protoProps); if (staticProps) CampaignList_defineProperties(Constructor, staticProps); return Constructor; }





var CampaignList_CampaignList = /*#__PURE__*/function () {
  function CampaignList(vnode) {
    CampaignList_classCallCheck(this, CampaignList);

    this.params = {
      id: vnode.attrs.id || 0,
      startDate: vnode.attrs.startDate || '',
      endDate: vnode.attrs.endDate || ''
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

      return m("div", null, m("div", null, m("ul", {
        "class": "text-gray-600 m-0 w-full"
      }, CampaignDashboardModel["a" /* default */].data.campaigns.map(function (item, key) {
        return m("li", {
          style: "cursor:pointer",
          "class": "my-2 overflow-hidden text-gray-400 bg-white shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50",
          onclick: function onclick() {
            Campaign["a" /* default */].currentCampaignID = item.campaignId;

            _this.refreshModel(Campaign["a" /* default */].currentCampaignID);
          }
        }, m("div", {
          "class": "flex items-center pl-4 py-2"
        }, m("div", {
          "class": "text-gray-700 text-lg pr-4 flex-1 sm:truncate leading-6"
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
        })))));
      }))), Campaign["a" /* default */].currentCampaignID !== 0 ? m(CampaignDetail_CampaignDetail, {
        id: Campaign["a" /* default */].currentCampaignID
      }) : '');
    }
  }]);

  return CampaignList;
}();


Campaign["a" /* default */].currentCampaignID = 0;
// CONCATENATED MODULE: ./src/frontend/views/CampaignsDashboard.js
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
      return m("div", null, CampaignDashboardModel["a" /* default */].data.campaigns.length > 0 ? m('[', null, m(CampaignList_CampaignList, null)) : m("p", {
        "class": "text-xl"
      }, afwcDashboardParams.no_campaign_string));
    }
  }]);

  return CampaignsDashboard;
}();


// CONCATENATED MODULE: ./src/frontend/index.js

var frontend_element = document.getElementById('afw-campaigns');
m.route(frontend_element, "/campaigns", {
  "/campaigns": {
    view: function view(vnode) {
      return [m(CampaignsDashboard_CampaignsDashboard, vnode.attrs)];
    }
  }
});

/***/ })
/******/ ]);