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
/******/ 	return __webpack_require__(__webpack_require__.s = "./admin/assets/src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./admin/assets/src/base/components/button/index.js":
/*!**********************************************************!*\
  !*** ./admin/assets/src/base/components/button/index.js ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ "./node_modules/@babel/runtime/helpers/extends.js");
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_3__);



/**
 * External dependencies
 */


/* harmony default export */ __webpack_exports__["default"] = (function (props) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["Button"], _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default()({}, props, {
    // Add is-button class that woocommerce css is expecting
    className: classnames__WEBPACK_IMPORTED_MODULE_3___default()('automatewoo-button is-button', props.className)
  }), props.children);
});

/***/ }),

/***/ "./admin/assets/src/base/components/index.js":
/*!***************************************************!*\
  !*** ./admin/assets/src/base/components/index.js ***!
  \***************************************************/
/*! exports provided: ProgressBar, Button, WorkflowSearch */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _progress_bar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./progress-bar */ "./admin/assets/src/base/components/progress-bar/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "ProgressBar", function() { return _progress_bar__WEBPACK_IMPORTED_MODULE_0__["default"]; });

/* harmony import */ var _button__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./button */ "./admin/assets/src/base/components/button/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Button", function() { return _button__WEBPACK_IMPORTED_MODULE_1__["default"]; });

/* harmony import */ var _workflow_search__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./workflow-search */ "./admin/assets/src/base/components/workflow-search/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "WorkflowSearch", function() { return _workflow_search__WEBPACK_IMPORTED_MODULE_2__["default"]; });

/**
 * Export all components
 */




/***/ }),

/***/ "./admin/assets/src/base/components/progress-bar/index.js":
/*!****************************************************************!*\
  !*** ./admin/assets/src/base/components/progress-bar/index.js ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_2__);


/**
 * External dependencies
 */



var ProgressBar = function ProgressBar(_ref) {
  var progress = _ref.progress;
  var progressString = "".concat(Math.min(progress, 100), "%");
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-progress-bar-component"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-progress-bar-component__fill",
    style: {
      width: progressString
    }
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", {
    className: "screen-reader-text"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["sprintf"])( // translators: %s: the progress percentage
  Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Progress: %s', 'automatewoo'), progressString))));
};

ProgressBar.propTypes = {
  progress: prop_types__WEBPACK_IMPORTED_MODULE_2___default.a.number.isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (ProgressBar);

/***/ }),

/***/ "./admin/assets/src/base/components/workflow-search/index.js":
/*!*******************************************************************!*\
  !*** ./admin/assets/src/base/components/workflow-search/index.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @woocommerce/components */ "@woocommerce/components");
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_components__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../utils */ "./admin/assets/src/base/utils.js");






function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

/**
 * External dependencies
 */






/**
 * Internal dependencies
 */



var WorkflowSearch = function WorkflowSearch(_ref) {
  var label = _ref.label,
      _ref$requestParams = _ref.requestParams,
      requestParams = _ref$requestParams === void 0 ? {} : _ref$requestParams,
      onChange = _ref.onChange,
      placeholder = _ref.placeholder,
      selected = _ref.selected;

  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__["useState"])([]),
      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_3___default()(_useState, 2),
      options = _useState2[0],
      setOptions = _useState2[1];

  var onSearch = /*#__PURE__*/function () {
    var _ref2 = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee(prevOptions, query) {
      var workflows, newOptions;
      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              if (query) {
                _context.next = 2;
                break;
              }

              return _context.abrupt("return", []);

            case 2:
              _context.prev = 2;
              _context.next = 5;
              return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_9___default()({
                path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_8__["addQueryArgs"])('/automatewoo/workflows', _objectSpread(_objectSpread({}, requestParams), {}, {
                  search: query
                }))
              });

            case 5:
              workflows = _context.sent;
              newOptions = workflows.map(function (workflow) {
                return {
                  key: workflow.id.toString(),
                  label: workflow.title,
                  value: workflow
                };
              });
              setOptions(newOptions);
              return _context.abrupt("return", newOptions);

            case 11:
              _context.prev = 11;
              _context.t0 = _context["catch"](2);
              Object(_utils__WEBPACK_IMPORTED_MODULE_10__["handleFetchError"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__["__"])('There was an error when searching for workflows.', 'automatewoo'), _context.t0);
              return _context.abrupt("return", []);

            case 15:
            case "end":
              return _context.stop();
          }
        }
      }, _callee, null, [[2, 11]]);
    }));

    return function onSearch(_x, _x2) {
      return _ref2.apply(this, arguments);
    };
  }();

  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__["createElement"])(_woocommerce_components__WEBPACK_IMPORTED_MODULE_6__["SelectControl"], {
    isSearchable: true,
    label: label,
    onChange: onChange,
    onSearch: onSearch,
    options: options,
    placeholder: placeholder,
    searchDebounceTime: 500 // I'm not sure this prop actual debounces the search
    ,
    selected: selected,
    showClearButton: true // Doesn't appear to be implemented

  });
};

WorkflowSearch.propTypes = {
  /**
   * Params to add to the API request
   */
  requestParams: prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.object,

  /**
   * Function called when selected results change, passed result list.
   */
  onChange: prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.func.isRequired,

  /**
   * A placeholder for the search input.
   */
  placeholder: prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.string.isRequired,

  /**
   * An array of objects describing selected values or optionally a string
   * for a single value.
   */
  selected: prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.oneOfType([prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.string, prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.arrayOf(prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.shape({
    key: prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.oneOfType([prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.number, prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.string]).isRequired,
    label: prop_types__WEBPACK_IMPORTED_MODULE_7___default.a.string
  }))]).isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (WorkflowSearch);

/***/ }),

/***/ "./admin/assets/src/base/hooks/index.js":
/*!**********************************************!*\
  !*** ./admin/assets/src/base/hooks/index.js ***!
  \**********************************************/
/*! exports provided: useBeforeUnload */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _use_before_unload__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./use-before-unload */ "./admin/assets/src/base/hooks/use-before-unload.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useBeforeUnload", function() { return _use_before_unload__WEBPACK_IMPORTED_MODULE_0__["useBeforeUnload"]; });



/***/ }),

/***/ "./admin/assets/src/base/hooks/use-before-unload.js":
/*!**********************************************************!*\
  !*** ./admin/assets/src/base/hooks/use-before-unload.js ***!
  \**********************************************************/
/*! exports provided: useBeforeUnload */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useBeforeUnload", function() { return useBeforeUnload; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */

/**
 * useBeforeUnload hook.
 *
 * Most browser will replace the custom before unload message.
 *
 * @param {string} message The message to show before unload.
 *                         If empty string no message will be shown.
 */

var useBeforeUnload = function useBeforeUnload(message) {
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(function () {
    var eventListener = function eventListener(event) {
      if (message) {
        event.returnValue = message;
        return event.returnValue;
      }
    };

    window.addEventListener('beforeunload', eventListener);
    return function () {
      window.removeEventListener('beforeunload', eventListener);
    };
  }, [message]);
};

/***/ }),

/***/ "./admin/assets/src/base/usage-tracking/index.js":
/*!*******************************************************!*\
  !*** ./admin/assets/src/base/usage-tracking/index.js ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
window.wcTracks = window.wcTracks || {};

window.wcTracks.recordEvent = window.wcTracks.recordEvent || function () {};

window.wcTracks.isEnabled = window.wcTracks.isEnabled || false;
/**
 * No-op function when Tracks is disabled.
 */

var recordTracksEvent = function recordTracksEvent() {};

if (window.wcTracks.isEnabled) {
  /**
   * Record a tracking event for AutomateWoo.
   *
   * @param {string} eventName
   * @param {Object} data
   */
  recordTracksEvent = function recordTracksEvent(eventName, data) {
    window.wcTracks.recordEvent("aw_".concat(eventName), data);
  };
}

/* harmony default export */ __webpack_exports__["default"] = (recordTracksEvent);

/***/ }),

/***/ "./admin/assets/src/base/utils.js":
/*!****************************************!*\
  !*** ./admin/assets/src/base/utils.js ***!
  \****************************************/
/*! exports provided: getWorkflowEditUrl, handleFetchError, getWorkflow */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getWorkflowEditUrl", function() { return getWorkflowEditUrl; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "handleFetchError", function() { return handleFetchError; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getWorkflow", function() { return getWorkflow; });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../settings */ "./admin/assets/src/settings.js");



/**
 * External dependencies
 */


/**
 * Internal dependencies
 */


var getWorkflowEditUrl = function getWorkflowEditUrl(workflowId) {
  return "".concat(_settings__WEBPACK_IMPORTED_MODULE_4__["ADMIN_URL"], "post.php?post=").concat(workflowId, "&action=edit");
};
function handleFetchError(noticeText, error) {
  var _dispatch = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["dispatch"])('core/notices'),
      createNotice = _dispatch.createNotice;

  createNotice('error', noticeText);
}
/**
 * Get a single workflow.
 *
 * @param {number} workflowId
 * @return {Promise<boolean>} Fetch request promise.
 * @throws Error
 */

function getWorkflow(_x) {
  return _getWorkflow.apply(this, arguments);
}

function _getWorkflow() {
  _getWorkflow = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee(workflowId) {
    var workflow;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            _context.next = 2;
            return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({
              path: "/automatewoo/workflows/".concat(workflowId)
            });

          case 2:
            workflow = _context.sent;

            if (!workflow) {
              _context.next = 5;
              break;
            }

            return _context.abrupt("return", workflow);

          case 5:
            throw new Error();

          case 6:
          case "end":
            return _context.stop();
        }
      }
    }, _callee);
  }));
  return _getWorkflow.apply(this, arguments);
}

/***/ }),

/***/ "./admin/assets/src/index.js":
/*!***********************************!*\
  !*** ./admin/assets/src/index.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _manual_workflow_runner__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./manual-workflow-runner */ "./admin/assets/src/manual-workflow-runner/index.js");


/**
 * External dependencies
 */


/**
 * Internal dependencies
 */


Object(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_2__["addFilter"])('woocommerce_admin_pages_list', 'automatewoo', function (pages) {
  return [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(pages), [{
    breadcrumbs: [Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('AutomateWoo', 'automatewoo'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Workflows', 'automatewoo'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Manual Runner', 'automatewoo')],
    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('AutomateWoo Manual Workflow Runner', 'automatewoo'),
    container: _manual_workflow_runner__WEBPACK_IMPORTED_MODULE_3__["default"],
    path: '/automatewoo/manual-workflow-runner',
    wpOpenMenu: 'toplevel_page_automatewoo'
  }]);
});

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/api-utils.js":
/*!**************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/api-utils.js ***!
  \**************************************************************/
/*! exports provided: getWorkflowQuickFilterData, getWorkflowMatchingItems, addItemBatchToQueue */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getWorkflowQuickFilterData", function() { return getWorkflowQuickFilterData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getWorkflowMatchingItems", function() { return getWorkflowMatchingItems; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addItemBatchToQueue", function() { return addItemBatchToQueue; });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../settings */ "./admin/assets/src/settings.js");



/**
 * External Dependencies
 */

/**
 * Internal dependencies
 */


/**
 * @param {number} workflowId
 * @return {Promise<*>} Post request promise.
 * @throws Error
 */

function getWorkflowQuickFilterData(_x) {
  return _getWorkflowQuickFilterData.apply(this, arguments);
}
/**
 * @param {number} workflowId
 * @param {string} ruleGroupNumber
 * @param {number} ruleGroupOffset
 * @return {Promise<*>} Post request promise.
 * @throws Error
 */

function _getWorkflowQuickFilterData() {
  _getWorkflowQuickFilterData = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee(workflowId) {
    var data;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            _context.next = 2;
            return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
              path: "/automatewoo/manual-workflow-runner/quick-filter-data/".concat(workflowId)
            });

          case 2:
            data = _context.sent;

            if (!data) {
              _context.next = 5;
              break;
            }

            return _context.abrupt("return", data);

          case 5:
            throw new Error();

          case 6:
          case "end":
            return _context.stop();
        }
      }
    }, _callee);
  }));
  return _getWorkflowQuickFilterData.apply(this, arguments);
}

function getWorkflowMatchingItems(_x2, _x3, _x4) {
  return _getWorkflowMatchingItems.apply(this, arguments);
}
/**
 * Add items to queue.
 *
 * @param {number} workflowId
 * @param {Array} batch
 * @return {Promise<*>} Post request promise.
 * @throws Error
 */

function _getWorkflowMatchingItems() {
  _getWorkflowMatchingItems = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2(workflowId, ruleGroupNumber, ruleGroupOffset) {
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            _context2.next = 2;
            return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
              path: "/automatewoo/manual-workflow-runner/find-matches/".concat(workflowId),
              method: 'POST',
              data: {
                offset: ruleGroupOffset,
                batch_size: _settings__WEBPACK_IMPORTED_MODULE_3__["MANUAL_WORKFLOWS_BATCH_SIZE"],
                rule_group: ruleGroupNumber
              }
            });

          case 2:
            return _context2.abrupt("return", _context2.sent);

          case 3:
          case "end":
            return _context2.stop();
        }
      }
    }, _callee2);
  }));
  return _getWorkflowMatchingItems.apply(this, arguments);
}

function addItemBatchToQueue(_x5, _x6) {
  return _addItemBatchToQueue.apply(this, arguments);
}

function _addItemBatchToQueue() {
  _addItemBatchToQueue = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee3(workflowId, batch) {
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee3$(_context3) {
      while (1) {
        switch (_context3.prev = _context3.next) {
          case 0:
            _context3.next = 2;
            return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
              path: "/automatewoo/manual-workflow-runner/add-items-to-queue/".concat(workflowId),
              method: 'POST',
              data: {
                batch: batch
              }
            });

          case 2:
            return _context3.abrupt("return", _context3.sent);

          case 3:
          case "end":
            return _context3.stop();
        }
      }
    }, _callee3);
  }));
  return _addItemBatchToQueue.apply(this, arguments);
}

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/find-items-step/data.js":
/*!*************************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/find-items-step/data.js ***!
  \*************************************************************************/
/*! exports provided: getCurrentProgressGroupNumber, useFindItemsReducer */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCurrentProgressGroupNumber", function() { return getCurrentProgressGroupNumber; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useFindItemsReducer", function() { return useFindItemsReducer; });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils */ "./admin/assets/src/manual-workflow-runner/utils.js");
/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../settings */ "./admin/assets/src/settings.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);


function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }




var initialState = {
  status: _utils__WEBPACK_IMPORTED_MODULE_1__["STEP_STATUSES"].PENDING,
  progress: {},
  progressPercent: 0,
  items: {}
};

var reducer = function reducer(state, action) {
  switch (action.type) {
    case 'FIND_ITEMS_REQUEST':
      if (state.status === _utils__WEBPACK_IMPORTED_MODULE_1__["STEP_STATUSES"].REQUESTING) {
        return state;
      }

      return _objectSpread(_objectSpread({}, state), {}, {
        status: _utils__WEBPACK_IMPORTED_MODULE_1__["STEP_STATUSES"].REQUESTING
      });

    case 'FIND_ITEMS_ERROR':
      if (state.status === _utils__WEBPACK_IMPORTED_MODULE_1__["STEP_STATUSES"].ERROR) {
        return state;
      }

      return _objectSpread(_objectSpread({}, state), {}, {
        status: _utils__WEBPACK_IMPORTED_MODULE_1__["STEP_STATUSES"].ERROR
      });

    case 'FIND_ITEMS_SUCCESS':
      var newProgress = incrementProgressData(state.progress);
      var progressPercent = calculateProgressPercentage(newProgress);
      return _objectSpread(_objectSpread({}, state), {}, {
        status: progressPercent === 100 ? _utils__WEBPACK_IMPORTED_MODULE_1__["STEP_STATUSES"].COMPLETE : _utils__WEBPACK_IMPORTED_MODULE_1__["STEP_STATUSES"].PENDING,
        // IMPORTANT: Don't add duplicate items
        items: _objectSpread(_objectSpread({}, action.items), state.items),
        progress: newProgress,
        progressPercent: progressPercent
      });

    default:
      return state;
  }
};
/**
 * @param {Object} progressData
 * @return {string|null} The index of the current progress group or null.
 */


var getCurrentProgressGroupNumber = function getCurrentProgressGroupNumber(progressData) {
  for (var groupNumber in progressData) {
    var group = progressData[groupNumber];

    if (!group.complete) {
      return groupNumber;
    }
  }

  return null;
};
/**
 * Updates progressData by incrementing the current group.
 *
 * @param {Object} progressData
 * @return {Object} Of new progress data.
 */

var incrementProgressData = function incrementProgressData(progressData) {
  var currentGroupNumber = getCurrentProgressGroupNumber(progressData);

  if (null === currentGroupNumber) {
    return progressData;
  }

  var currentGroup = progressData[currentGroupNumber]; // Increment the batch

  currentGroup.offset += _settings__WEBPACK_IMPORTED_MODULE_2__["MANUAL_WORKFLOWS_BATCH_SIZE"]; // Mark group as complete if required

  if (currentGroup.offset >= currentGroup.total) {
    currentGroup.complete = true;
  }

  progressData[currentGroupNumber] = currentGroup;
  return progressData;
};
/**
 * Calculate progress percentage.
 *
 * @param {Object} progressData
 * @return {number} The percentage.
 */


var calculateProgressPercentage = function calculateProgressPercentage(progressData) {
  var total = 0;
  var complete = 0;

  for (var groupNumber in progressData) {
    var group = progressData[groupNumber];
    total += group.total;
    complete += group.complete ? group.total : group.offset;
  }

  if (0 === complete || 0 === total) {
    return 0;
  }

  var progress = Math.round(complete / total * 100);
  return progress > 100 ? 100 : progress;
};
/**
 * useFindItemsReducer.
 *
 * @param {Array} possibleResultCounts
 * @return {Array} Containing reducer state and dispatch function.
 */


var useFindItemsReducer = function useFindItemsReducer(possibleResultCounts) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useReducer"])(reducer, initialState, function (state) {
    // Set up object for storing progress
    possibleResultCounts.forEach(function (group) {
      state.progress[group.group_number] = {
        offset: 0,
        total: group.count,
        complete: false
      };
    });
    return state;
  });
};

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/find-items-step/high-volume-warning.js":
/*!****************************************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/find-items-step/high-volume-warning.js ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _next_button__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../next-button */ "./admin/assets/src/manual-workflow-runner/next-button.js");
/* harmony import */ var _base_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../base/components */ "./admin/assets/src/base/components/index.js");
/* harmony import */ var _base_utils__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../base/utils */ "./admin/assets/src/base/utils.js");
/* harmony import */ var _large_text_and_icon__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../large-text-and-icon */ "./admin/assets/src/manual-workflow-runner/large-text-and-icon.js");


/**
 * External dependencies
 */



/**
 * Internal dependencies
 */






var HighVolumeWarning = function HighVolumeWarning(_ref) {
  var dismissWarning = _ref.dismissWarning,
      possibleResultsCount = _ref.possibleResultsCount,
      primaryDataTypePluralName = _ref.primaryDataTypePluralName,
      workflowId = _ref.workflowId;
  var workflowUrl = Object(_base_utils__WEBPACK_IMPORTED_MODULE_6__["getWorkflowEditUrl"])(workflowId);
  var warningText = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["sprintf"])( // translators: %(count)d: number of items, %(dataType)s: type of item e.g. 'orders'
  Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('This workflow could potentially match %(count)d %(dataType)s which will take ' + 'some time to process. Try adding more rules to the workflow ' + 'so there are fewer matches.', 'automatewoo'), {
    count: possibleResultsCount,
    dataType: primaryDataTypePluralName
  });
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_large_text_and_icon__WEBPACK_IMPORTED_MODULE_7__["default"], {
    text: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("strong", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Warning!', 'automatewoo'), " "), warningText),
    icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Dashicon"], {
      icon: "warning",
      size: "60"
    })
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-workflow-runner-buttons"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_base_components__WEBPACK_IMPORTED_MODULE_5__["Button"], {
    isDefault: true,
    isLarge: true,
    href: workflowUrl
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Edit workflow', 'automatewoo')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_next_button__WEBPACK_IMPORTED_MODULE_4__["default"], {
    onClick: dismissWarning
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Continue anyway', 'automatewoo'))));
};

HighVolumeWarning.propTypes = {
  dismissWarning: prop_types__WEBPACK_IMPORTED_MODULE_3___default.a.func.isRequired,
  possibleResultsCount: prop_types__WEBPACK_IMPORTED_MODULE_3___default.a.number.isRequired,
  primaryDataTypePluralName: prop_types__WEBPACK_IMPORTED_MODULE_3___default.a.string.isRequired,
  workflowId: prop_types__WEBPACK_IMPORTED_MODULE_3___default.a.number.isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (HighVolumeWarning);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/find-items-step/index.js":
/*!**************************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/find-items-step/index.js ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @woocommerce/components */ "@woocommerce/components");
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../settings */ "./admin/assets/src/settings.js");
/* harmony import */ var _high_volume_warning__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./high-volume-warning */ "./admin/assets/src/manual-workflow-runner/find-items-step/high-volume-warning.js");
/* harmony import */ var _item_finder__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./item-finder */ "./admin/assets/src/manual-workflow-runner/find-items-step/item-finder.js");
/* harmony import */ var _data__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./data */ "./admin/assets/src/manual-workflow-runner/find-items-step/data.js");
/* harmony import */ var _no_results__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./no-results */ "./admin/assets/src/manual-workflow-runner/find-items-step/no-results.js");



/**
 * External dependencies
 */




/**
 * Internal dependencies
 */







var FindItemsStep = function FindItemsStep(_ref) {
  var workflow = _ref.workflow,
      onStepCancel = _ref.onStepCancel,
      onStepComplete = _ref.onStepComplete,
      workflowQuickFilterData = _ref.workflowQuickFilterData,
      possibleResultsCount = _ref.possibleResultsCount;
  var primaryDataTypePluralName = workflowQuickFilterData.primaryDataTypePluralName,
      possibleResultCounts = workflowQuickFilterData.possibleResultCounts;

  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState, 2),
      dismissedHighVolumeWarning = _useState2[0],
      setDismissedHighVolumeWarning = _useState2[1];

  var _useFindItemsReducer = Object(_data__WEBPACK_IMPORTED_MODULE_8__["useFindItemsReducer"])(possibleResultCounts),
      _useFindItemsReducer2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useFindItemsReducer, 2),
      findItemsState = _useFindItemsReducer2[0],
      findItemsDispatch = _useFindItemsReducer2[1]; // Logic for high volume warning


  var showHighVolumeWarning = false;

  if (!dismissedHighVolumeWarning && possibleResultsCount > _settings__WEBPACK_IMPORTED_MODULE_5__["MANUAL_WORKFLOWS_HIGH_VOLUME_THRESHOLD"]) {
    showHighVolumeWarning = true;
  }

  var cardBody;

  if (possibleResultsCount === 0) {
    cardBody = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_no_results__WEBPACK_IMPORTED_MODULE_9__["default"], {
      dataType: primaryDataTypePluralName,
      workflowId: workflow.id
    });
  } else if (showHighVolumeWarning) {
    cardBody = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_high_volume_warning__WEBPACK_IMPORTED_MODULE_6__["default"], {
      dismissWarning: function dismissWarning() {
        return setDismissedHighVolumeWarning(true);
      },
      possibleResultsCount: possibleResultsCount,
      primaryDataTypePluralName: primaryDataTypePluralName,
      workflowId: workflow.id
    });
  } else {
    cardBody = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_item_finder__WEBPACK_IMPORTED_MODULE_7__["default"], {
      state: findItemsState,
      dispatch: findItemsDispatch,
      onComplete: onStepComplete,
      onCancel: onStepCancel,
      workflow: workflow,
      workflowQuickFilterData: workflowQuickFilterData
    });
  }

  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_woocommerce_components__WEBPACK_IMPORTED_MODULE_3__["Card"], {
    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["sprintf"])( // translators: %s: type of item e.g. 'orders'
    Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('2. Find matching %s', 'automatewoo'), primaryDataTypePluralName)
  }, cardBody);
};

FindItemsStep.propTypes = {
  workflow: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.shape({
    id: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.number.isRequired,
    title: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.string.isRequired
  }).isRequired,
  workflowQuickFilterData: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.shape({
    possibleResultCounts: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.array.isRequired,
    primaryDataTypePluralName: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.string.isRequired
  }).isRequired,
  onStepComplete: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.func.isRequired,
  onStepCancel: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.func.isRequired,
  possibleResultsCount: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.number.isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (FindItemsStep);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/find-items-step/item-finder.js":
/*!********************************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/find-items-step/item-finder.js ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _base_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../base/components */ "./admin/assets/src/base/components/index.js");
/* harmony import */ var _api_utils__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../api-utils */ "./admin/assets/src/manual-workflow-runner/api-utils.js");
/* harmony import */ var _data__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./data */ "./admin/assets/src/manual-workflow-runner/find-items-step/data.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../utils */ "./admin/assets/src/manual-workflow-runner/utils.js");
/* harmony import */ var _items_table__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./items-table */ "./admin/assets/src/manual-workflow-runner/find-items-step/items-table.js");
/* harmony import */ var _next_button__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../next-button */ "./admin/assets/src/manual-workflow-runner/next-button.js");
/* harmony import */ var _no_results__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./no-results */ "./admin/assets/src/manual-workflow-runner/find-items-step/no-results.js");
/* harmony import */ var _base_utils__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../../base/utils */ "./admin/assets/src/base/utils.js");




/**
 * External dependencies
 */



/**
 * Internal dependencies
 */










var ItemFinder = function ItemFinder(_ref) {
  var onComplete = _ref.onComplete,
      onCancel = _ref.onCancel,
      workflowQuickFilterData = _ref.workflowQuickFilterData,
      workflow = _ref.workflow,
      state = _ref.state,
      dispatch = _ref.dispatch;
  var primaryDataTypePluralName = workflowQuickFilterData.primaryDataTypePluralName;
  var itemCount = Object.keys(state.items).length;
  Object(_utils__WEBPACK_IMPORTED_MODULE_8__["useWarnBeforeUnloadWhileRequesting"])(state.status);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["useEffect"])(function () {
    // Only do another request when status is pending
    if (state.status !== _utils__WEBPACK_IMPORTED_MODULE_8__["STEP_STATUSES"].PENDING) {
      return;
    }

    var fetchItemsBatch = /*#__PURE__*/function () {
      var _ref2 = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
        var groupNumber, progressGroup, itemsArray, items;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                groupNumber = Object(_data__WEBPACK_IMPORTED_MODULE_7__["getCurrentProgressGroupNumber"])(state.progress);

                if (!(null === groupNumber)) {
                  _context.next = 3;
                  break;
                }

                return _context.abrupt("return");

              case 3:
                progressGroup = state.progress[groupNumber];
                dispatch({
                  type: 'FIND_ITEMS_REQUEST'
                });
                _context.prev = 5;
                _context.next = 8;
                return Object(_api_utils__WEBPACK_IMPORTED_MODULE_6__["getWorkflowMatchingItems"])(workflow.id, groupNumber, progressGroup.offset);

              case 8:
                itemsArray = _context.sent;
                // Convert items array to object with id as key
                items = {};
                itemsArray.forEach(function (item) {
                  items[item.id] = item;
                });
                dispatch({
                  type: 'FIND_ITEMS_SUCCESS',
                  items: items
                });
                _context.next = 18;
                break;

              case 14:
                _context.prev = 14;
                _context.t0 = _context["catch"](5);
                dispatch({
                  type: 'FIND_ITEMS_ERROR'
                });
                Object(_base_utils__WEBPACK_IMPORTED_MODULE_12__["handleFetchError"])('Error finding items.', _context.t0);

              case 18:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, null, [[5, 14]]);
      }));

      return function fetchItemsBatch() {
        return _ref2.apply(this, arguments);
      };
    }();

    fetchItemsBatch();
  }, [dispatch, workflow.id, state.status, state.progress]);
  var nextButtonText = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["sprintf"])( // translators: %(itemCount)d: number of items, %(dataType)s: type of item e.g. 'orders'
  Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Run workflow for %(itemCount)d %(dataType)s', 'automatewoo'), {
    dataType: primaryDataTypePluralName,
    itemCount: itemCount
  }); // If no results after completion display message.

  if (state.status === 'COMPLETE' && itemCount === 0) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_no_results__WEBPACK_IMPORTED_MODULE_11__["default"], {
      dataType: primaryDataTypePluralName,
      workflowId: workflow.id
    });
  }

  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("p", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["sprintf"])( // translators: %1$s: type of item e.g. 'orders', %2$s: the workflow title
  Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Searching for %1$s that match the rules used in the "%2$s" workflow. ' + 'If you leave this page the process will stop.', 'automatewoo'), primaryDataTypePluralName, workflow.title)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_base_components__WEBPACK_IMPORTED_MODULE_5__["ProgressBar"], {
    progress: state.progressPercent
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_items_table__WEBPACK_IMPORTED_MODULE_9__["default"], {
    items: state.items
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("div", {
    className: "automatewoo-workflow-runner-buttons"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_base_components__WEBPACK_IMPORTED_MODULE_5__["Button"], {
    isLarge: true,
    isDefault: true,
    onClick: onCancel
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Cancel', 'automatewoo')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_next_button__WEBPACK_IMPORTED_MODULE_10__["default"], {
    disabled: state.status !== _utils__WEBPACK_IMPORTED_MODULE_8__["STEP_STATUSES"].COMPLETE,
    onClick: function onClick() {
      return onComplete(state.items);
    }
  }, nextButtonText)));
};

ItemFinder.propTypes = {
  state: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.object.isRequired,
  dispatch: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.func.isRequired,
  onComplete: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.func.isRequired,
  onCancel: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.func.isRequired,
  workflow: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.shape({
    id: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.number.isRequired,
    title: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.string.isRequired
  }).isRequired,
  workflowQuickFilterData: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.shape({
    possibleResultCounts: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.array.isRequired,
    primaryDataTypePluralName: prop_types__WEBPACK_IMPORTED_MODULE_4___default.a.string.isRequired
  }).isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (ItemFinder);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/find-items-step/items-table.js":
/*!********************************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/find-items-step/items-table.js ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_2__);


/**
 * External dependencies
 */



var ItemsTable = function ItemsTable(_ref) {
  var items = _ref.items;
  var itemKeys = Object.keys(items);
  var summary = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["sprintf"])( // translators: %d: the number of items
  Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Total: %d', 'automatewoo'), itemKeys.length);
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-manual-runner-found-items"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-manual-runner-found-items__results"
  }, itemKeys.map(function (itemId) {
    var _items$itemId = items[itemId],
        id = _items$itemId.id,
        url = _items$itemId.url,
        singularName = _items$itemId.singularName;
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
      key: id,
      className: "automatewoo-manual-runner-found-items__result"
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("a", {
      href: url
    }, "".concat(singularName, " #").concat(id)));
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-manual-runner-found-items__summary"
  }, summary));
};

ItemsTable.propTypes = {
  items: prop_types__WEBPACK_IMPORTED_MODULE_2___default.a.objectOf(prop_types__WEBPACK_IMPORTED_MODULE_2___default.a.shape({
    id: prop_types__WEBPACK_IMPORTED_MODULE_2___default.a.number.isRequired,
    singularName: prop_types__WEBPACK_IMPORTED_MODULE_2___default.a.string.isRequired,
    url: prop_types__WEBPACK_IMPORTED_MODULE_2___default.a.string.isRequired
  })).isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (ItemsTable);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/find-items-step/no-results.js":
/*!*******************************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/find-items-step/no-results.js ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _base_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../base/components */ "./admin/assets/src/base/components/index.js");
/* harmony import */ var _base_utils__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../base/utils */ "./admin/assets/src/base/utils.js");


/**
 * External dependencies
 */


/**
 * Internal dependencies
 */




var NoResults = function NoResults(_ref) {
  var dataType = _ref.dataType,
      workflowId = _ref.workflowId;
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-workflow-runner-no-results"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["sprintf"])( // translators: %s: The type of data e.g. 'orders'
  Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('There are no matching %s for the selected manual workflow.', 'automatewoo'), dataType), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-workflow-runner-buttons"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_base_components__WEBPACK_IMPORTED_MODULE_3__["Button"], {
    isDefault: true,
    isLarge: true,
    href: Object(_base_utils__WEBPACK_IMPORTED_MODULE_4__["getWorkflowEditUrl"])(workflowId)
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Edit workflow', 'automatewoo'))));
};

NoResults.propTypes = {
  dataType: prop_types__WEBPACK_IMPORTED_MODULE_2___default.a.string.isRequired,
  workflowId: prop_types__WEBPACK_IMPORTED_MODULE_2___default.a.number.isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (NoResults);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/index.js":
/*!**********************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/index.js ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @woocommerce/components */ "@woocommerce/components");
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_components__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _select_workflow_step__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./select-workflow-step */ "./admin/assets/src/manual-workflow-runner/select-workflow-step.js");
/* harmony import */ var _find_items_step__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./find-items-step */ "./admin/assets/src/manual-workflow-runner/find-items-step/index.js");
/* harmony import */ var _queue_step__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./queue-step */ "./admin/assets/src/manual-workflow-runner/queue-step/index.js");
/* harmony import */ var _api_utils__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./api-utils */ "./admin/assets/src/manual-workflow-runner/api-utils.js");
/* harmony import */ var _base_utils__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../base/utils */ "./admin/assets/src/base/utils.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./utils */ "./admin/assets/src/manual-workflow-runner/utils.js");
/* harmony import */ var _base_usage_tracking__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../base/usage-tracking */ "./admin/assets/src/base/usage-tracking/index.js");





/**
 * External dependencies
 */



/**
 * Internal dependencies
 */









var ManualWorkflowRunner = function ManualWorkflowRunner(_ref) {
  var query = _ref.query;

  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useState"])({}),
      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState, 2),
      workflow = _useState2[0],
      setWorkflow = _useState2[1];

  var _useState3 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useState"])(false),
      _useState4 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState3, 2),
      quickFilterData = _useState4[0],
      setQuickFilterData = _useState4[1];

  var _useState5 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useState"])({}),
      _useState6 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState5, 2),
      foundItems = _useState6[0],
      setFoundItems = _useState6[1];

  var _useState7 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useState"])('select'),
      _useState8 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState7, 2),
      currentStep = _useState8[0],
      setCurrentStep = _useState8[1];

  var _useState9 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useState"])(false),
      _useState10 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState9, 2),
      stepperIsPending = _useState10[0],
      setStepperIsPending = _useState10[1];

  var _useState11 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useState"])(true),
      _useState12 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState11, 2),
      isPreFillingWorkflow = _useState12[0],
      setIsPreFillingWorkflow = _useState12[1];
  /**
   * Handle select workflow step completion.
   *
   * Load extra data about the selected workflow.
   */


  var onSelectStepComplete = /*#__PURE__*/function () {
    var _ref2 = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
      var _yield$getWorkflowQui, possibleResultCounts, primaryDataType;

      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              setStepperIsPending(true);
              Object(_base_usage_tracking__WEBPACK_IMPORTED_MODULE_12__["default"])('manual_workflow_runner_select_workflow', {
                conversion_tracking_enabled: workflow.is_conversion_tracking_enabled,
                tracking_enabled: workflow.is_tracking_enabled,
                title: workflow.title,
                type: workflow.type,
                trigger_name: workflow.trigger.name
              });
              _context.prev = 2;
              _context.next = 5;
              return Object(_api_utils__WEBPACK_IMPORTED_MODULE_9__["getWorkflowQuickFilterData"])(workflow.id);

            case 5:
              _yield$getWorkflowQui = _context.sent;
              possibleResultCounts = _yield$getWorkflowQui.possibleResultCounts;
              primaryDataType = _yield$getWorkflowQui.primaryDataType;
              setQuickFilterData({
                possibleResultCounts: possibleResultCounts,
                primaryDataType: primaryDataType,
                primaryDataTypePluralName: getDataTypePluralName(primaryDataType)
              });
              setCurrentStep('find');
              setStepperIsPending(false);
              _context.next = 16;
              break;

            case 13:
              _context.prev = 13;
              _context.t0 = _context["catch"](2);
              Object(_base_utils__WEBPACK_IMPORTED_MODULE_10__["handleFetchError"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('Error loading the workflow data.', 'automatewoo'), _context.t0);

            case 16:
            case "end":
              return _context.stop();
          }
        }
      }, _callee, null, [[2, 13]]);
    }));

    return function onSelectStepComplete() {
      return _ref2.apply(this, arguments);
    };
  }();
  /**
   * Pre-fill the selected workflow based on the query.
   */


  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useEffect"])(function () {
    var maybePreFillWorkflow = /*#__PURE__*/function () {
      var _ref3 = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2() {
        var workflowId, newWorkflow;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                setIsPreFillingWorkflow(true);
                workflowId = typeof query.workflowId !== 'undefined' ? parseInt(query.workflowId) : 0;

                if (!(0 !== workflowId)) {
                  _context2.next = 13;
                  break;
                }

                _context2.prev = 3;
                _context2.next = 6;
                return Object(_base_utils__WEBPACK_IMPORTED_MODULE_10__["getWorkflow"])(workflowId);

              case 6:
                newWorkflow = _context2.sent;
                setWorkflow(newWorkflow);
                _context2.next = 13;
                break;

              case 10:
                _context2.prev = 10;
                _context2.t0 = _context2["catch"](3);
                Object(_base_utils__WEBPACK_IMPORTED_MODULE_10__["handleFetchError"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])("The workflow couldn't be loaded from the URL.", 'automatewoo'), _context2.t0);

              case 13:
                setIsPreFillingWorkflow(false);

              case 14:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2, null, [[3, 10]]);
      }));

      return function maybePreFillWorkflow() {
        return _ref3.apply(this, arguments);
      };
    }();

    maybePreFillWorkflow();
  }, [query.workflowId]);

  var onFindStepComplete = function onFindStepComplete(newFoundItems) {
    Object(_base_usage_tracking__WEBPACK_IMPORTED_MODULE_12__["default"])('manual_run_workflow_button_clicked', {
      items_count: Object.keys(newFoundItems).length
    });
    setCurrentStep('queue');
    setFoundItems(newFoundItems);
  };

  var onFindStepCancel = function onFindStepCancel() {
    Object(_base_usage_tracking__WEBPACK_IMPORTED_MODULE_12__["default"])('manual_find_matching_cancel_button_clicked', {});
    setCurrentStep('select');
  };

  var onQueueStepCancel = function onQueueStepCancel() {
    Object(_base_usage_tracking__WEBPACK_IMPORTED_MODULE_12__["default"])('manual_queue_items_cancel_button_clicked', {});
    setCurrentStep('select');
    setFoundItems({});
  };
  /**
   * @param {string} primaryDataType
   * @return {string} The plural name of the data type.
   */


  var getDataTypePluralName = function getDataTypePluralName(primaryDataType) {
    var names = {
      order: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('orders', 'automatewoo'),
      subscription: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('subscriptions', 'automatewoo')
    };
    return names.hasOwnProperty(primaryDataType) ? names[primaryDataType] : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('items', 'automatewoo');
  };

  var findItemsStepContent = function findItemsStepContent() {
    if (currentStep === 'find' && workflow && quickFilterData) {
      var possibleResults = Object(_utils__WEBPACK_IMPORTED_MODULE_11__["getTotalPossibleResults"])(quickFilterData.possibleResultCounts);
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_find_items_step__WEBPACK_IMPORTED_MODULE_7__["default"], {
        workflow: workflow,
        workflowQuickFilterData: quickFilterData,
        possibleResultsCount: possibleResults,
        onStepComplete: onFindStepComplete,
        onStepCancel: onFindStepCancel
      });
    }

    return '';
  };

  var steps = [{
    key: 'select',
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('Select', 'automatewoo'),
    content: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_select_workflow_step__WEBPACK_IMPORTED_MODULE_6__["default"], {
      onStepComplete: onSelectStepComplete,
      isPreFillingWorkflow: isPreFillingWorkflow,
      workflow: workflow,
      setWorkflow: setWorkflow
    })
  }, {
    key: 'find',
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('Find', 'automatewoo'),
    content: findItemsStepContent()
  }, {
    key: 'queue',
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('Queue', 'automatewoo'),
    content: workflow && quickFilterData && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_queue_step__WEBPACK_IMPORTED_MODULE_8__["default"], {
      workflow: workflow,
      workflowQuickFilterData: quickFilterData,
      items: foundItems,
      onStepCancel: onQueueStepCancel
    })
  }];
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_woocommerce_components__WEBPACK_IMPORTED_MODULE_5__["Stepper"], {
    steps: steps,
    currentStep: currentStep,
    className: "automatewoo-manual-workflow-runner-stepper",
    isPending: stepperIsPending
  });
};

/* harmony default export */ __webpack_exports__["default"] = (ManualWorkflowRunner);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/large-text-and-icon.js":
/*!************************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/large-text-and-icon.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);


/**
 * External dependencies
 */



var LargeTextAndIcon = function LargeTextAndIcon(_ref) {
  var text = _ref.text,
      icon = _ref.icon,
      _ref$className = _ref.className,
      className = _ref$className === void 0 ? '' : _ref$className;
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()('automatewoo-manual-runner-large-text-and-icon', className)
  }, icon, text);
};

LargeTextAndIcon.propTypes = {
  text: prop_types__WEBPACK_IMPORTED_MODULE_1___default.a.element.isRequired,
  icon: prop_types__WEBPACK_IMPORTED_MODULE_1___default.a.element.isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (LargeTextAndIcon);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/next-button.js":
/*!****************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/next-button.js ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return NextButton; });
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ "./node_modules/@babel/runtime/helpers/extends.js");
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _base_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../base/components */ "./admin/assets/src/base/components/index.js");



/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


function NextButton(props) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_base_components__WEBPACK_IMPORTED_MODULE_3__["Button"], _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default()({}, props, {
    isPrimary: true,
    isLarge: true,
    className: "automatewoo-workflow-runner-next-button"
  }), props.children || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Next', 'automatewoo'));
}

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/queue-step/data.js":
/*!********************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/queue-step/data.js ***!
  \********************************************************************/
/*! exports provided: useQueueItemsReducer */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useQueueItemsReducer", function() { return useQueueItemsReducer; });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils */ "./admin/assets/src/manual-workflow-runner/utils.js");


function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

/**
 * External dependencies
 */

/**
 * Internal dependencies
 */



var reducer = function reducer(state, action) {
  switch (action.type) {
    case 'ADD_ITEMS_REQUEST':
      if (state.status === _utils__WEBPACK_IMPORTED_MODULE_2__["STEP_STATUSES"].REQUESTING) {
        return state;
      }

      return _objectSpread(_objectSpread({}, state), {}, {
        status: _utils__WEBPACK_IMPORTED_MODULE_2__["STEP_STATUSES"].REQUESTING
      });

    case 'ADD_ITEMS_ERROR':
      if (state.status === _utils__WEBPACK_IMPORTED_MODULE_2__["STEP_STATUSES"].ERROR) {
        return state;
      }

      return _objectSpread(_objectSpread({}, state), {}, {
        status: _utils__WEBPACK_IMPORTED_MODULE_2__["STEP_STATUSES"].ERROR
      });

    case 'ADD_ITEMS_SUCCESS':
      var itemsRemainingCount = Object.keys(action.itemsRemaining).length;
      return _objectSpread(_objectSpread({}, state), {}, {
        itemsRemaining: action.itemsRemaining,
        status: itemsRemainingCount === 0 ? _utils__WEBPACK_IMPORTED_MODULE_2__["STEP_STATUSES"].COMPLETE : _utils__WEBPACK_IMPORTED_MODULE_2__["STEP_STATUSES"].PENDING,
        progress: calculateProgressPercentage(Object.keys(state.itemsToAdd).length, itemsRemainingCount)
      });

    default:
      return state;
  }
};

var useQueueItemsReducer = function useQueueItemsReducer(itemsToQueue) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useReducer"])(reducer, {
    status: _utils__WEBPACK_IMPORTED_MODULE_2__["STEP_STATUSES"].PENDING,
    progress: 0,
    itemsToAdd: itemsToQueue,
    itemsRemaining: itemsToQueue
  });
};

var calculateProgressPercentage = function calculateProgressPercentage(totalCount, remainingCount) {
  var completedCount = totalCount - remainingCount;

  if (0 === totalCount || 0 === completedCount) {
    return 0;
  }

  var progress = Math.round(completedCount / totalCount * 100);
  return progress > 100 ? 100 : progress;
};

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/queue-step/index.js":
/*!*********************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/queue-step/index.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @woocommerce/components */ "@woocommerce/components");
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_components__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _base_components__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../../base/components */ "./admin/assets/src/base/components/index.js");
/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../settings */ "./admin/assets/src/settings.js");
/* harmony import */ var _api_utils__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../api-utils */ "./admin/assets/src/manual-workflow-runner/api-utils.js");
/* harmony import */ var _next_button__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../next-button */ "./admin/assets/src/manual-workflow-runner/next-button.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../utils */ "./admin/assets/src/manual-workflow-runner/utils.js");
/* harmony import */ var _data__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./data */ "./admin/assets/src/manual-workflow-runner/queue-step/data.js");
/* harmony import */ var _base_utils__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../../base/utils */ "./admin/assets/src/base/utils.js");
/* harmony import */ var _base_usage_tracking__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ../../base/usage-tracking */ "./admin/assets/src/base/usage-tracking/index.js");
/* harmony import */ var _large_text_and_icon__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ../large-text-and-icon */ "./admin/assets/src/manual-workflow-runner/large-text-and-icon.js");





/**
 * External dependencies
 */






/**
 * Internal dependencies
 */











var QueueStep = function QueueStep(_ref) {
  var items = _ref.items,
      workflow = _ref.workflow,
      workflowQuickFilterData = _ref.workflowQuickFilterData,
      onStepCancel = _ref.onStepCancel;

  var _useQueueItemsReducer = Object(_data__WEBPACK_IMPORTED_MODULE_14__["useQueueItemsReducer"])(items),
      _useQueueItemsReducer2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useQueueItemsReducer, 2),
      queueItemsState = _useQueueItemsReducer2[0],
      queueItemsDispatch = _useQueueItemsReducer2[1];

  Object(_utils__WEBPACK_IMPORTED_MODULE_13__["useWarnBeforeUnloadWhileRequesting"])(queueItemsState.status);
  var itemCount = Object.keys(items).length;
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useEffect"])(function () {
    // Only do another request when status is pending
    if (queueItemsState.status !== _utils__WEBPACK_IMPORTED_MODULE_13__["STEP_STATUSES"].PENDING) {
      return;
    }

    var addItemsBatch = /*#__PURE__*/function () {
      var _ref2 = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
        var batch, newItemsRemaining;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                queueItemsDispatch({
                  type: 'ADD_ITEMS_REQUEST'
                }); // Extract a batch of items from the remaining items

                batch = Object.keys(queueItemsState.itemsRemaining).splice(0, _settings__WEBPACK_IMPORTED_MODULE_10__["MANUAL_WORKFLOWS_BATCH_SIZE"]);
                newItemsRemaining = Object(lodash__WEBPACK_IMPORTED_MODULE_8__["omit"])(queueItemsState.itemsRemaining, batch);
                _context.prev = 3;
                _context.next = 6;
                return Object(_api_utils__WEBPACK_IMPORTED_MODULE_11__["addItemBatchToQueue"])(workflow.id, batch);

              case 6:
                queueItemsDispatch({
                  type: 'ADD_ITEMS_SUCCESS',
                  itemsRemaining: newItemsRemaining
                });
                _context.next = 13;
                break;

              case 9:
                _context.prev = 9;
                _context.t0 = _context["catch"](3);
                queueItemsDispatch({
                  type: 'ADD_ITEMS_ERROR'
                });
                Object(_base_utils__WEBPACK_IMPORTED_MODULE_15__["handleFetchError"])('Error adding items to queue.', _context.t0);

              case 13:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, null, [[3, 9]]);
      }));

      return function addItemsBatch() {
        return _ref2.apply(this, arguments);
      };
    }();

    addItemsBatch();
  }, [queueItemsState.itemsRemaining, queueItemsState.status, workflow.id, queueItemsDispatch]);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useEffect"])(function () {
    // Only record tracks event when complete.
    if (queueItemsState.status !== _utils__WEBPACK_IMPORTED_MODULE_13__["STEP_STATUSES"].COMPLETE) {
      return;
    }

    Object(_base_usage_tracking__WEBPACK_IMPORTED_MODULE_16__["default"])('manual_run_workflow_complete', {
      items_count: itemCount,
      conversion_tracking_enabled: workflow.is_conversion_tracking_enabled,
      tracking_enabled: workflow.is_tracking_enabled,
      title: workflow.title,
      type: workflow.type,
      trigger_name: workflow.trigger.name
    });
  }, [queueItemsState.status, itemCount]);

  var getCardBody = function getCardBody() {
    if (queueItemsState.status === _utils__WEBPACK_IMPORTED_MODULE_13__["STEP_STATUSES"].COMPLETE) {
      var successText = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["sprintf"])( // translators: %(itemCount)d: number of matching items, %(dataType)s: type of item
      Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('Woo! %(itemCount)d %(dataType)s were successfully added to the queue.', 'automatewoo'), {
        dataType: workflowQuickFilterData.primaryDataTypePluralName,
        itemCount: itemCount
      });
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_large_text_and_icon__WEBPACK_IMPORTED_MODULE_17__["default"], {
        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__["Dashicon"], {
          icon: "yes-alt",
          size: "60"
        }),
        text: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])("p", null, successText)
      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])("div", {
        className: "automatewoo-workflow-runner-buttons"
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_next_button__WEBPACK_IMPORTED_MODULE_12__["default"], {
        isPrimary: true,
        isLarge: true,
        href: "".concat(_settings__WEBPACK_IMPORTED_MODULE_10__["ADMIN_URL"], "admin.php?page=automatewoo-queue&_workflow=").concat(workflow.id)
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('View in queue', 'automatewoo'))));
    }

    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])("p", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["sprintf"])( // translators: %(itemCount)d: number of matching items, %(dataType)s: type of item, %(workflow)s: workflow title
    Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('Adding %(itemCount)d matching %(dataType)s to the queue for the "%(workflow)s" workflow. ' + 'If you leave this page the process will stop.', 'automatewoo'), {
      workflow: workflow.title,
      dataType: workflowQuickFilterData.primaryDataTypePluralName,
      itemCount: itemCount
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_base_components__WEBPACK_IMPORTED_MODULE_9__["ProgressBar"], {
      progress: queueItemsState.progress
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])("div", {
      className: "automatewoo-workflow-runner-buttons"
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_base_components__WEBPACK_IMPORTED_MODULE_9__["Button"], {
      isLarge: true,
      isDefault: true,
      onClick: onStepCancel
    }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('Cancel', 'automatewoo'))));
  };

  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["createElement"])(_woocommerce_components__WEBPACK_IMPORTED_MODULE_5__["Card"], {
    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('3. Add to workflow queue', 'automatewoo')
  }, getCardBody());
};

QueueStep.propTypes = {
  workflow: prop_types__WEBPACK_IMPORTED_MODULE_7__["PropTypes"].shape({
    id: prop_types__WEBPACK_IMPORTED_MODULE_7__["PropTypes"].number.isRequired,
    title: prop_types__WEBPACK_IMPORTED_MODULE_7__["PropTypes"].string.isRequired
  }).isRequired,
  workflowQuickFilterData: prop_types__WEBPACK_IMPORTED_MODULE_7__["PropTypes"].shape({
    primaryDataTypePluralName: prop_types__WEBPACK_IMPORTED_MODULE_7__["PropTypes"].string.isRequired
  }).isRequired,
  items: prop_types__WEBPACK_IMPORTED_MODULE_7__["PropTypes"].object.isRequired,
  onStepCancel: prop_types__WEBPACK_IMPORTED_MODULE_7__["PropTypes"].func.isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (QueueStep);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/select-workflow-step.js":
/*!*************************************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/select-workflow-step.js ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @woocommerce/components */ "@woocommerce/components");
/* harmony import */ var _woocommerce_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _base_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../base/components */ "./admin/assets/src/base/components/index.js");
/* harmony import */ var _next_button__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./next-button */ "./admin/assets/src/manual-workflow-runner/next-button.js");


/**
 * External dependencies
 */




/**
 * Internal dependencies
 */




var SelectWorkflowStep = function SelectWorkflowStep(_ref) {
  var onStepComplete = _ref.onStepComplete,
      isPreFillingWorkflow = _ref.isPreFillingWorkflow,
      workflow = _ref.workflow,
      setWorkflow = _ref.setWorkflow;
  var selected = [];

  if (!Object(lodash__WEBPACK_IMPORTED_MODULE_4__["isEmpty"])(workflow)) {
    selected.push({
      key: workflow.id.toString(),
      label: workflow.title,
      value: workflow
    });
  }
  /**
   * Completes this step and sends the selected workflow up.
   */


  var goToNextStep = function goToNextStep() {
    if (workflow.length === 0) {
      return;
    }

    onStepComplete();
  };

  var content = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-workflow-runner-select-workflow-step"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_base_components__WEBPACK_IMPORTED_MODULE_5__["WorkflowSearch"], {
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Workflow', 'automatewoo'),
    placeholder: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Search by workflow title…', 'automatewoo'),
    selected: selected,
    onChange: function onChange(newSelected) {
      setWorkflow(newSelected.length ? newSelected[0].value : {});
    },
    requestParams: {
      type: 'manual'
    }
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-workflow-runner-buttons"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_next_button__WEBPACK_IMPORTED_MODULE_6__["default"], {
    onClick: goToNextStep,
    disabled: selected.length === 0
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Find matching items', 'automatewoo'))));
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_woocommerce_components__WEBPACK_IMPORTED_MODULE_3__["Card"], {
    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('1. Select a manual workflow', 'automatewoo')
  }, isPreFillingWorkflow ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "automatewoo-manual-workflow-runner-spinner-container"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_woocommerce_components__WEBPACK_IMPORTED_MODULE_3__["Spinner"], null)) : content);
};

SelectWorkflowStep.propTypes = {
  onStepComplete: prop_types__WEBPACK_IMPORTED_MODULE_2__["PropTypes"].func.isRequired,
  isPreFillingWorkflow: prop_types__WEBPACK_IMPORTED_MODULE_2__["PropTypes"].bool.isRequired,
  workflow: prop_types__WEBPACK_IMPORTED_MODULE_2__["PropTypes"].object.isRequired,
  setWorkflow: prop_types__WEBPACK_IMPORTED_MODULE_2__["PropTypes"].func.isRequired
};
/* harmony default export */ __webpack_exports__["default"] = (SelectWorkflowStep);

/***/ }),

/***/ "./admin/assets/src/manual-workflow-runner/utils.js":
/*!**********************************************************!*\
  !*** ./admin/assets/src/manual-workflow-runner/utils.js ***!
  \**********************************************************/
/*! exports provided: STEP_STATUSES, useWarnBeforeUnloadWhileRequesting, getTotalPossibleResults */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "STEP_STATUSES", function() { return STEP_STATUSES; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useWarnBeforeUnloadWhileRequesting", function() { return useWarnBeforeUnloadWhileRequesting; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getTotalPossibleResults", function() { return getTotalPossibleResults; });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _base_hooks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../base/hooks */ "./admin/assets/src/base/hooks/index.js");
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


var STEP_STATUSES = {
  COMPLETE: 'COMPLETE',
  ERROR: 'ERROR',
  PENDING: 'PENDING',
  REQUESTING: 'REQUESTING'
};
var useWarnBeforeUnloadWhileRequesting = function useWarnBeforeUnloadWhileRequesting(status) {
  var message = '';

  if (![STEP_STATUSES.COMPLETE, STEP_STATUSES.ERROR].includes(status)) {
    message = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('If you leave this page the process will stop.', 'automatewoo');
  }

  Object(_base_hooks__WEBPACK_IMPORTED_MODULE_1__["useBeforeUnload"])(message);
};
var getTotalPossibleResults = function getTotalPossibleResults(possibleResultCountData) {
  var count = 0;
  possibleResultCountData.forEach(function (group) {
    count += group.count;
  });
  return count;
};

/***/ }),

/***/ "./admin/assets/src/settings.js":
/*!**************************************!*\
  !*** ./admin/assets/src/settings.js ***!
  \**************************************/
/*! exports provided: MANUAL_WORKFLOWS_BATCH_SIZE, MANUAL_WORKFLOWS_HIGH_VOLUME_THRESHOLD, ADMIN_URL */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "MANUAL_WORKFLOWS_BATCH_SIZE", function() { return MANUAL_WORKFLOWS_BATCH_SIZE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "MANUAL_WORKFLOWS_HIGH_VOLUME_THRESHOLD", function() { return MANUAL_WORKFLOWS_HIGH_VOLUME_THRESHOLD; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ADMIN_URL", function() { return ADMIN_URL; });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);

var getWcSetting = wc.wcSettings.getSetting;
var automatewoo = getWcSetting('automatewoo', {});

var getSetting = function getSetting(property) {
  var fallback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var settingsObject = arguments.length > 2 ? arguments[2] : undefined;

  if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(settingsObject) === 'object' && settingsObject.hasOwnProperty(property)) {
    return settingsObject[property];
  }

  return fallback;
};

var MANUAL_WORKFLOWS_BATCH_SIZE = getSetting('batchSize', 10, automatewoo.manualRunner);
var MANUAL_WORKFLOWS_HIGH_VOLUME_THRESHOLD = getSetting('highVolumeThreshold', 500, automatewoo.manualRunner);
var ADMIN_URL = getWcSetting('adminUrl', '');

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

module.exports = _arrayLikeToArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithHoles.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

module.exports = _arrayWithHoles;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return arrayLikeToArray(arr);
}

module.exports = _arrayWithoutHoles;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/asyncToGenerator.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
  try {
    var info = gen[key](arg);
    var value = info.value;
  } catch (error) {
    reject(error);
    return;
  }

  if (info.done) {
    resolve(value);
  } else {
    Promise.resolve(value).then(_next, _throw);
  }
}

function _asyncToGenerator(fn) {
  return function () {
    var self = this,
        args = arguments;
    return new Promise(function (resolve, reject) {
      var gen = fn.apply(self, args);

      function _next(value) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
      }

      function _throw(err) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
      }

      _next(undefined);
    });
  };
}

module.exports = _asyncToGenerator;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

module.exports = _defineProperty;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/extends.js":
/*!********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/extends.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _extends() {
  module.exports = _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}

module.exports = _extends;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter);
}

module.exports = _iterableToArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArrayLimit(arr, i) {
  if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return;
  var _arr = [];
  var _n = true;
  var _d = false;
  var _e = undefined;

  try {
    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);

      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }

  return _arr;
}

module.exports = _iterableToArrayLimit;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableRest.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableRest.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

module.exports = _nonIterableRest;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

module.exports = _nonIterableSpread;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/slicedToArray.js":
/*!**************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/slicedToArray.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithHoles = __webpack_require__(/*! ./arrayWithHoles */ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js");

var iterableToArrayLimit = __webpack_require__(/*! ./iterableToArrayLimit */ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js");

var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");

var nonIterableRest = __webpack_require__(/*! ./nonIterableRest */ "./node_modules/@babel/runtime/helpers/nonIterableRest.js");

function _slicedToArray(arr, i) {
  return arrayWithHoles(arr) || iterableToArrayLimit(arr, i) || unsupportedIterableToArray(arr, i) || nonIterableRest();
}

module.exports = _slicedToArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles */ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js");

var iterableToArray = __webpack_require__(/*! ./iterableToArray */ "./node_modules/@babel/runtime/helpers/iterableToArray.js");

var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");

var nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread */ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js");

function _toConsumableArray(arr) {
  return arrayWithoutHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableSpread();
}

module.exports = _toConsumableArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) {
  "@babel/helpers - typeof";

  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    module.exports = _typeof = function _typeof(obj) {
      return typeof obj;
    };
  } else {
    module.exports = _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };
  }

  return _typeof(obj);
}

module.exports = _typeof;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);
}

module.exports = _unsupportedIterableToArray;

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg) && arg.length) {
				var inner = classNames.apply(null, arg);
				if (inner) {
					classes.push(inner);
				}
			} else if (argType === 'object') {
				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "./node_modules/object-assign/index.js":
/*!*********************************************!*\
  !*** ./node_modules/object-assign/index.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/*
object-assign
(c) Sindre Sorhus
@license MIT
*/


/* eslint-disable no-unused-vars */
var getOwnPropertySymbols = Object.getOwnPropertySymbols;
var hasOwnProperty = Object.prototype.hasOwnProperty;
var propIsEnumerable = Object.prototype.propertyIsEnumerable;

function toObject(val) {
	if (val === null || val === undefined) {
		throw new TypeError('Object.assign cannot be called with null or undefined');
	}

	return Object(val);
}

function shouldUseNative() {
	try {
		if (!Object.assign) {
			return false;
		}

		// Detect buggy property enumeration order in older V8 versions.

		// https://bugs.chromium.org/p/v8/issues/detail?id=4118
		var test1 = new String('abc');  // eslint-disable-line no-new-wrappers
		test1[5] = 'de';
		if (Object.getOwnPropertyNames(test1)[0] === '5') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test2 = {};
		for (var i = 0; i < 10; i++) {
			test2['_' + String.fromCharCode(i)] = i;
		}
		var order2 = Object.getOwnPropertyNames(test2).map(function (n) {
			return test2[n];
		});
		if (order2.join('') !== '0123456789') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test3 = {};
		'abcdefghijklmnopqrst'.split('').forEach(function (letter) {
			test3[letter] = letter;
		});
		if (Object.keys(Object.assign({}, test3)).join('') !==
				'abcdefghijklmnopqrst') {
			return false;
		}

		return true;
	} catch (err) {
		// We don't expect any of the above to throw, but better to be safe.
		return false;
	}
}

module.exports = shouldUseNative() ? Object.assign : function (target, source) {
	var from;
	var to = toObject(target);
	var symbols;

	for (var s = 1; s < arguments.length; s++) {
		from = Object(arguments[s]);

		for (var key in from) {
			if (hasOwnProperty.call(from, key)) {
				to[key] = from[key];
			}
		}

		if (getOwnPropertySymbols) {
			symbols = getOwnPropertySymbols(from);
			for (var i = 0; i < symbols.length; i++) {
				if (propIsEnumerable.call(from, symbols[i])) {
					to[symbols[i]] = from[symbols[i]];
				}
			}
		}
	}

	return to;
};


/***/ }),

/***/ "./node_modules/prop-types/checkPropTypes.js":
/*!***************************************************!*\
  !*** ./node_modules/prop-types/checkPropTypes.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var printWarning = function() {};

if (true) {
  var ReactPropTypesSecret = __webpack_require__(/*! ./lib/ReactPropTypesSecret */ "./node_modules/prop-types/lib/ReactPropTypesSecret.js");
  var loggedTypeFailures = {};
  var has = Function.call.bind(Object.prototype.hasOwnProperty);

  printWarning = function(text) {
    var message = 'Warning: ' + text;
    if (typeof console !== 'undefined') {
      console.error(message);
    }
    try {
      // --- Welcome to debugging React ---
      // This error was thrown as a convenience so that you can use this stack
      // to find the callsite that caused this warning to fire.
      throw new Error(message);
    } catch (x) {}
  };
}

/**
 * Assert that the values match with the type specs.
 * Error messages are memorized and will only be shown once.
 *
 * @param {object} typeSpecs Map of name to a ReactPropType
 * @param {object} values Runtime values that need to be type-checked
 * @param {string} location e.g. "prop", "context", "child context"
 * @param {string} componentName Name of the component for error messages.
 * @param {?Function} getStack Returns the component stack.
 * @private
 */
function checkPropTypes(typeSpecs, values, location, componentName, getStack) {
  if (true) {
    for (var typeSpecName in typeSpecs) {
      if (has(typeSpecs, typeSpecName)) {
        var error;
        // Prop type validation may throw. In case they do, we don't want to
        // fail the render phase where it didn't fail before. So we log it.
        // After these have been cleaned up, we'll let them throw.
        try {
          // This is intentionally an invariant that gets caught. It's the same
          // behavior as without this statement except with a better message.
          if (typeof typeSpecs[typeSpecName] !== 'function') {
            var err = Error(
              (componentName || 'React class') + ': ' + location + ' type `' + typeSpecName + '` is invalid; ' +
              'it must be a function, usually from the `prop-types` package, but received `' + typeof typeSpecs[typeSpecName] + '`.'
            );
            err.name = 'Invariant Violation';
            throw err;
          }
          error = typeSpecs[typeSpecName](values, typeSpecName, componentName, location, null, ReactPropTypesSecret);
        } catch (ex) {
          error = ex;
        }
        if (error && !(error instanceof Error)) {
          printWarning(
            (componentName || 'React class') + ': type specification of ' +
            location + ' `' + typeSpecName + '` is invalid; the type checker ' +
            'function must return `null` or an `Error` but returned a ' + typeof error + '. ' +
            'You may have forgotten to pass an argument to the type checker ' +
            'creator (arrayOf, instanceOf, objectOf, oneOf, oneOfType, and ' +
            'shape all require an argument).'
          );
        }
        if (error instanceof Error && !(error.message in loggedTypeFailures)) {
          // Only monitor this failure once because there tends to be a lot of the
          // same error.
          loggedTypeFailures[error.message] = true;

          var stack = getStack ? getStack() : '';

          printWarning(
            'Failed ' + location + ' type: ' + error.message + (stack != null ? stack : '')
          );
        }
      }
    }
  }
}

/**
 * Resets warning cache when testing.
 *
 * @private
 */
checkPropTypes.resetWarningCache = function() {
  if (true) {
    loggedTypeFailures = {};
  }
}

module.exports = checkPropTypes;


/***/ }),

/***/ "./node_modules/prop-types/factoryWithTypeCheckers.js":
/*!************************************************************!*\
  !*** ./node_modules/prop-types/factoryWithTypeCheckers.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var ReactIs = __webpack_require__(/*! react-is */ "./node_modules/react-is/index.js");
var assign = __webpack_require__(/*! object-assign */ "./node_modules/object-assign/index.js");

var ReactPropTypesSecret = __webpack_require__(/*! ./lib/ReactPropTypesSecret */ "./node_modules/prop-types/lib/ReactPropTypesSecret.js");
var checkPropTypes = __webpack_require__(/*! ./checkPropTypes */ "./node_modules/prop-types/checkPropTypes.js");

var has = Function.call.bind(Object.prototype.hasOwnProperty);
var printWarning = function() {};

if (true) {
  printWarning = function(text) {
    var message = 'Warning: ' + text;
    if (typeof console !== 'undefined') {
      console.error(message);
    }
    try {
      // --- Welcome to debugging React ---
      // This error was thrown as a convenience so that you can use this stack
      // to find the callsite that caused this warning to fire.
      throw new Error(message);
    } catch (x) {}
  };
}

function emptyFunctionThatReturnsNull() {
  return null;
}

module.exports = function(isValidElement, throwOnDirectAccess) {
  /* global Symbol */
  var ITERATOR_SYMBOL = typeof Symbol === 'function' && Symbol.iterator;
  var FAUX_ITERATOR_SYMBOL = '@@iterator'; // Before Symbol spec.

  /**
   * Returns the iterator method function contained on the iterable object.
   *
   * Be sure to invoke the function with the iterable as context:
   *
   *     var iteratorFn = getIteratorFn(myIterable);
   *     if (iteratorFn) {
   *       var iterator = iteratorFn.call(myIterable);
   *       ...
   *     }
   *
   * @param {?object} maybeIterable
   * @return {?function}
   */
  function getIteratorFn(maybeIterable) {
    var iteratorFn = maybeIterable && (ITERATOR_SYMBOL && maybeIterable[ITERATOR_SYMBOL] || maybeIterable[FAUX_ITERATOR_SYMBOL]);
    if (typeof iteratorFn === 'function') {
      return iteratorFn;
    }
  }

  /**
   * Collection of methods that allow declaration and validation of props that are
   * supplied to React components. Example usage:
   *
   *   var Props = require('ReactPropTypes');
   *   var MyArticle = React.createClass({
   *     propTypes: {
   *       // An optional string prop named "description".
   *       description: Props.string,
   *
   *       // A required enum prop named "category".
   *       category: Props.oneOf(['News','Photos']).isRequired,
   *
   *       // A prop named "dialog" that requires an instance of Dialog.
   *       dialog: Props.instanceOf(Dialog).isRequired
   *     },
   *     render: function() { ... }
   *   });
   *
   * A more formal specification of how these methods are used:
   *
   *   type := array|bool|func|object|number|string|oneOf([...])|instanceOf(...)
   *   decl := ReactPropTypes.{type}(.isRequired)?
   *
   * Each and every declaration produces a function with the same signature. This
   * allows the creation of custom validation functions. For example:
   *
   *  var MyLink = React.createClass({
   *    propTypes: {
   *      // An optional string or URI prop named "href".
   *      href: function(props, propName, componentName) {
   *        var propValue = props[propName];
   *        if (propValue != null && typeof propValue !== 'string' &&
   *            !(propValue instanceof URI)) {
   *          return new Error(
   *            'Expected a string or an URI for ' + propName + ' in ' +
   *            componentName
   *          );
   *        }
   *      }
   *    },
   *    render: function() {...}
   *  });
   *
   * @internal
   */

  var ANONYMOUS = '<<anonymous>>';

  // Important!
  // Keep this list in sync with production version in `./factoryWithThrowingShims.js`.
  var ReactPropTypes = {
    array: createPrimitiveTypeChecker('array'),
    bool: createPrimitiveTypeChecker('boolean'),
    func: createPrimitiveTypeChecker('function'),
    number: createPrimitiveTypeChecker('number'),
    object: createPrimitiveTypeChecker('object'),
    string: createPrimitiveTypeChecker('string'),
    symbol: createPrimitiveTypeChecker('symbol'),

    any: createAnyTypeChecker(),
    arrayOf: createArrayOfTypeChecker,
    element: createElementTypeChecker(),
    elementType: createElementTypeTypeChecker(),
    instanceOf: createInstanceTypeChecker,
    node: createNodeChecker(),
    objectOf: createObjectOfTypeChecker,
    oneOf: createEnumTypeChecker,
    oneOfType: createUnionTypeChecker,
    shape: createShapeTypeChecker,
    exact: createStrictShapeTypeChecker,
  };

  /**
   * inlined Object.is polyfill to avoid requiring consumers ship their own
   * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/is
   */
  /*eslint-disable no-self-compare*/
  function is(x, y) {
    // SameValue algorithm
    if (x === y) {
      // Steps 1-5, 7-10
      // Steps 6.b-6.e: +0 != -0
      return x !== 0 || 1 / x === 1 / y;
    } else {
      // Step 6.a: NaN == NaN
      return x !== x && y !== y;
    }
  }
  /*eslint-enable no-self-compare*/

  /**
   * We use an Error-like object for backward compatibility as people may call
   * PropTypes directly and inspect their output. However, we don't use real
   * Errors anymore. We don't inspect their stack anyway, and creating them
   * is prohibitively expensive if they are created too often, such as what
   * happens in oneOfType() for any type before the one that matched.
   */
  function PropTypeError(message) {
    this.message = message;
    this.stack = '';
  }
  // Make `instanceof Error` still work for returned errors.
  PropTypeError.prototype = Error.prototype;

  function createChainableTypeChecker(validate) {
    if (true) {
      var manualPropTypeCallCache = {};
      var manualPropTypeWarningCount = 0;
    }
    function checkType(isRequired, props, propName, componentName, location, propFullName, secret) {
      componentName = componentName || ANONYMOUS;
      propFullName = propFullName || propName;

      if (secret !== ReactPropTypesSecret) {
        if (throwOnDirectAccess) {
          // New behavior only for users of `prop-types` package
          var err = new Error(
            'Calling PropTypes validators directly is not supported by the `prop-types` package. ' +
            'Use `PropTypes.checkPropTypes()` to call them. ' +
            'Read more at http://fb.me/use-check-prop-types'
          );
          err.name = 'Invariant Violation';
          throw err;
        } else if ( true && typeof console !== 'undefined') {
          // Old behavior for people using React.PropTypes
          var cacheKey = componentName + ':' + propName;
          if (
            !manualPropTypeCallCache[cacheKey] &&
            // Avoid spamming the console because they are often not actionable except for lib authors
            manualPropTypeWarningCount < 3
          ) {
            printWarning(
              'You are manually calling a React.PropTypes validation ' +
              'function for the `' + propFullName + '` prop on `' + componentName  + '`. This is deprecated ' +
              'and will throw in the standalone `prop-types` package. ' +
              'You may be seeing this warning due to a third-party PropTypes ' +
              'library. See https://fb.me/react-warning-dont-call-proptypes ' + 'for details.'
            );
            manualPropTypeCallCache[cacheKey] = true;
            manualPropTypeWarningCount++;
          }
        }
      }
      if (props[propName] == null) {
        if (isRequired) {
          if (props[propName] === null) {
            return new PropTypeError('The ' + location + ' `' + propFullName + '` is marked as required ' + ('in `' + componentName + '`, but its value is `null`.'));
          }
          return new PropTypeError('The ' + location + ' `' + propFullName + '` is marked as required in ' + ('`' + componentName + '`, but its value is `undefined`.'));
        }
        return null;
      } else {
        return validate(props, propName, componentName, location, propFullName);
      }
    }

    var chainedCheckType = checkType.bind(null, false);
    chainedCheckType.isRequired = checkType.bind(null, true);

    return chainedCheckType;
  }

  function createPrimitiveTypeChecker(expectedType) {
    function validate(props, propName, componentName, location, propFullName, secret) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== expectedType) {
        // `propValue` being instance of, say, date/regexp, pass the 'object'
        // check, but we can offer a more precise error message here rather than
        // 'of type `object`'.
        var preciseType = getPreciseType(propValue);

        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + preciseType + '` supplied to `' + componentName + '`, expected ') + ('`' + expectedType + '`.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createAnyTypeChecker() {
    return createChainableTypeChecker(emptyFunctionThatReturnsNull);
  }

  function createArrayOfTypeChecker(typeChecker) {
    function validate(props, propName, componentName, location, propFullName) {
      if (typeof typeChecker !== 'function') {
        return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside arrayOf.');
      }
      var propValue = props[propName];
      if (!Array.isArray(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an array.'));
      }
      for (var i = 0; i < propValue.length; i++) {
        var error = typeChecker(propValue, i, componentName, location, propFullName + '[' + i + ']', ReactPropTypesSecret);
        if (error instanceof Error) {
          return error;
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createElementTypeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      if (!isValidElement(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected a single ReactElement.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createElementTypeTypeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      if (!ReactIs.isValidElementType(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected a single ReactElement type.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createInstanceTypeChecker(expectedClass) {
    function validate(props, propName, componentName, location, propFullName) {
      if (!(props[propName] instanceof expectedClass)) {
        var expectedClassName = expectedClass.name || ANONYMOUS;
        var actualClassName = getClassName(props[propName]);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + actualClassName + '` supplied to `' + componentName + '`, expected ') + ('instance of `' + expectedClassName + '`.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createEnumTypeChecker(expectedValues) {
    if (!Array.isArray(expectedValues)) {
      if (true) {
        if (arguments.length > 1) {
          printWarning(
            'Invalid arguments supplied to oneOf, expected an array, got ' + arguments.length + ' arguments. ' +
            'A common mistake is to write oneOf(x, y, z) instead of oneOf([x, y, z]).'
          );
        } else {
          printWarning('Invalid argument supplied to oneOf, expected an array.');
        }
      }
      return emptyFunctionThatReturnsNull;
    }

    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      for (var i = 0; i < expectedValues.length; i++) {
        if (is(propValue, expectedValues[i])) {
          return null;
        }
      }

      var valuesString = JSON.stringify(expectedValues, function replacer(key, value) {
        var type = getPreciseType(value);
        if (type === 'symbol') {
          return String(value);
        }
        return value;
      });
      return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of value `' + String(propValue) + '` ' + ('supplied to `' + componentName + '`, expected one of ' + valuesString + '.'));
    }
    return createChainableTypeChecker(validate);
  }

  function createObjectOfTypeChecker(typeChecker) {
    function validate(props, propName, componentName, location, propFullName) {
      if (typeof typeChecker !== 'function') {
        return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside objectOf.');
      }
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an object.'));
      }
      for (var key in propValue) {
        if (has(propValue, key)) {
          var error = typeChecker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
          if (error instanceof Error) {
            return error;
          }
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createUnionTypeChecker(arrayOfTypeCheckers) {
    if (!Array.isArray(arrayOfTypeCheckers)) {
       true ? printWarning('Invalid argument supplied to oneOfType, expected an instance of array.') : undefined;
      return emptyFunctionThatReturnsNull;
    }

    for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
      var checker = arrayOfTypeCheckers[i];
      if (typeof checker !== 'function') {
        printWarning(
          'Invalid argument supplied to oneOfType. Expected an array of check functions, but ' +
          'received ' + getPostfixForTypeWarning(checker) + ' at index ' + i + '.'
        );
        return emptyFunctionThatReturnsNull;
      }
    }

    function validate(props, propName, componentName, location, propFullName) {
      for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
        var checker = arrayOfTypeCheckers[i];
        if (checker(props, propName, componentName, location, propFullName, ReactPropTypesSecret) == null) {
          return null;
        }
      }

      return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`.'));
    }
    return createChainableTypeChecker(validate);
  }

  function createNodeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      if (!isNode(props[propName])) {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`, expected a ReactNode.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createShapeTypeChecker(shapeTypes) {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type `' + propType + '` ' + ('supplied to `' + componentName + '`, expected `object`.'));
      }
      for (var key in shapeTypes) {
        var checker = shapeTypes[key];
        if (!checker) {
          continue;
        }
        var error = checker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
        if (error) {
          return error;
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createStrictShapeTypeChecker(shapeTypes) {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type `' + propType + '` ' + ('supplied to `' + componentName + '`, expected `object`.'));
      }
      // We need to check all keys in case some are required but missing from
      // props.
      var allKeys = assign({}, props[propName], shapeTypes);
      for (var key in allKeys) {
        var checker = shapeTypes[key];
        if (!checker) {
          return new PropTypeError(
            'Invalid ' + location + ' `' + propFullName + '` key `' + key + '` supplied to `' + componentName + '`.' +
            '\nBad object: ' + JSON.stringify(props[propName], null, '  ') +
            '\nValid keys: ' +  JSON.stringify(Object.keys(shapeTypes), null, '  ')
          );
        }
        var error = checker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
        if (error) {
          return error;
        }
      }
      return null;
    }

    return createChainableTypeChecker(validate);
  }

  function isNode(propValue) {
    switch (typeof propValue) {
      case 'number':
      case 'string':
      case 'undefined':
        return true;
      case 'boolean':
        return !propValue;
      case 'object':
        if (Array.isArray(propValue)) {
          return propValue.every(isNode);
        }
        if (propValue === null || isValidElement(propValue)) {
          return true;
        }

        var iteratorFn = getIteratorFn(propValue);
        if (iteratorFn) {
          var iterator = iteratorFn.call(propValue);
          var step;
          if (iteratorFn !== propValue.entries) {
            while (!(step = iterator.next()).done) {
              if (!isNode(step.value)) {
                return false;
              }
            }
          } else {
            // Iterator will provide entry [k,v] tuples rather than values.
            while (!(step = iterator.next()).done) {
              var entry = step.value;
              if (entry) {
                if (!isNode(entry[1])) {
                  return false;
                }
              }
            }
          }
        } else {
          return false;
        }

        return true;
      default:
        return false;
    }
  }

  function isSymbol(propType, propValue) {
    // Native Symbol.
    if (propType === 'symbol') {
      return true;
    }

    // falsy value can't be a Symbol
    if (!propValue) {
      return false;
    }

    // 19.4.3.5 Symbol.prototype[@@toStringTag] === 'Symbol'
    if (propValue['@@toStringTag'] === 'Symbol') {
      return true;
    }

    // Fallback for non-spec compliant Symbols which are polyfilled.
    if (typeof Symbol === 'function' && propValue instanceof Symbol) {
      return true;
    }

    return false;
  }

  // Equivalent of `typeof` but with special handling for array and regexp.
  function getPropType(propValue) {
    var propType = typeof propValue;
    if (Array.isArray(propValue)) {
      return 'array';
    }
    if (propValue instanceof RegExp) {
      // Old webkits (at least until Android 4.0) return 'function' rather than
      // 'object' for typeof a RegExp. We'll normalize this here so that /bla/
      // passes PropTypes.object.
      return 'object';
    }
    if (isSymbol(propType, propValue)) {
      return 'symbol';
    }
    return propType;
  }

  // This handles more types than `getPropType`. Only used for error messages.
  // See `createPrimitiveTypeChecker`.
  function getPreciseType(propValue) {
    if (typeof propValue === 'undefined' || propValue === null) {
      return '' + propValue;
    }
    var propType = getPropType(propValue);
    if (propType === 'object') {
      if (propValue instanceof Date) {
        return 'date';
      } else if (propValue instanceof RegExp) {
        return 'regexp';
      }
    }
    return propType;
  }

  // Returns a string that is postfixed to a warning about an invalid type.
  // For example, "undefined" or "of type array"
  function getPostfixForTypeWarning(value) {
    var type = getPreciseType(value);
    switch (type) {
      case 'array':
      case 'object':
        return 'an ' + type;
      case 'boolean':
      case 'date':
      case 'regexp':
        return 'a ' + type;
      default:
        return type;
    }
  }

  // Returns class name of the object, if any.
  function getClassName(propValue) {
    if (!propValue.constructor || !propValue.constructor.name) {
      return ANONYMOUS;
    }
    return propValue.constructor.name;
  }

  ReactPropTypes.checkPropTypes = checkPropTypes;
  ReactPropTypes.resetWarningCache = checkPropTypes.resetWarningCache;
  ReactPropTypes.PropTypes = ReactPropTypes;

  return ReactPropTypes;
};


/***/ }),

/***/ "./node_modules/prop-types/index.js":
/*!******************************************!*\
  !*** ./node_modules/prop-types/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

if (true) {
  var ReactIs = __webpack_require__(/*! react-is */ "./node_modules/react-is/index.js");

  // By explicitly using `prop-types` you are opting into new development behavior.
  // http://fb.me/prop-types-in-prod
  var throwOnDirectAccess = true;
  module.exports = __webpack_require__(/*! ./factoryWithTypeCheckers */ "./node_modules/prop-types/factoryWithTypeCheckers.js")(ReactIs.isElement, throwOnDirectAccess);
} else {}


/***/ }),

/***/ "./node_modules/prop-types/lib/ReactPropTypesSecret.js":
/*!*************************************************************!*\
  !*** ./node_modules/prop-types/lib/ReactPropTypesSecret.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var ReactPropTypesSecret = 'SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED';

module.exports = ReactPropTypesSecret;


/***/ }),

/***/ "./node_modules/react-is/cjs/react-is.development.js":
/*!***********************************************************!*\
  !*** ./node_modules/react-is/cjs/react-is.development.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/** @license React v16.13.1
 * react-is.development.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */





if (true) {
  (function() {
'use strict';

// The Symbol used to tag the ReactElement-like types. If there is no native Symbol
// nor polyfill, then a plain number is used for performance.
var hasSymbol = typeof Symbol === 'function' && Symbol.for;
var REACT_ELEMENT_TYPE = hasSymbol ? Symbol.for('react.element') : 0xeac7;
var REACT_PORTAL_TYPE = hasSymbol ? Symbol.for('react.portal') : 0xeaca;
var REACT_FRAGMENT_TYPE = hasSymbol ? Symbol.for('react.fragment') : 0xeacb;
var REACT_STRICT_MODE_TYPE = hasSymbol ? Symbol.for('react.strict_mode') : 0xeacc;
var REACT_PROFILER_TYPE = hasSymbol ? Symbol.for('react.profiler') : 0xead2;
var REACT_PROVIDER_TYPE = hasSymbol ? Symbol.for('react.provider') : 0xeacd;
var REACT_CONTEXT_TYPE = hasSymbol ? Symbol.for('react.context') : 0xeace; // TODO: We don't use AsyncMode or ConcurrentMode anymore. They were temporary
// (unstable) APIs that have been removed. Can we remove the symbols?

var REACT_ASYNC_MODE_TYPE = hasSymbol ? Symbol.for('react.async_mode') : 0xeacf;
var REACT_CONCURRENT_MODE_TYPE = hasSymbol ? Symbol.for('react.concurrent_mode') : 0xeacf;
var REACT_FORWARD_REF_TYPE = hasSymbol ? Symbol.for('react.forward_ref') : 0xead0;
var REACT_SUSPENSE_TYPE = hasSymbol ? Symbol.for('react.suspense') : 0xead1;
var REACT_SUSPENSE_LIST_TYPE = hasSymbol ? Symbol.for('react.suspense_list') : 0xead8;
var REACT_MEMO_TYPE = hasSymbol ? Symbol.for('react.memo') : 0xead3;
var REACT_LAZY_TYPE = hasSymbol ? Symbol.for('react.lazy') : 0xead4;
var REACT_BLOCK_TYPE = hasSymbol ? Symbol.for('react.block') : 0xead9;
var REACT_FUNDAMENTAL_TYPE = hasSymbol ? Symbol.for('react.fundamental') : 0xead5;
var REACT_RESPONDER_TYPE = hasSymbol ? Symbol.for('react.responder') : 0xead6;
var REACT_SCOPE_TYPE = hasSymbol ? Symbol.for('react.scope') : 0xead7;

function isValidElementType(type) {
  return typeof type === 'string' || typeof type === 'function' || // Note: its typeof might be other than 'symbol' or 'number' if it's a polyfill.
  type === REACT_FRAGMENT_TYPE || type === REACT_CONCURRENT_MODE_TYPE || type === REACT_PROFILER_TYPE || type === REACT_STRICT_MODE_TYPE || type === REACT_SUSPENSE_TYPE || type === REACT_SUSPENSE_LIST_TYPE || typeof type === 'object' && type !== null && (type.$$typeof === REACT_LAZY_TYPE || type.$$typeof === REACT_MEMO_TYPE || type.$$typeof === REACT_PROVIDER_TYPE || type.$$typeof === REACT_CONTEXT_TYPE || type.$$typeof === REACT_FORWARD_REF_TYPE || type.$$typeof === REACT_FUNDAMENTAL_TYPE || type.$$typeof === REACT_RESPONDER_TYPE || type.$$typeof === REACT_SCOPE_TYPE || type.$$typeof === REACT_BLOCK_TYPE);
}

function typeOf(object) {
  if (typeof object === 'object' && object !== null) {
    var $$typeof = object.$$typeof;

    switch ($$typeof) {
      case REACT_ELEMENT_TYPE:
        var type = object.type;

        switch (type) {
          case REACT_ASYNC_MODE_TYPE:
          case REACT_CONCURRENT_MODE_TYPE:
          case REACT_FRAGMENT_TYPE:
          case REACT_PROFILER_TYPE:
          case REACT_STRICT_MODE_TYPE:
          case REACT_SUSPENSE_TYPE:
            return type;

          default:
            var $$typeofType = type && type.$$typeof;

            switch ($$typeofType) {
              case REACT_CONTEXT_TYPE:
              case REACT_FORWARD_REF_TYPE:
              case REACT_LAZY_TYPE:
              case REACT_MEMO_TYPE:
              case REACT_PROVIDER_TYPE:
                return $$typeofType;

              default:
                return $$typeof;
            }

        }

      case REACT_PORTAL_TYPE:
        return $$typeof;
    }
  }

  return undefined;
} // AsyncMode is deprecated along with isAsyncMode

var AsyncMode = REACT_ASYNC_MODE_TYPE;
var ConcurrentMode = REACT_CONCURRENT_MODE_TYPE;
var ContextConsumer = REACT_CONTEXT_TYPE;
var ContextProvider = REACT_PROVIDER_TYPE;
var Element = REACT_ELEMENT_TYPE;
var ForwardRef = REACT_FORWARD_REF_TYPE;
var Fragment = REACT_FRAGMENT_TYPE;
var Lazy = REACT_LAZY_TYPE;
var Memo = REACT_MEMO_TYPE;
var Portal = REACT_PORTAL_TYPE;
var Profiler = REACT_PROFILER_TYPE;
var StrictMode = REACT_STRICT_MODE_TYPE;
var Suspense = REACT_SUSPENSE_TYPE;
var hasWarnedAboutDeprecatedIsAsyncMode = false; // AsyncMode should be deprecated

function isAsyncMode(object) {
  {
    if (!hasWarnedAboutDeprecatedIsAsyncMode) {
      hasWarnedAboutDeprecatedIsAsyncMode = true; // Using console['warn'] to evade Babel and ESLint

      console['warn']('The ReactIs.isAsyncMode() alias has been deprecated, ' + 'and will be removed in React 17+. Update your code to use ' + 'ReactIs.isConcurrentMode() instead. It has the exact same API.');
    }
  }

  return isConcurrentMode(object) || typeOf(object) === REACT_ASYNC_MODE_TYPE;
}
function isConcurrentMode(object) {
  return typeOf(object) === REACT_CONCURRENT_MODE_TYPE;
}
function isContextConsumer(object) {
  return typeOf(object) === REACT_CONTEXT_TYPE;
}
function isContextProvider(object) {
  return typeOf(object) === REACT_PROVIDER_TYPE;
}
function isElement(object) {
  return typeof object === 'object' && object !== null && object.$$typeof === REACT_ELEMENT_TYPE;
}
function isForwardRef(object) {
  return typeOf(object) === REACT_FORWARD_REF_TYPE;
}
function isFragment(object) {
  return typeOf(object) === REACT_FRAGMENT_TYPE;
}
function isLazy(object) {
  return typeOf(object) === REACT_LAZY_TYPE;
}
function isMemo(object) {
  return typeOf(object) === REACT_MEMO_TYPE;
}
function isPortal(object) {
  return typeOf(object) === REACT_PORTAL_TYPE;
}
function isProfiler(object) {
  return typeOf(object) === REACT_PROFILER_TYPE;
}
function isStrictMode(object) {
  return typeOf(object) === REACT_STRICT_MODE_TYPE;
}
function isSuspense(object) {
  return typeOf(object) === REACT_SUSPENSE_TYPE;
}

exports.AsyncMode = AsyncMode;
exports.ConcurrentMode = ConcurrentMode;
exports.ContextConsumer = ContextConsumer;
exports.ContextProvider = ContextProvider;
exports.Element = Element;
exports.ForwardRef = ForwardRef;
exports.Fragment = Fragment;
exports.Lazy = Lazy;
exports.Memo = Memo;
exports.Portal = Portal;
exports.Profiler = Profiler;
exports.StrictMode = StrictMode;
exports.Suspense = Suspense;
exports.isAsyncMode = isAsyncMode;
exports.isConcurrentMode = isConcurrentMode;
exports.isContextConsumer = isContextConsumer;
exports.isContextProvider = isContextProvider;
exports.isElement = isElement;
exports.isForwardRef = isForwardRef;
exports.isFragment = isFragment;
exports.isLazy = isLazy;
exports.isMemo = isMemo;
exports.isPortal = isPortal;
exports.isProfiler = isProfiler;
exports.isStrictMode = isStrictMode;
exports.isSuspense = isSuspense;
exports.isValidElementType = isValidElementType;
exports.typeOf = typeOf;
  })();
}


/***/ }),

/***/ "./node_modules/react-is/index.js":
/*!****************************************!*\
  !*** ./node_modules/react-is/index.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (false) {} else {
  module.exports = __webpack_require__(/*! ./cjs/react-is.development.js */ "./node_modules/react-is/cjs/react-is.development.js");
}


/***/ }),

/***/ "@babel/runtime/regenerator":
/*!**********************************************!*\
  !*** external {"this":"regeneratorRuntime"} ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["regeneratorRuntime"]; }());

/***/ }),

/***/ "@woocommerce/components":
/*!*********************************************!*\
  !*** external {"this":["wc","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wc"]["components"]; }());

/***/ }),

/***/ "@wordpress/api-fetch":
/*!*******************************************!*\
  !*** external {"this":["wp","apiFetch"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["apiFetch"]; }());

/***/ }),

/***/ "@wordpress/components":
/*!*********************************************!*\
  !*** external {"this":["wp","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["components"]; }());

/***/ }),

/***/ "@wordpress/data":
/*!***************************************!*\
  !*** external {"this":["wp","data"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["data"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/hooks":
/*!****************************************!*\
  !*** external {"this":["wp","hooks"]} ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["hooks"]; }());

/***/ }),

/***/ "@wordpress/i18n":
/*!***************************************!*\
  !*** external {"this":["wp","i18n"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["i18n"]; }());

/***/ }),

/***/ "@wordpress/url":
/*!**************************************!*\
  !*** external {"this":["wp","url"]} ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["url"]; }());

/***/ }),

/***/ "lodash":
/*!**********************************!*\
  !*** external {"this":"lodash"} ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["lodash"]; }());

/***/ })

/******/ });
//# sourceMappingURL=index.js.map