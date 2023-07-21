/******/(()=>{// webpackBootstrap
/******/var e={
/***/8598:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>s
/* harmony export */});
/* harmony import */var n=r(69307),o=r(70444);
/* harmony import */
/**
 * WordPress dependencies
 */
const s=(0,n.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,n.createElement)(o.Path,{d:"M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"}));
/* harmony default export */}
//# sourceMappingURL=close-small.js.map
/***/,
/***/60042:
/***/(e,t)=>{var r;
/*!
  Copyright (c) 2018 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */!function(){"use strict";var n={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var r=arguments[t];if(r){var s=typeof r;if("string"===s||"number"===s)e.push(r);else if(Array.isArray(r)){if(r.length){var i=o.apply(null,r);i&&e.push(i)}}else if("object"===s)if(r.toString===Object.prototype.toString)for(var a in r)n.call(r,a)&&r[a]&&e.push(a);else e.push(r.toString())}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(r=function(){return o}.apply(t,[]))||(e.exports=r)}()}
/***/,
/***/33743:
/***/e=>{"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * 
 */function t(e){return function(){return e}}
/**
 * This function accepts and discards inputs; it has no side effects. This is
 * primarily useful idiomatically for overridable function endpoints which
 * always need to be callable, since JS lacks a null-call idiom ala Cocoa.
 */var r=function(){};r.thatReturns=t,r.thatReturnsFalse=t(!1),r.thatReturnsTrue=t(!0),r.thatReturnsNull=t(null),r.thatReturnsThis=function(){return this},r.thatReturnsArgument=function(e){return e},e.exports=r}
/***/,
/***/87081:
/***/e=>{"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 *
 */
/**
 * Use invariant() to assert state which your program assumes to be true.
 *
 * Provide sprintf-style format (only %s is supported) and arguments
 * to provide information about what broke and what you were
 * expecting.
 *
 * The invariant message will be stripped in production, but the invariant
 * will remain to ensure logic does not differ in production.
 */e.exports=function(e,t,r,n,o,s,i,a){if(!e){var l;if(void 0===t)l=new Error("Minified exception occurred; use the non-minified dev environment for the full error message and additional helpful warnings.");else{var c=[r,n,o,s,i,a],u=0;(l=new Error(t.replace(/%s/g,(function(){return c[u++]})))).name="Invariant Violation"}// we don't care about invariant's own frame
throw l.framesToPop=1,l}}}
/***/,
/***/82264:
/***/(e,t,r)=>{"use strict";
/**
 * Copyright (c) 2014-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 *
 */var n=r(33743);
/**
 * Similar to invariant but only logs a warning if the condition is not met.
 * This can be used to log issues in development environments in critical
 * paths. Removing the logging code for production environments will keep the
 * same logic and follow the same code paths.
 */e.exports=n}
/***/,
/***/19219:
/***/(e,t,r)=>{"use strict";var n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},o=a(r(99196)),s=a(r(40349)),i=a(r(58470));
/**
                                                                                                                                                                                                                                                                               * External Dependencies
                                                                                                                                                                                                                                                                               */
/**
 * Internal Dependencies
 */function a(e){return e&&e.__esModule?e:{default:e}}var l=void 0;function c(e,t){var r,i,a,u,p,d,f,m,v=[],h={};for(d=0;d<e.length;d++)if("string"!==(p=e[d]).type){
// component node should at least be set
if(!t.hasOwnProperty(p.value)||void 0===t[p.value])throw new Error("Invalid interpolation, missing component node: `"+p.value+"`");
// should be either ReactElement or null (both type "object"), all other types deprecated
if("object"!==n(t[p.value]))throw new Error("Invalid interpolation, component node must be a ReactElement or null: `"+p.value+"`","\n> "+l);
// we should never see a componentClose token in this loop
if("componentClose"===p.type)throw new Error("Missing opening component token: `"+p.value+"`");if("componentOpen"===p.type){r=t[p.value],a=d;break}
// componentSelfClosing token
v.push(t[p.value])}else v.push(p.value);return r&&(u=function(e,t){var r,n,o=t[e],s=0;for(n=e+1;n<t.length;n++)if((r=t[n]).value===o.value){if("componentOpen"===r.type){s++;continue}if("componentClose"===r.type){if(0===s)return n;s--}}
// if we get this far, there was no matching close token
throw new Error("Missing closing component token `"+o.value+"`")}(a,e),f=c(e.slice(a+1,u),t),i=o.default.cloneElement(r,{},f),v.push(i),u<e.length-1&&(m=c(e.slice(u+1),t),v=v.concat(m))),1===v.length?v[0]:(v.forEach((function(e,t){e&&(h["interpolation-child-"+t]=e)})),(0,s.default)(h))}t.Z=function(e){var t=e.mixedString,r=e.components,o=e.throwErrors;if(l=t,!r)return t;if("object"!==(void 0===r?"undefined":n(r))){if(o)throw new Error("Interpolation Error: unable to process `"+t+"` because components is not an object");return t}var s=(0,i.default)(t);try{return c(s,r)}catch(e){if(o)throw new Error("Interpolation Error: unable to process `"+t+"` because of error `"+e.message+"`");return t}}}
//# sourceMappingURL=index.js.map
/***/,
/***/58470:
/***/e=>{"use strict";function t(e){
// {{/example}}
return e.match(/^\{\{\//)?{type:"componentClose",value:e.replace(/\W/g,"")}:
// {{example /}}
e.match(/\/\}\}$/)?{type:"componentSelfClosing",value:e.replace(/\W/g,"")}:
// {{example}}
e.match(/^\{\{/)?{type:"componentOpen",value:e.replace(/\W/g,"")}:{type:"string",value:e}}e.exports=function(e){// split to components and strings
return e.split(/(\{\{\/?\s*\w+\s*\/?\}\})/g).map(t)}}
//# sourceMappingURL=tokenize.js.map
/***/,
/***/40349:
/***/(e,t,r)=>{"use strict";
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */var n=r(99196),o="function"==typeof Symbol&&Symbol.for&&Symbol.for("react.element")||60103,s=r(33743),i=r(87081),a=r(82264),l="function"==typeof Symbol&&Symbol.iterator;function c(e,t){
// Do some typechecking here since we call this blindly. We want to ensure
// that we don't block potential future ES APIs.
return e&&"object"==typeof e&&null!=e.key?(r=e.key,n={"=":"=0",":":"=2"},"$"+(""+r).replace(/[=:]/g,(function(e){return n[e]}))):t.toString(36);
// Implicit key determined by the index in the set
var r,n}function u(e,t,r,n){var s,a=typeof e;if("undefined"!==a&&"boolean"!==a||(
// All of the above are perceived as null.
e=null),null===e||"string"===a||"number"===a||
// The following is inlined from ReactElement. This means we can optimize
// some checks. React Fiber also inlines this logic for similar purposes.
"object"===a&&e.$$typeof===o)return r(n,e,
// If it's the only child, treat the name as if it was wrapped in an array
// so that it's consistent if the number of children grows.
""===t?"."+c(e,0):t),1;var p=0,d=""===t?".":t+":";// Count of children found in the current subtree.
if(Array.isArray(e))for(var f=0;f<e.length;f++)p+=u(s=e[f],d+c(s,f),r,n);else{var m=// Before Symbol spec.
function(e){var t=e&&(l&&e[l]||e["@@iterator"]);if("function"==typeof t)return t}(e);if(m){0;for(var v,h=m.call(e),g=0;!(v=h.next()).done;)p+=u(s=v.value,d+c(s,g++),r,n)}else if("object"===a){0;var y=""+e;i(!1,"Objects are not valid as a React child (found: %s).%s","[object Object]"===y?"object with keys {"+Object.keys(e).join(", ")+"}":y,"")}}return p}var p=/\/+/g;function d(e){return(""+e).replace(p,"$&/")}var f,m,v=h,h=function(e){var t=this;if(t.instancePool.length){var r=t.instancePool.pop();return t.call(r,e),r}return new t(e)},g=function(e){var t=this;i(e instanceof t,"Trying to release an instance into a pool of a different type."),e.destructor(),t.instancePool.length<t.poolSize&&t.instancePool.push(e)};function y(e,t,r,n){this.result=e,this.keyPrefix=t,this.func=r,this.context=n,this.count=0}function E(e,t,r){var o,i,a=e.result,l=e.keyPrefix,c=e.func,u=e.context,p=c.call(u,t,e.count++);Array.isArray(p)?b(p,a,r,s.thatReturnsArgument):null!=p&&(n.isValidElement(p)&&(o=p,i=
// Keep both the (mapped) and old keys if they differ, just as
// traverseAllChildren used to do for objects as children
l+(!p.key||t&&t.key===p.key?"":d(p.key)+"/")+r,p=n.cloneElement(o,{key:i},void 0!==o.props?o.props.children:void 0)),a.push(p))}function b(e,t,r,n,o){var s="";null!=r&&(s=d(r)+"/");var i=y.getPooled(t,s,n,o);!function(e,t,r){null==e||u(e,"",t,r)}(e,E,i),y.release(i)}y.prototype.destructor=function(){this.result=null,this.keyPrefix=null,this.func=null,this.context=null,this.count=0},f=function(e,t,r,n){var o=this;if(o.instancePool.length){var s=o.instancePool.pop();return o.call(s,e,t,r,n),s}return new o(e,t,r,n)},(m=y).instancePool=[],m.getPooled=f||v,m.poolSize||(m.poolSize=10),m.release=g;e.exports=function(e){if("object"!=typeof e||!e||Array.isArray(e))return a(!1,"React.addons.createFragment only accepts a single object. Got: %s",e),e;if(n.isValidElement(e))return a(!1,"React.addons.createFragment does not accept a ReactElement without a wrapper object."),e;i(1!==e.nodeType,"React.addons.createFragment(...): Encountered an invalid child; DOM elements are not valid children of React components.");var t=[];for(var r in e)b(e[r],t,r,s.thatReturnsArgument);return t}}
/***/,
/***/90631:
/***/(e,t,r)=>{"use strict";r.r(t),
/* harmony export */r.d(t,{
/* harmony export */fetchFromAPI:()=>/* binding */p
/* harmony export */,wait:()=>/* binding */d
/* harmony export */,loadCurrentJobState:()=>/* binding */f
/* harmony export */,updateJobState:()=>/* binding */m
/* harmony export */,pollJobProgress:()=>/* binding */v
/* harmony export */,setJobState:()=>/* binding */h
/* harmony export */,submitStartImport:()=>/* binding */g
/* harmony export */,startImport:()=>/* binding */y
/* harmony export */,successStartImport:()=>/* binding */E
/* harmony export */,errorStartImport:()=>/* binding */b
/* harmony export */,uploadFileForLevel:()=>/* binding */w
/* harmony export */,throwEarlyUploadError:()=>/* binding */_
/* harmony export */,startFileUploadAction:()=>/* binding */S
/* harmony export */,successFileUpload:()=>/* binding */Z
/* harmony export */,errorFileUpload:()=>/* binding */x
/* harmony export */,deleteLevelFile:()=>/* binding */O
/* harmony export */,startDeleteLevelFileAction:()=>/* binding */k
/* harmony export */,successDeleteLevelFileAction:()=>/* binding */L
/* harmony export */,errorDeleteLevelFileAction:()=>/* binding */T
/* harmony export */,resetState:()=>/* binding */F
/* harmony export */,restartImporter:()=>/* binding */j
/* harmony export */});
/* harmony import */var n=r(11092),o=r(25463),s=r(20832),i=r(70468),a=r(97769),l=(0,n/* ["default"] */.Z)().mark(m),c=(0,n/* ["default"] */.Z)().mark(O),u=(0,n/* ["default"] */.Z)().mark(j),p=function(e){return{type:o/* .FETCH_FROM_API */.nx,request:e}},d=function(e){return{type:o/* .WAIT */.wF,timeout:e}},f=(0,s/* .composeFetchAction */.GI)(o/* .START_LOAD_CURRENT_JOB_STATE */.jn,(0,n/* ["default"] */.Z)().mark((function e(){var t;return(0,n/* ["default"] */.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,p({path:(0,a/* .buildJobEndpointUrl */.D)(o/* .API_SPECIAL_ACTIVE_JOB_ID */.BL)});case 2:return t=e.sent,e.abrupt("return",(0,i/* .normalizeImportData */.wh)(t));case 4:case"end":return e.stop()}}),e)})),o/* .SUCCESS_LOAD_CURRENT_JOB_STATE */.H1,o/* .ERROR_LOAD_CURRENT_JOB_STATE */.sw);
/* harmony import */
/**
 * Update job state in the background.
 *
 * @param {string} jobId The job ID.
 */
function m(e){var t;return(0,n/* ["default"] */.Z)().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,r.next=3,p({path:(0,a/* .buildJobEndpointUrl */.D)(e)});case 3:return t=r.sent,r.next=6,h((0,i/* .normalizeImportData */.wh)(t));case 6:r.next=10;break;case 8:r.prev=8,r.t0=r.catch(0);case 10:case"end":return r.stop()}}),l,null,[[0,8]])}
/**
 * Run job batches and query progress until it is completed.
 *
 * @param {string} jobId Job ID.
 */var v=(0,n/* ["default"] */.Z)().mark((function e(t){var r;return(0,n/* ["default"] */.Z)().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.prev=0,n.next=3,p({path:(0,a/* .buildJobEndpointUrl */.D)(t,["process"]),method:"POST"});case 3:return r=n.sent,n.next=6,h((0,i/* .normalizeImportData */.wh)(r));case 6:if("completed"===r.status.status){n.next=9;break}return n.delegateYield(e(t),"t0",9);case 9:n.next=16;break;case 11:return n.prev=11,n.t1=n.catch(0),n.next=15,d(2e3);case 15:return n.delegateYield(e(t),"t2",16);case 16:case"end":return n.stop()}}),e,null,[[0,11]])})),h=function(e){return{type:o/* .SET_JOB_STATE */.gL,data:e}};
/**
 * @typedef  {Object} SetJobStateAction
 * @property {string} type Action type.
 * @property {Object} data Job state.
 */
/**
 * Set job state action creator.
 *
 * @param {Object} data Job state.
 * @return {SetJobStateAction} Set job state action.
 */
/**
 * Start import.
 *
 * @param {string}   jobId               The job identifier.
 * @param {Object}   [options]
 * @param {Function} [options.onSuccess] On Success handler.
 * @param {Function} [options.onError]   On Error handler.
 */
function g(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},r=t.onSuccess,o=t.onError;
return(0,n/* ["default"] */.Z)().mark((function t(){var s;return(0,n/* ["default"] */.Z)().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,y();case 2:if(t.prev=2,e){t.next=7;break}return t.next=6,b({message:null});case 6:return t.abrupt("return");case 7:return t.next=9,p({path:(0,a/* .buildJobEndpointUrl */.D)(e,["start"]),method:"POST"});case 9:return s=t.sent,t.next=12,E((0,i/* .normalizeImportData */.wh)(s));case 12:r&&r(),t.next=20;break;case 15:return t.prev=15,t.t0=t.catch(2),t.next=19,b(t.t0);case 19:o&&o(t.t0);case 20:case"end":return t.stop()}}),t,null,[[2,15]])}))()}
/**
 * @typedef  {Object} StartImportAction
 * @property {string} type Action type.
 */
/**
 * Start action to start import.
 *
 * @return {StartImportAction} Start import action.
 */var y=function(){return{type:o/* .START_IMPORT */.FG}},E=function(e){return{type:o/* .SUCCESS_START_IMPORT */.XM,data:e}},b=function(e){return{type:o/* .ERROR_START_IMPORT */.UX,error:e}};
/**
 * @typedef  {Object} SuccessStartImportAction
 * @property {string} type Action type.
 * @property {Object} data Data object.
 */
/**
 * Success submit action creator.
 *
 * @param {Object} data Importer data.
 * @return {SuccessStartImportAction} Success submit action.
 */
/**
 * Upload a file for a level.
 *
 * @param {string}   jobId               The job identifier.
 * @param {string}   level               Level identifier.
 * @param {Object}   uploadData          Data to submit.
 * @param {Object}   [options]
 * @param {Function} [options.onSuccess] Callback on success.
 * @param {Function} [options.onError]   Callback on error.
 */
function w(e,t,r){var s=arguments.length>3&&void 0!==arguments[3]?arguments[3]:{},l=s.onSuccess,c=s.onError;
return(0,n/* ["default"] */.Z)().mark((function s(){var u;return(0,n/* ["default"] */.Z)().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,S(t,r);case 2:return n.prev=2,e||(e=o/* .API_SPECIAL_ACTIVE_JOB_ID */.BL),n.next=6,p({path:(0,a/* .buildJobEndpointUrl */.D)(e,["file",t]),method:"POST",body:r});case 6:return u=n.sent,n.next=9,Z(t,(0,i/* .normalizeImportData */.wh)(u));case 9:l&&l(),n.next=17;break;case 12:return n.prev=12,n.t0=n.catch(2),n.next=16,x(t,n.t0);case 16:c&&c(n.t0);case 17:case"end":return n.stop()}}),s,null,[[2,12]])}))()}
/**
 * Throw an early upload error.
 *
 * @param {string} level    Level identifier.
 * @param {string} errorMsg Error object or false.
 */var _=function(e,t){return x(e,{code:"",message:t})},S=function(e,t){return{type:o/* .START_UPLOAD_IMPORT_DATA_FILE */.fo,level:e,uploadData:t}},Z=function(e,t){return{type:o/* .SUCCESS_UPLOAD_IMPORT_DATA_FILE */.i7,level:e,data:t}},x=function(e,t){return{type:o/* .ERROR_UPLOAD_IMPORT_DATA_FILE */.wO,level:e,error:t}};
/**
 * @typedef  {Object} StartFileUploadAction
 * @property {string} type       Action type.
 * @property {string} level      Level identifier.
 * @property {Object} uploadData Error object or false.
 */
/**
 * Start file upload action creator.
 *
 * @param {string} level      Level identifier.
 * @param {Object} uploadData Data to submit.
 *
 * @return {StartFileUploadAction} Start file upload action.
 */
/**
 * Delete a level file.
 *
 * @param {string} jobId The job identifier.
 * @param {string} level Level identifier.
 */
function O(e,t){var r;return(0,n/* ["default"] */.Z)().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,k(t);case 2:if(n.prev=2,e){n.next=7;break}return n.next=6,T({message:null});case 6:return n.abrupt("return");case 7:return n.next=9,p({path:(0,a/* .buildJobEndpointUrl */.D)(e,["file",t]),method:"DELETE"});case 9:return r=n.sent,n.next=12,L(t,(0,i/* .normalizeImportData */.wh)(r));case 12:n.next=18;break;case 14:return n.prev=14,n.t0=n.catch(2),n.next=18,T(t,n.t0);case 18:case"end":return n.stop()}}),c,null,[[2,14]])}
/**
 * @typedef  {Object} StartDeleteLevelFileAction
 * @property {string} type  Action type.
 * @property {string} level Level identifier.
 */
/**
 * Start file upload action creator.
 *
 * @param {string} level Level identifier.
 *
 * @return {StartDeleteLevelFileAction} Start delete file action.
 */var k=function(e){return{type:o/* .START_DELETE_IMPORT_DATA_FILE */.AI,level:e}},L=function(e,t){return{type:o/* .SUCCESS_DELETE_IMPORT_DATA_FILE */.Wi,level:e,data:t}},T=function(e,t){return{type:o/* .ERROR_DELETE_IMPORT_DATA_FILE */.sW,level:e,error:t}},F=function(){return{type:o/* .RESET_STATE */.so}};
/**
 * @typedef  {Object} SuccessDeleteLevelFileAction
 * @property {string} type  Action type.
 * @property {string} level Level identifier.
 * @property {Object} data  Data object.
 */
/**
 * Success delete level file action.
 *
 * @param {string} level Level identifier.
 * @param {Object} data  Importer data.
 * @return {SuccessDeleteLevelFileAction} Success delete level file action.
 */
/**
 * Restart importer.
 */
function j(){return(0,n/* ["default"] */.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,F();case 2:return e.next=4,f();case 4:case"end":return e.stop()}}),u)}
/***/},
/***/25463:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Lt:()=>/* binding */n
/* harmony export */,BL:()=>/* binding */o
/* harmony export */,nx:()=>/* binding */s
/* harmony export */,wF:()=>/* binding */i
/* harmony export */,jn:()=>/* binding */a
/* harmony export */,H1:()=>/* binding */l
/* harmony export */,sw:()=>/* binding */c
/* harmony export */,gL:()=>/* binding */u
/* harmony export */,FG:()=>/* binding */p
/* harmony export */,XM:()=>/* binding */d
/* harmony export */,UX:()=>/* binding */f
/* harmony export */,fo:()=>/* binding */m
/* harmony export */,i7:()=>/* binding */v
/* harmony export */,wO:()=>/* binding */h
/* harmony export */,AI:()=>/* binding */g
/* harmony export */,Wi:()=>/* binding */y
/* harmony export */,sW:()=>/* binding */E
/* harmony export */,ao:()=>/* binding */b
/* harmony export */,$L:()=>/* binding */w
/* harmony export */,so:()=>/* binding */_
/* harmony export */});
/**
 * Data import constants.
 */
var n="/sensei-internal/v1/import/",o="active",s="FETCH_FROM_API",i="WAIT",a="START_LOAD_CURRENT_JOB_STATE",l="SUCCESS_LOAD_CURRENT_JOB_STATE",c="ERROR_LOAD_CURRENT_JOB_STATE",u="SET_JOB_STATE",p="START_IMPORT",d="SUCCESS_START_IMPORT",f="ERROR_START_IMPORT",m="START_UPLOAD_IMPORT_DATA_FILE",v="SUCCESS_UPLOAD_IMPORT_DATA_FILE",h="ERROR_UPLOAD_IMPORT_DATA_FILE",g="START_DELETE_IMPORT_DATA_FILE",y="SUCCESS_DELETE_IMPORT_DATA_FILE",E="ERROR_DELETE_IMPORT_DATA_FILE",b="SET_IMPORT_LOG",w="ERROR_FETCH_IMPORT_LOG",_="RESET_STATE"}
/***/,
/***/42588:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>l
/* harmony export */});
/* harmony import */var n,o=r(64649),s=r(86989),i=r.n(s),a=r(25463);
/* harmony import */
/**
 * WordPress dependencies
 */
/**
 * Internal dependencies
 */
/* harmony default export */const l=(n={},(0,o/* ["default"] */.Z)(n,a/* .FETCH_FROM_API */.nx,(function(e){var t=e.request;return i()(t)})),(0,o/* ["default"] */.Z)(n,a/* .WAIT */.wF,(function(e){var t=e.timeout;return new Promise((function(e){return setTimeout(e,t)}))})),n);
/***/},
/***/51842:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>c
/* harmony export */});
/* harmony import */var n=r(9818),o=r(19790),s=r(90631),i=r(50330),a=r(42588),l=r(43802);
/* harmony import */
/* harmony default export */const c=function(){(0,n.registerStore)("sensei/import",{reducer:o/* ["default"] */.Z,actions:s,selectors:i,controls:a/* ["default"] */.Z,resolvers:l})};
/***/},
/***/70468:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */wh:()=>/* binding */c
/* harmony export */});
/* unused harmony exports normalizeUploadsState, parseCompletedSteps */
/* harmony import */var n=r(50189),o=r(53782),s=r(66886),i=["id","files","status","results"],a=function(e){var t={};return Object.entries(e).forEach((function(e){var r=(0,s/* ["default"] */.Z)(e,2),n=r[0],o=r[1];o.name&&(t[n]={filename:o.name,isUploaded:!0})})),t},l=function(e){return"pending"===e.status?["upload"]:"completed"===e.status?["upload","progress"]:[]},c=function(e){var t=e.id,r=e.files,s=e.status,c=e.results,u=(0,o/* ["default"] */.Z)(e,i);return(0,n/* ["default"] */.Z)((0,n/* ["default"] */.Z)({},u),{},{jobId:t,progress:s,upload:a(r||[]),completedSteps:l(s||{}),done:{results:c}})};
/* harmony import */}
/***/,
/***/19790:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>c
/* harmony export */});
/* harmony import */var n=r(64649),o=r(50189),s=r(92819),i=r(25463),a={jobId:null,isFetching:!0,fetchError:!1,completedSteps:[],upload:{isSubmitting:!1,errorMsg:null,courses:{isUploaded:!1,isUploading:!1,isDeleting:!1,hasError:!1,errorMsg:null,filename:null},lessons:{isUploaded:!1,isUploading:!1,isDeleting:!1,hasError:!1,errorMsg:null,filename:null},questions:{isUploaded:!1,isUploading:!1,isDeleting:!1,hasError:!1,errorMsg:null,filename:null}},progress:{status:"",percentage:0},done:{results:null,logs:null}},l=function(e,t,r){return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{upload:(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload),{},(0,n/* ["default"] */.Z)({},t,r))})};
/* harmony import */
/**
 * Data importer reducer.
 *
 * @param {Object}         state  Current state.
 * @param {{type: string}} action Action to update the state.
 *
 * @return {Object} State updated.
 */
/* harmony default export */const c=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:a,t=arguments.length>1?arguments[1]:void 0;switch(t.type){case i/* .START_LOAD_CURRENT_JOB_STATE */.jn:return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{isFetching:!0,fetchError:!1});case i/* .SUCCESS_LOAD_CURRENT_JOB_STATE */.H1:return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},(0,s.merge)({},e,t.data)),{},{isFetching:!1});case i/* .SET_JOB_STATE */.gL:return(0,o/* ["default"] */.Z)({},(0,s.merge)({},e,t.data));case i/* .ERROR_LOAD_CURRENT_JOB_STATE */.sw:return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{isFetching:!1,fetchError:t.error});case i/* .START_IMPORT */.FG:return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{upload:(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload),{},{errorMsg:null,isSubmitting:!0})});case i/* .ERROR_START_IMPORT */.UX:return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{upload:(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload),{},{errorMsg:t.error.message,isSubmitting:!1})});case i/* .SUCCESS_START_IMPORT */.XM:return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{completedSteps:t.data.completedSteps,upload:(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload),{},{isSubmitting:!1}),progress:(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.progress),t.data.progress)});case i/* .START_UPLOAD_IMPORT_DATA_FILE */.fo:return l(e,t.level,(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload[t.level]),{},{isUploaded:!1,isUploading:!0,isDeleting:!1,hasError:!1,errorMsg:null,filename:null}));case i/* .SUCCESS_UPLOAD_IMPORT_DATA_FILE */.i7:return l((0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{jobId:t.data.jobId}),t.level,(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload[t.level]),t.data.upload[t.level]),{},{isUploading:!1,isDeleting:!1,hasError:!1,errorMsg:null}));case i/* .ERROR_UPLOAD_IMPORT_DATA_FILE */.wO:return l(e,t.level,(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload[t.level]),{},{isUploaded:!1,isUploading:!1,isDeleting:!1,hasError:!0,errorMsg:t.error.message,filename:null}));case i/* .START_DELETE_IMPORT_DATA_FILE */.AI:return l(e,t.level,(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload[t.level]),{},{isDeleting:!0}));case i/* .SUCCESS_DELETE_IMPORT_DATA_FILE */.Wi:return l(e,t.level,(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},t.data.upload[t.level]),{},{isUploaded:!1,isDeleting:!1,hasError:!1,errorMsg:null,filename:null}));case i/* .ERROR_DELETE_IMPORT_DATA_FILE */.sW:return l(e,t.level,(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.upload[t.level]),{},{isUploaded:!1,isDeleting:!1,hasError:!0,errorMsg:t.error.message}));case i/* .SET_IMPORT_LOG */.ao:return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{done:(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.done),{},{logs:t.data})});case i/* .ERROR_FETCH_IMPORT_LOG */.$L:return(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e),{},{done:(0,o/* ["default"] */.Z)((0,o/* ["default"] */.Z)({},e.done),{},{logs:{fetchError:t.error}})});case i/* .RESET_STATE */.so:return(0,o/* ["default"] */.Z)({},a);default:return e}};
/***/},
/***/43802:
/***/(e,t,r)=>{"use strict";r.r(t),
/* harmony export */r.d(t,{
/* harmony export */getLogsBySeverity:()=>/* binding */l
/* harmony export */});
/* harmony import */var n=r(11092),o=r(25463),s=r(20832),i=r(90631),a=r(97769),l=(0,s/* .composeFetchAction */.GI)(null,(0,n/* ["default"] */.Z)().mark((function e(t){return(0,n/* ["default"] */.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(t){e.next=2;break}return e.abrupt("return");case 2:return e.next=4,(0,i.fetchFromAPI)({path:(0,a/* .buildJobEndpointUrl */.D)(t,["logs"])});case 4:return e.abrupt("return",e.sent);case 5:case"end":return e.stop()}}),e)})),o/* .SET_IMPORT_LOG */.ao,o/* .ERROR_FETCH_IMPORT_LOG */.$L);
/* harmony import */}
/***/,
/***/50330:
/***/(e,t,r)=>{"use strict";r.r(t),
/* harmony export */r.d(t,{
/* harmony export */isFetching:()=>/* binding */l
/* harmony export */,getJobId:()=>/* binding */c
/* harmony export */,getFetchError:()=>/* binding */u
/* harmony export */,getStepData:()=>/* binding */p
/* harmony export */,getNavigationSteps:()=>/* binding */d
/* harmony export */,isCompleteStep:()=>/* binding */f
/* harmony export */,isReadyToStart:()=>/* binding */m
/* harmony export */,getUploadedLevelKeys:()=>/* binding */v
/* harmony export */,getSuccessResults:()=>/* binding */h
/* harmony export */,getLogsBySeverity:()=>/* binding */g
/* harmony export */,getLogsFetchError:()=>/* binding */y
/* harmony export */});
/* harmony import */var n=r(50189),o=r(92819),s=r(47854),i=r(35345),a=["course","lesson","question"],l=function(e){return e.isFetching},c=function(e){return e.jobId},u=function(e){return e.fetchError},p=function(e,t){return e[t]},d=function(e){var t=e.completedSteps,r=s/* .steps.map */.S.map((function(e){return(0,n/* ["default"] */.Z)((0,n/* ["default"] */.Z)({},e),{},{isComplete:t.includes(e.key),isNext:!1})}));return(r.find((function(e){return!e.isComplete}))||r[0]).isNext=!0,r},f=function(e,t){return e.completedSteps.includes(t)},m=function(e){var t=i/* .levels.map */.g.map((function(t){var r=t.key;return e.upload[r]})),r=t.some((function(e){return e.isUploaded})),n=t.some((function(e){return e.isUploading||e.isDeleting}));return r&&!n},v=function(e){var t=e.upload;return i/* .levels.filter */.g.filter((function(e){var r=e.key;return t[r].isUploaded})).map((function(e){return e.key}))},h=function(e){var t=e.done;return a.map((function(e){return{key:e,count:(0,o.get)(t,["results",e,"success"],0)+(0,o.get)(t,["results",e,"warning"],0)}})).filter((function(e){return e.count>0}))},g=function(e){var t=e.done,r=e.upload,s=(0,o.get)(t,"logs.items",[]).map((function(e){return(0,n/* ["default"] */.Z)((0,n/* ["default"] */.Z)({},e),{},{filename:(0,o.get)(r,e.type+"s.filename","")})}));return(0,o.groupBy)(s,"severity")},y=function(e){var t=e.done;return(0,o.get)(t,"logs.fetchError",!1)};
/* harmony import */}
/***/,
/***/21051:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */w:()=>/* binding */c
/* harmony export */});
/* harmony import */var n=r(69307),o=r(65736),s=r(55609),i=r(74849),a=r(79801),l=r(43011),__=o.__,c=function(e){var t=e.restartImporter,r=e.successResults,o=void 0===r?[]:r,c=e.logs,u=void 0===c?{}:c,p=e.isFetching,d=void 0!==p&&p,f=e.fetchError,m=void 0!==f&&f,v=e.retry,h=(0,n.createElement)(n.Fragment,null,u.error&&u.error.length>0&&(0,n.createElement)("section",{className:"sensei-data-port-step"},(0,n.createElement)(l/* .Section */.$,{className:"sensei-data-port-step__body"},(0,n.createElement)("h2",null,__("Failed","sensei-lms")),(0,n.createElement)("p",{className:"sensei-import-done__section-description"},__("The following content was not imported. Please make the necessary corrections to the import file and try again.","sensei-lms")),(0,n.createElement)(i/* .ImportLog */._,{items:u.error,type:"error"}))),u.notice&&u.notice.length>0&&(0,n.createElement)("section",{className:"sensei-data-port-step"},(0,n.createElement)(l/* .Section */.$,{className:"sensei-data-port-step__body"},(0,n.createElement)("h2",null,__("Partial","sensei-lms")),(0,n.createElement)("p",{className:"sensei-import-done__section-description"},__("The following content was partially imported. The import process encountered some issues that you can resolve manually by clicking the link and making the necessary adjustments.","sensei-lms")),(0,n.createElement)(i/* .ImportLog */._,{items:u.notice,type:"warning"}))));return d?h=(0,n.createElement)("div",{className:"sensei-import-done__log-fetching"},(0,n.createElement)(s.Spinner,null)," ",__("Fetching log details…","sensei-lms")):m&&(h=(0,n.createElement)(s.Notice,{status:"error",isDismissible:!1},__("Failed to load import log.","sensei-lms")," ",m.message,(0,n.createElement)(s.Button,{onClick:v,isLink:!0,isSmall:!0},__("Retry","sensei-lms")))),(0,n.createElement)(n.Fragment,null,(0,n.createElement)("section",{className:"sensei-data-port-step"},(0,n.createElement)(l/* .Section */.$,{className:"sensei-data-port-step__body"},(0,n.createElement)("h2",null,__("Completed","sensei-lms")),o.length>0?(0,n.createElement)(n.Fragment,null,(0,n.createElement)("p",{className:"sensei-import-done__section-description"},__("The following content was imported:","sensei-lms")),(0,n.createElement)(a/* ["default"] */.Z,{successResults:o})):(0,n.createElement)("p",{className:"sensei-import-done__section-description"},__("No content was imported.","sensei-lms")),(0,n.createElement)("div",{className:"sensei-data-port-step__footer"},(0,n.createElement)(s.Button,{isPrimary:!0,onClick:t},__("Import More Content","sensei-lms"))))),h)};
/* harmony import */}
/***/,
/***/74849:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */_:()=>/* binding */a
/* harmony export */});
/* harmony import */var n=r(69307),o=r(92819),s=r(65736),i=r(70761),__=s.__,a=function(e){var t=e.items,r=e.type;return(0,n.createElement)("div",{className:"sensei-import-done__log-data"},(0,n.createElement)("table",{className:"sensei-data-table"},(0,n.createElement)("thead",null,(0,n.createElement)("tr",null,"error"===r&&(0,n.createElement)("th",null,__("File","sensei-lms")),(0,n.createElement)("th",null,__("Title","sensei-lms")),(0,n.createElement)("th",null,__("Line #","sensei-lms")),(0,n.createElement)("th",null,i/* .logTypeLabels */.g[r]))),(0,n.createElement)("tbody",null,t.map((function(e){return(0,n.createElement)("tr",{key:(0,o.kebabCase)(Object.entries(e).join(""))},"error"===r&&(0,n.createElement)("td",null,e.filename),(0,n.createElement)("td",null,(t=e.post.title,(s=e.post.edit_link)?(0,n.createElement)("a",{href:s,target:"_blank",rel:"noreferrer"},t):t)),(0,n.createElement)("td",null,e.line),(0,n.createElement)("td",null,e.message));var t,s})))))};
/* harmony import */}
/***/,
/***/79801:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>s
/* harmony export */});
/* harmony import */var n=r(69307),o=r(65736),_n=o._n;
/* harmony import */
/* harmony default export */const s=function(e){var t=e.successResults;return(0,n.createElement)("ul",{className:"sensei-import-bullet-list"},t.map((function(e){var t=e.key,r=e.count;return(0,n.createElement)("li",{key:t},r," ",function(e){var t=e.key,r=e.count;return{course:_n("course","courses",r,"sensei-lms"),lesson:_n("lesson","lessons",r,"sensei-lms"),question:_n("question","questions",r,"sensei-lms")}[t]}({key:t,count:r}))})))};
/***/},
/***/98740:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>i
/* harmony export */});
/* harmony import */var n=r(9818),o=r(94333),s=r(21051);
/* harmony import */
/**
 * WordPress dependencies
 */
/**
 * Internal dependencies
 */
/* harmony default export */const i=(0,o.compose)((0,n.withSelect)((function(e){var t=e("sensei/import"),r=t.getJobId();return{successResults:t.getSuccessResults(),logs:t.getLogsBySeverity(r),isFetching:t.isResolving("getLogsBySeverity",[r]),fetchError:t.getLogsFetchError()}})),(0,n.withDispatch)((function(e){var t=e("sensei/import"),r=t.restartImporter,n=t.invalidateResolutionForStoreSelector;return{restartImporter:r,retry:function(){return n("getLogsBySeverity")}}})))(s/* .DonePage */.w);
/***/},
/***/97769:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */D:()=>/* binding */s
/* harmony export */});
/* harmony import */var n=r(90228),o=r(25463),s=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,r=[].concat((0,n/* ["default"] */.Z)(e?[e]:[]),(0,n/* ["default"] */.Z)(t||[])).join("/");return o/* .API_BASE_PATH */.Lt+r};
/* harmony import */}
/***/,
/***/45879:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */A:()=>/* binding */a
/* harmony export */});
/* harmony import */var n=r(69307),o=r(65736),s=r(55292),i=r(43011),__=o.__,a=function(e){var t=e.state.percentage;return(0,s/* ["default"] */.Z)(),(0,n.createElement)("section",{className:"sensei-data-port-step sensei-import-progress-page"},(0,n.createElement)("header",{className:"sensei-data-port-step__header"},(0,n.createElement)(i.H,null,__("Importing","sensei-lms")),(0,n.createElement)("p",null,__("Your content is now being imported…","sensei-lms"))),(0,n.createElement)(i/* .Section */.$,{className:"sensei-data-port-step__body",component:"section"},(0,n.createElement)("p",null,(0,n.createElement)("progress",{className:"sensei-data-port__progressbar sensei-import-progress-page__progress",max:"100",value:t}))))};
/* harmony import */}
/***/,
/***/79357:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>i
/* harmony export */});
/* harmony import */var n=r(9818),o=r(94333),s=r(45879);
/* harmony import */
/**
 * WordPress dependencies
 */
/**
 * Internal dependencies
 */
/* harmony default export */const i=(0,o.compose)((0,n.withSelect)((function(e){return{state:e("sensei/import").getStepData("progress")}})))(s/* .ImportProgressPage */.A);
/***/},
/***/55292:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>s
/* harmony export */});
/* harmony import */var n=r(69307),o=r(9818);
/* harmony import */
/* harmony default export */const s=function(){var e=(0,o.useDispatch)("sensei/import").pollJobProgress,t=(0,o.useSelect)((function(e){return e("sensei/import").getJobId()}));(0,n.useEffect)((function(){e(t)}),[e,t])};
/***/},
/***/35345:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */g:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(65736),__=n.__,o=[{key:"courses",description:__("Courses CSV File","sensei-lms")},{key:"lessons",description:__("Lessons CSV File","sensei-lms")},{key:"questions",description:__("Questions CSV File","sensei-lms")}];
/* harmony import */}
/***/,
/***/47854:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */S:()=>/* binding */l
/* harmony export */});
/* harmony import */var n=r(69307),o=r(65736),s=r(71793),i=r(79357),a=r(98740),__=o.__,l=[{key:"upload",container:(0,n.createElement)(s/* ["default"] */.Z,null),label:__("Upload CSV Files","sensei-lms")},{key:"progress",container:(0,n.createElement)(i/* ["default"] */.Z,null),label:__("Import","sensei-lms")},{key:"complete",container:(0,n.createElement)(a/* ["default"] */.Z,null),label:__("Done","sensei-lms")}];
/* harmony import */}
/***/,
/***/31567:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>i
/* harmony export */});
/* harmony import */var n=r(9818),o=r(94333),s=r(56565);
/* harmony import */
/**
 * WordPress dependencies
 */
/**
 * Internal dependencies
 */
/* harmony default export */const i=(0,o.compose)((0,n.withSelect)((function(e){var t=e("sensei/import");return{jobId:t.getJobId(),state:t.getStepData("upload")}})),(0,n.withDispatch)((function(e){var t=e("sensei/import");return{deleteLevelFile:t.deleteLevelFile,uploadFileForLevel:t.uploadFileForLevel,throwEarlyUploadError:t.throwEarlyUploadError}})))(s/* .UploadLevels */.B);
/***/},
/***/56565:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */B:()=>/* binding */c
/* harmony export */});
/* harmony import */var n=r(69307),o=r(55609),s=r(8598),i=r(65736),a=r(29822),l=r(35345),__=i.__,c=function(e){var t=e.jobId,r=e.state,i=e.uploadFileForLevel,c=e.throwEarlyUploadError,u=e.deleteLevelFile;return(0,n.createElement)("ol",null,l/* .levels.map */.g.map((function(e){var l,p=r[e.key],d=function(e){return e.hasError?(0,n.createElement)(a/* .Notice */.q,{message:e.errorMsg,isError:!0}):e.isUploaded?(0,n.createElement)(a/* .Notice */.q,{message:e.filename}):void 0}(p);
/* eslint-disable jsx-a11y/label-has-for */
return p.isDeleting?l=(0,n.createElement)("div",{className:"sensei-upload-file-line__delete-button-wrapper"},(0,n.createElement)(o.Spinner,null)):p.isUploaded&&(l=(0,n.createElement)("div",{className:"sensei-upload-file-line__delete-button-wrapper"},(0,n.createElement)(o.Button,{icon:s/* ["default"] */.Z,label:__("Delete File","sensei-lms"),onClick:function(){return u(t,e.key)},disabled:p.isDeleting}))),(0,n.createElement)("li",{key:e.key,className:"sensei-upload-file-line sensei-data-port-step__line"},(0,n.createElement)("label",{className:"sensei-upload-file-line__description",htmlFor:"sensei-upload-file-line-".concat(e.key)},e.description),(0,n.createElement)(o.FormFileUpload,{key:p.isUploading,isSecondary:!0,id:"sensei-upload-file-line-".concat(e.key),accept:[".csv",".txt"],disabled:p.isUploading||p.isDeleting,onChange:function(r){return function(e,t,r,n,o){if(!(t.length<1)){var s=t[0];if(["csv","txt"].includes(s.name.split(".").pop())){var i=new FormData;i.append("file",s),n(e,r,i)}else o(r,__("Only CSV files are supported.","sensei-lms"))}}(t,r.target.files,e.key,i,c)}},p.isUploading?__("Uploading…","sensei-lms"):__("Upload","sensei-lms")),(d||l)&&(0,n.createElement)("div",{className:"sensei-upload-file-line__info"},d,l));
/* eslint-enable */})))};
/* harmony import */}
/***/,
/***/71793:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>i
/* harmony export */});
/* harmony import */var n=r(9818),o=r(94333),s=r(71247);
/* harmony import */
/**
 * WordPress dependencies
 */
/**
 * Internal dependencies
 */
/* harmony default export */const i=(0,o.compose)((0,n.withSelect)((function(e){var t=e("sensei/import");return{state:t.getStepData("upload"),isReady:t.isReadyToStart()}})),(0,n.withDispatch)((function(e,t,r){var n=r.select,o=e("sensei/import").submitStartImport;return{submitStartImport:function(){o(n("sensei/import").getJobId());
// Log continue to import from uploaded files.
var e=n("sensei/import").getUploadedLevelKeys().join(",");window.sensei_log_event("import_continue_click",{type:e})}}})))(s/* .UploadPage */.b);
/***/},
/***/71247:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */b:()=>/* binding */u
/* harmony export */});
/* harmony import */var n=r(69307),o=r(65736),s=r(55609),i=r(31567),a=r(29822),l=r(22866),c=r(43011),__=o.__,u=function(e){var t=e.state,r=e.isReady,o=e.submitStartImport,u=t.isSubmitting,p=t.errorMsg;return(0,n.createElement)("section",{className:"sensei-data-port-step sensei-upload-page"},(0,n.createElement)("header",{className:"sensei-data-port-step__header"},(0,n.createElement)(c.H,null,__("Import content from a CSV file","sensei-lms")),(0,n.createElement)("p",null,(0,l/* .formatString */.U)(__("This tool enables you to import courses, lessons, and questions from a CSV file. Please review the {{link}}documentation{{/link}} to learn more about the expected file structure.","sensei-lms"),{link:
// eslint-disable-next-line jsx-a11y/anchor-has-content
(0,n.createElement)("a",{className:"link__color-secondary",href:"https://senseilms.com/documentation/import/",target:"_blank",type:"external",rel:"noopener noreferrer"})}))),(0,n.createElement)(c/* .Section */.$,{className:"sensei-data-port-step__body",component:"section"},(0,n.createElement)("p",null,__("Choose one or more CSV files to upload from your computer.","sensei-lms")),(0,n.createElement)(i/* ["default"] */.Z,null),(0,n.createElement)("div",{className:"sensei-data-port-step__footer"},null!==p&&(0,n.createElement)(a/* .Notice */.q,{message:p,isError:!0}),(0,n.createElement)(s.Button,{isPrimary:!0,className:"continue-button",disabled:!r||u,onClick:o},__("Continue","sensei-lms")))))};
/* harmony import */}
/***/,
/***/29822:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */q:()=>/* binding */a
/* harmony export */});
/* harmony import */var n=r(69307),o=r(60042),s=r.n(o),i=r(55609),a=function(e){var t=e.message,r=e.isError,o=s()({"sensei-data-port-notice__message":!0,error:r});return(0,n.createElement)("div",{className:"sensei-data-port-notice"},r&&(0,n.createElement)(i.Dashicon,{className:"sensei-data-port-notice__icon error",icon:"warning"}),(0,n.createElement)("span",{className:o},t))};
/* harmony import */}
/***/,
/***/17214:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */K:()=>/* binding */i
/* harmony export */});
/* harmony import */var n=r(69307),o=r(60042),s=r.n(o),i=function(e){var t=e.steps;return(0,n.createElement)("ol",{className:"sensei-data-port-steps"},t.map((function(e){var t=s()({active:e.isNext,done:e.isComplete});return(0,n.createElement)("li",{key:e.key,className:t},e.label)})))};
/* harmony import */}
/***/,
/***/49373:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */I:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(69307);
/* harmony import */
/**
 * WordPress dependencies
 */
/**
 * Use Sensei color theme.
 *
 * Requires enqueueing the sensei-wp-components stylesheet.
 */
function o(){(0,n.useLayoutEffect)((function(){return document.body.classList.add("sensei-color"),function(){return document.body.classList.remove("sensei-color")}}))}
/***/},
/***/43011:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */H:()=>/* binding */a
/* harmony export */,$:()=>/* binding */l
/* harmony export */});
/* harmony import */var n=r(53782),o=r(69307),s=["component","children"],i=(0,o.createContext)(2);
/* harmony import */
/**
 * These components are used to frame out the page content for accessible heading hierarchy. Instead of defining fixed heading levels
 * (`h2`, `h3`, …) you can use `<H />` to create "section headings", which look to the parent `<Section />`s for the appropriate
 * heading level.
 *
 * @param {Object} props -
 * @return {Object} -
 */
function a(e){return(0,o.createElement)(i.Consumer,null,(function(t){var r="h"+Math.min(t,6);return(0,o.createElement)(r,e)}))}
/**
 * The section wrapper, used to indicate a sub-section (and change the header level context).
 *
 * @param {Object} props
 * @param {string} props.component
 * @param {Node}   props.children
 * @return {Object} -
 */function l(e){var t=e.component,r=e.children,a=(0,n/* ["default"] */.Z)(e,s),l=t||"div";return(0,o.createElement)(i.Consumer,null,(function(e){return(0,o.createElement)(i.Provider,{value:e+1},!1===t?r:(0,o.createElement)(l,a,r))}))}
/***/},
/***/16400:
/***/(e,t,r)=>{"use strict";
/* unused harmony export preloadedDataUsedOnceMiddleware */
/* harmony import */var n,o=r(96483),s=r(86989);
/* harmony import */r.n(s)().use((n={},function(e,t){return"string"!=typeof e.path||"GET"!==e.method&&e.method||(n[e.path]?e.path=(0,o.addQueryArgs)(e.path,{__skip_preload:1}):n[e.path]=!0),t(e)}))}
/***/,
/***/20832:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */GI:()=>/* binding */o
/* harmony export */});
/* unused harmony exports createReducerFromActionMap, createStore */
/* harmony import */var n=r(11092),o=(r(9818),function(e,t,r,o){
return(0,n/* ["default"] */.Z)().mark((function s(){var i,a=arguments;return(0,n/* ["default"] */.Z)().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:if(!e){n.next=3;break}return n.next=3,{type:e};case 3:return n.prev=3,n.delegateYield(t.apply(void 0,a),"t0",5);case 5:return i=n.t0,n.next=8,{type:r,data:i};case 8:n.next=14;break;case 10:return n.prev=10,n.t1=n.catch(3),n.next=14,{type:o,error:n.t1};case 14:case"end":return n.stop()}}),s,null,[[3,10]])}))});
/* harmony import */}
/***/,
/***/22866:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */U:()=>/* binding */a
/* harmony export */});
/* unused harmony export formattingComponents */
/* harmony import */var n=r(50189),o=r(69307),s=r(19219),i={em:(0,o.createElement)("em",null),strong:(0,o.createElement)("strong",null),code:(0,o.createElement)("code",null),small:(0,o.createElement)("small",null),sub:(0,o.createElement)("sub",null),sup:(0,o.createElement)("sup",null),br:(0,o.createElement)("br",null),p:(0,o.createElement)("p",null),del:(0,o.createElement)("del",null)},a=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return(0,s/* ["default"] */.Z)({mixedString:e,components:(0,n/* ["default"] */.Z)((0,n/* ["default"] */.Z)({},i),t)})};
/* harmony import */}
/***/,
/***/70761:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */g:()=>/* binding */o
/* harmony export */});
/* unused harmony export postTypeLabels */
/* harmony import */var n=r(65736),__=n.__,o=(__("Courses","sensei-lms"),__("Lessons","sensei-lms"),__("Questions","sensei-lms"),{error:__("Error","sensei-lms"),warning:__("Warning","sensei-lms")});
/* harmony import */}
/***/,
/***/99196:
/***/e=>{"use strict";e.exports=window.React}
/***/,
/***/92819:
/***/e=>{"use strict";e.exports=window.lodash}
/***/,
/***/86989:
/***/e=>{"use strict";e.exports=window.wp.apiFetch}
/***/,
/***/55609:
/***/e=>{"use strict";e.exports=window.wp.components}
/***/,
/***/94333:
/***/e=>{"use strict";e.exports=window.wp.compose}
/***/,
/***/9818:
/***/e=>{"use strict";e.exports=window.wp.data}
/***/,
/***/69307:
/***/e=>{"use strict";e.exports=window.wp.element}
/***/,
/***/65736:
/***/e=>{"use strict";e.exports=window.wp.i18n}
/***/,
/***/70444:
/***/e=>{"use strict";e.exports=window.wp.primitives}
/***/,
/***/96483:
/***/e=>{"use strict";e.exports=window.wp.url}
/***/,
/***/1793:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/66470:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(e){if(Array.isArray(e))return e}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/58138:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(1793);function o(e){if(Array.isArray(e))return(0,n/* ["default"] */.Z)(e)}
/***/},
/***/64649:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/55181:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/22446:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(e,t){var r=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=r){var n,o,s=[],_n=!0,i=!1;try{for(r=r.call(e);!(_n=(n=r.next()).done)&&(s.push(n.value),!t||s.length!==t);_n=!0);}catch(e){i=!0,o=e}finally{try{_n||null==r.return||r.return()}finally{if(i)throw o}}return s}}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/53764:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/83314:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/50189:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */s
/* harmony export */});
/* harmony import */var n=r(64649);function o(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function s(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?o(Object(r),!0).forEach((function(t){(0,n/* ["default"] */.Z)(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):o(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}
/***/},
/***/53782:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(30808);function o(e,t){if(null==e)return{};var r,o,s=(0,n/* ["default"] */.Z)(e,t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);for(o=0;o<i.length;o++)r=i[o],t.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(e,r)&&(s[r]=e[r])}return s}
/***/},
/***/30808:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(e,t){if(null==e)return{};var r,n,o={},s=Object.keys(e);for(n=0;n<s.length;n++)r=s[n],t.indexOf(r)>=0||(o[r]=e[r]);return o}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/11092:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(33940);function o(){/*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */
o=function(){return e};var e={},t=Object.prototype,r=t.hasOwnProperty,s=Object.defineProperty||function(e,t,r){e[t]=r.value},i="function"==typeof Symbol?Symbol:{},a=i.iterator||"@@iterator",l=i.asyncIterator||"@@asyncIterator",c=i.toStringTag||"@@toStringTag";function u(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{u({},"")}catch(e){u=function(e,t,r){return e[t]=r}}function p(e,t,r,n){var o=t&&t.prototype instanceof m?t:m,i=Object.create(o.prototype),a=new k(n||[]);return s(i,"_invoke",{value:S(e,r,a)}),i}function d(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}e.wrap=p;var f={};function m(){}function v(){}function h(){}var g={};u(g,a,(function(){return this}));var y=Object.getPrototypeOf,E=y&&y(y(L([])));E&&E!==t&&r.call(E,a)&&(g=E);var b=h.prototype=m.prototype=Object.create(g);function w(e){["next","throw","return"].forEach((function(t){u(e,t,(function(e){return this._invoke(t,e)}))}))}function _(e,t){function o(s,i,a,l){var c=d(e[s],e,i);if("throw"!==c.type){var u=c.arg,p=u.value;return p&&"object"==(0,n/* ["default"] */.Z)(p)&&r.call(p,"__await")?t.resolve(p.__await).then((function(e){o("next",e,a,l)}),(function(e){o("throw",e,a,l)})):t.resolve(p).then((function(e){u.value=e,a(u)}),(function(e){return o("throw",e,a,l)}))}l(c.arg)}var i;s(this,"_invoke",{value:function(e,r){function n(){return new t((function(t,n){o(e,r,t,n)}))}return i=i?i.then(n,n):n()}})}function S(e,t,r){var n="suspendedStart";return function(o,s){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===o)throw s;return T()}for(r.method=o,r.arg=s;;){var i=r.delegate;if(i){var a=Z(i,r);if(a){if(a===f)continue;return a}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if("suspendedStart"===n)throw n="completed",r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n="executing";var l=d(e,t,r);if("normal"===l.type){if(n=r.done?"completed":"suspendedYield",l.arg===f)continue;return{value:l.arg,done:r.done}}"throw"===l.type&&(n="completed",r.method="throw",r.arg=l.arg)}}}function Z(e,t){var r=e.iterator[t.method];if(void 0===r){if(t.delegate=null,"throw"===t.method){if(e.iterator.return&&(t.method="return",t.arg=void 0,Z(e,t),"throw"===t.method))return f;t.method="throw",t.arg=new TypeError("The iterator does not provide a 'throw' method")}return f}var n=d(r,e.iterator,t.arg);if("throw"===n.type)return t.method="throw",t.arg=n.arg,t.delegate=null,f;var o=n.arg;return o?o.done?(t[e.resultName]=o.value,t.next=e.nextLoc,"return"!==t.method&&(t.method="next",t.arg=void 0),t.delegate=null,f):o:(t.method="throw",t.arg=new TypeError("iterator result is not an object"),t.delegate=null,f)}function x(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function O(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function k(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(x,this),this.reset(!0)}function L(e){if(e){var t=e[a];if(t)return t.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var n=-1,o=function t(){for(;++n<e.length;)if(r.call(e,n))return t.value=e[n],t.done=!1,t;return t.value=void 0,t.done=!0,t};return o.next=o}}return{next:T}}function T(){return{value:void 0,done:!0}}return v.prototype=h,s(b,"constructor",{value:h,configurable:!0}),s(h,"constructor",{value:v,configurable:!0}),v.displayName=u(h,c,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===v||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,h):(e.__proto__=h,u(e,c,"GeneratorFunction")),e.prototype=Object.create(b),e},e.awrap=function(e){return{__await:e}},w(_.prototype),u(_.prototype,l,(function(){return this})),e.AsyncIterator=_,e.async=function(t,r,n,o,s){void 0===s&&(s=Promise);var i=new _(p(t,r,n,o),s);return e.isGeneratorFunction(r)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},w(b),u(b,c,"Generator"),u(b,a,(function(){return this})),u(b,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},e.values=L,k.prototype={constructor:k,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(O),!e)for(var t in this)"t"===t.charAt(0)&&r.call(this,t)&&!isNaN(+t.slice(1))&&(this[t]=void 0)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var t=this;function n(r,n){return i.type="throw",i.arg=e,t.next=r,n&&(t.method="next",t.arg=void 0),!!n}for(var o=this.tryEntries.length-1;o>=0;--o){var s=this.tryEntries[o],i=s.completion;if("root"===s.tryLoc)return n("end");if(s.tryLoc<=this.prev){var a=r.call(s,"catchLoc"),l=r.call(s,"finallyLoc");if(a&&l){if(this.prev<s.catchLoc)return n(s.catchLoc,!0);if(this.prev<s.finallyLoc)return n(s.finallyLoc)}else if(a){if(this.prev<s.catchLoc)return n(s.catchLoc,!0)}else{if(!l)throw new Error("try statement without catch or finally");if(this.prev<s.finallyLoc)return n(s.finallyLoc)}}}},abrupt:function(e,t){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var s=o;break}}s&&("break"===e||"continue"===e)&&s.tryLoc<=t&&t<=s.finallyLoc&&(s=null);var i=s?s.completion:{};return i.type=e,i.arg=t,s?(this.method="next",this.next=s.finallyLoc,f):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),f},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),O(r),f}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;O(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,t,r){return this.delegate={iterator:L(e),resultName:t,nextLoc:r},"next"===this.method&&(this.arg=void 0),f}},e
/***/}},
/***/66886:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */a
/* harmony export */});
/* harmony import */var n=r(66470),o=r(22446),s=r(64013),i=r(53764);
/* harmony import */function a(e,t){return(0,n/* ["default"] */.Z)(e)||(0,o/* ["default"] */.Z)(e,t)||(0,s/* ["default"] */.Z)(e,t)||(0,i/* ["default"] */.Z)()}
/***/},
/***/90228:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */a
/* harmony export */});
/* harmony import */var n=r(58138),o=r(55181),s=r(64013),i=r(83314);
/* harmony import */function a(e){return(0,n/* ["default"] */.Z)(e)||(0,o/* ["default"] */.Z)(e)||(0,s/* ["default"] */.Z)(e)||(0,i/* ["default"] */.Z)()}
/***/},
/***/33940:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(e){return n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(e)
/***/}r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/64013:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(1793);function o(e,t){if(e){if("string"==typeof e)return(0,n/* ["default"] */.Z)(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(e):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?(0,n/* ["default"] */.Z)(e,t):void 0}}
/***/
/******/}},t={};
/************************************************************************/
/******/ // The module cache
/******/
/******/
/******/ // The require function
/******/function r(n){
/******/ // Check if module is in cache
/******/var o=t[n];
/******/if(void 0!==o)
/******/return o.exports;
/******/
/******/ // Create a new module (and put it into the cache)
/******/var s=t[n]={
/******/ // no module.id needed
/******/ // no module.loaded needed
/******/exports:{}
/******/};
/******/
/******/ // Execute the module function
/******/
/******/
/******/ // Return the exports of the module
/******/return e[n](s,s.exports,r),s.exports;
/******/}
/******/
/************************************************************************/
/******/ /* webpack/runtime/compat get default export */
/******/
/******/ // getDefaultExport function for compatibility with non-harmony modules
/******/r.n=e=>{
/******/var t=e&&e.__esModule?
/******/()=>e.default
/******/:()=>e
/******/;
/******/return r.d(t,{a:t}),t;
/******/},
/******/ // define getter functions for harmony exports
/******/r.d=(e,t)=>{
/******/for(var n in t)
/******/r.o(t,n)&&!r.o(e,n)&&
/******/Object.defineProperty(e,n,{enumerable:!0,get:t[n]})
/******/;
/******/},
/******/r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t)
/******/,
/******/ // define __esModule on exports
/******/r.r=e=>{
/******/"undefined"!=typeof Symbol&&Symbol.toStringTag&&
/******/Object.defineProperty(e,Symbol.toStringTag,{value:"Module"})
/******/,Object.defineProperty(e,"__esModule",{value:!0})}
/******/,
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(()=>{"use strict";
/* harmony import */var e=r(69307),t=r(65736),n=r(9818),o=r(49373),s=r(17214),i=r(51842),a=r(55609),__=(r(16400),t.__);
/* harmony import */
/**
 * Internal dependencies
 */
(0,i/* ["default"] */.Z)();(0,e.render)((0,e.createElement)((function(){var t=(0,n.useSelect)((function(e){var t=e("sensei/import");return{error:t.getFetchError(),navigationSteps:t.getNavigationSteps()}}),[]),r=t.error,i=t.navigationSteps,l=(0,n.useDispatch)("sensei/import").loadCurrentJobState;if((0,e.useLayoutEffect)((function(){l()}),[l]),(0,o/* .useSenseiColorTheme */.I)(),r)return(0,e.createElement)(a.Notice,{status:"error",isDismissible:!1},__("An error has occurred while fetching the data. Please try again later!","sensei-lms"),(0,e.createElement)("br",null),__("Error details:","sensei-lms")," ",r.message);var c=i.find((function(e){return e.isNext}));return(0,e.createElement)("div",{className:"sensei-page-import"},(0,e.createElement)(s/* .DataPortStepper */.K,{steps:i}),c.container)}),null),document.getElementById("sensei-import-page"))})()})
/******/();