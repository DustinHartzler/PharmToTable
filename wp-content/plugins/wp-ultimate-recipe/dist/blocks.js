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
/******/ 	return __webpack_require__(__webpack_require__.s = "./core/assets/js/blocks.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./core/assets/js/blocks.js":
/*!**********************************!*\
  !*** ./core/assets/js/blocks.js ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _blocks_recipe__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./blocks/recipe */ \"./core/assets/js/blocks/recipe.js\");\n/* harmony import */ var _blocks_recipe__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_blocks_recipe__WEBPACK_IMPORTED_MODULE_0__);\n\n\n//# sourceURL=webpack:///./core/assets/js/blocks.js?");

/***/ }),

/***/ "./core/assets/js/blocks/recipe.js":
/*!*****************************************!*\
  !*** ./core/assets/js/blocks/recipe.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var __ = wp.i18n.__;\nvar _wp$components = wp.components,\n    PanelBody = _wp$components.PanelBody,\n    SelectControl = _wp$components.SelectControl;\nvar registerBlockType = wp.blocks.registerBlockType;\nvar InspectorControls = wp.editor.InspectorControls;\n\n\nregisterBlockType('wp-ultimate-recipe/recipe', {\n    title: __('WPURP Recipe'),\n    description: __('Display a WP Ultimate Recipe box.'),\n    icon: 'media-document',\n    keywords: ['wpurp'],\n    category: 'wp-ultimate-recipe',\n    supports: {\n        html: false\n    },\n    attributes: {\n        id: {\n            type: 'string',\n            default: 'random'\n        },\n        template: {\n            type: 'string',\n            default: 'default'\n        }\n    },\n    transforms: {\n        from: [{\n            type: 'shortcode',\n            tag: 'ultimate-recipe',\n            attributes: {\n                id: {\n                    type: 'string',\n                    shortcode: function shortcode(_ref) {\n                        var _ref$named$id = _ref.named.id,\n                            id = _ref$named$id === undefined ? '' : _ref$named$id;\n\n                        return id.replace('id', '');\n                    }\n                },\n                template: {\n                    type: 'string',\n                    shortcode: function shortcode(_ref2) {\n                        var _ref2$named$template = _ref2.named.template,\n                            template = _ref2$named$template === undefined ? '' : _ref2$named$template;\n\n                        return template.replace('template', '');\n                    }\n                }\n            }\n        }]\n    },\n    edit: function edit(props) {\n        var attributes = props.attributes,\n            setAttributes = props.setAttributes,\n            isSelected = props.isSelected,\n            className = props.className;\n\n\n        var IdOptions = [{\n            value: 'random',\n            label: __('Random')\n        }, {\n            value: 'latest',\n            label: __('Latest')\n        }].concat(wpurp_blocks.shortcodes.recipes_by_date);\n\n        return React.createElement(\n            'div',\n            { className: className, style: {\n                    border: '1px dashed #444',\n                    padding: '10px'\n                } },\n            React.createElement(\n                InspectorControls,\n                null,\n                React.createElement(\n                    PanelBody,\n                    { title: __('Recipe Details') },\n                    React.createElement(SelectControl, {\n                        label: __('Recipe'),\n                        value: attributes.id,\n                        options: IdOptions,\n                        onChange: function onChange(id) {\n                            return setAttributes({\n                                id: id\n                            });\n                        }\n                    }),\n                    React.createElement(SelectControl, {\n                        label: __('Template'),\n                        value: attributes.template,\n                        options: wpurp_blocks.shortcodes.templates,\n                        onChange: function onChange(template) {\n                            return setAttributes({\n                                template: template\n                            });\n                        }\n                    })\n                )\n            ),\n            React.createElement(\n                'strong',\n                null,\n                'Placeholder for WP Ultimate Recipe recipe (',\n                attributes.id,\n                ')'\n            )\n        );\n    },\n    save: function save(props) {\n        var id = props.attributes.id;\n\n        if (!id) {\n            return null;\n        } else {\n            return '[ultimate-recipe id=\"' + props.attributes.id + '\" template=\"' + props.attributes.template + '\"]';\n        }\n    }\n});\n\n//# sourceURL=webpack:///./core/assets/js/blocks/recipe.js?");

/***/ })

/******/ });