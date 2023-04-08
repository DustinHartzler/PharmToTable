/******/(()=>{// webpackBootstrap
/******/"use strict";
/******/var e,t,r,n,o,i,a,c,u,s={
/***/27253:
/***/(e,t,r)=>{
/* harmony export */r.d(t,{
/* harmony export */o:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(69307),o=function(e){var t=e.id,r=e.label,o=e.detailsLabel,i="sensei-exit-reason__".concat(t),a="".concat(i,"-details");return(0,n.createElement)("div",{className:"sensei-exit-survey__item"},(0,n.createElement)("input",{id:i,type:"radio",name:"reason",value:t,className:"sensei-exit-survey__radio"}),(0,n.createElement)("label",{htmlFor:i}," ",r),o&&(0,n.createElement)("div",{className:"sensei-exit-survey__details"},(0,n.createElement)("input",{id:a,name:"details-".concat(t),defaultValue:"",type:"text",placeholder:o})))};
/* harmony import */}
/***/,
/***/39246:
/***/(e,t,r)=>{
/* harmony export */r.d(t,{
/* harmony export */C:()=>/* binding */s
/* harmony export */});
/* harmony import */var n=r(25773),o=r(66886),i=r(69307),a=r(65736),c=r(27253),u=r(3306),__=a.__,s=function(e){var t=e.submit,r=e.skip,a=(0,i.useRef)(null),s=(0,i.useState)(null),l=(0,o/* ["default"] */.Z)(s,2)[1],f=(0,i.useCallback)((function(e){e.preventDefault();var r=new window.FormData(a.current),n=r.get("reason");t({reason:n,details:n&&r.get("details-".concat(n))})}),[t]),d=!1;if(a.current){var h,p=new window.FormData(a.current),v="details-".concat(p.get("reason")),y=(null===(h=a.current)||void 0===h?void 0:h.elements[v])||!1;d=!(!p.get("reason")||y&&""===p.get(v).trim())}return(0,i.createElement)("form",{onChange:function(){var e=new window.FormData(a.current);l(e.values())},className:"sensei-modal sensei-exit-survey",ref:a,onSubmit:f},(0,i.createElement)("div",{className:"sensei-exit-survey__content"},(0,i.createElement)("h2",null,__("Quick Feedback","sensei-lms")),(0,i.createElement)("p",null,__("If you have a moment, please let us know why you are deactivating so that we can work to improve our product.","sensei-lms")),u/* .reasons.map */.l.map((function(e){return(0,i.createElement)(c/* .ExitSurveyFormItem */.o,(0,n/* ["default"] */.Z)({key:e.id},e))}))),(0,i.createElement)("div",{className:"sensei-exit-survey__buttons"},(0,i.createElement)("button",{className:"button button-primary",type:"submit",disabled:!d},__("Submit Feedback","sensei-lms")),(0,i.createElement)("button",{className:"button button-secondary",onClick:r,type:"button"},__("Skip Feedback","sensei-lms"))))};
/* harmony import */}
/***/,
/***/3306:
/***/(e,t,r)=>{
/* harmony export */r.d(t,{
/* harmony export */l:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(65736),__=n.__,o=[{id:"no-longer-need",label:__("I no longer need the plugin","sensei-lms")},{id:"not-working",label:__("The plugin isn't working","sensei-lms"),detailsLabel:__("What isn't working properly?","sensei-lms")},{id:"different-functionality",label:__("I'm looking for different functionality","sensei-lms"),detailsLabel:__("What functionality is missing?","sensei-lms")},{id:"found-better-plugin",label:__("I found a better plugin","sensei-lms"),detailsLabel:__("What's the name of the plugin?","sensei-lms")},{id:"temporary",label:__("It's a temporary deactivation","sensei-lms")},{id:"other",label:"Other",detailsLabel:__("Why are you deactivating?","sensei-lms")}];
/* harmony import */}
/***/,
/***/69307:
/***/e=>{e.exports=window.wp.element;
/***/},
/***/65736:
/***/e=>{e.exports=window.wp.i18n;
/***/},
/***/1793:
/***/(e,t,r)=>{function n(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/66470:
/***/(e,t,r)=>{function n(e){if(Array.isArray(e))return e}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/27791:
/***/(e,t,r)=>{function n(e,t,r,n,o,i,a){try{var c=e[i](a),u=c.value}catch(e){return void r(e)}c.done?t(u):Promise.resolve(u).then(n,o)}function o(e){return function(){var t=this,r=arguments;return new Promise((function(o,i){var a=e.apply(t,r);function c(e){n(a,o,i,c,u,"next",e)}function u(e){n(a,o,i,c,u,"throw",e)}c(void 0)}))}}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */})},
/***/22951:
/***/(e,t,r)=>{function n(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/91976:
/***/(e,t,r)=>{function n(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function o(e,t,r){return t&&n(e.prototype,t),r&&n(e,r),Object.defineProperty(e,"prototype",{writable:!1}),e}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */})},
/***/64649:
/***/(e,t,r)=>{function n(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/25773:
/***/(e,t,r)=>{function n(){return n=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e},n.apply(this,arguments)}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/22446:
/***/(e,t,r)=>{function n(e,t){var r=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=r){var n,o,i=[],_n=!0,a=!1;try{for(r=r.call(e);!(_n=(n=r.next()).done)&&(i.push(n.value),!t||i.length!==t);_n=!0);}catch(e){a=!0,o=e}finally{try{_n||null==r.return||r.return()}finally{if(a)throw o}}return i}}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/53764:
/***/(e,t,r)=>{function n(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}
/***/
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/11092:
/***/(e,t,r)=>{
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(33940);function o(){/*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */
o=function(){return e};var e={},t=Object.prototype,r=t.hasOwnProperty,i=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},c=a.iterator||"@@iterator",u=a.asyncIterator||"@@asyncIterator",s=a.toStringTag||"@@toStringTag";function l(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{l({},"")}catch(e){l=function(e,t,r){return e[t]=r}}function f(e,t,r,n){var o=t&&t.prototype instanceof p?t:p,a=Object.create(o.prototype),c=new k(n||[]);return i(a,"_invoke",{value:_(e,r,c)}),a}function d(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}e.wrap=f;var h={};function p(){}function v(){}function y(){}var m={};l(m,c,(function(){return this}));var w=Object.getPrototypeOf,b=w&&w(w(S([])));b&&b!==t&&r.call(b,c)&&(m=b);var g=y.prototype=p.prototype=Object.create(m);function x(e){["next","throw","return"].forEach((function(t){l(e,t,(function(e){return this._invoke(t,e)}))}))}function E(e,t){function o(i,a,c,u){var s=d(e[i],e,a);if("throw"!==s.type){var l=s.arg,f=l.value;return f&&"object"==(0,n/* ["default"] */.Z)(f)&&r.call(f,"__await")?t.resolve(f.__await).then((function(e){o("next",e,c,u)}),(function(e){o("throw",e,c,u)})):t.resolve(f).then((function(e){l.value=e,c(l)}),(function(e){return o("throw",e,c,u)}))}u(s.arg)}var a;i(this,"_invoke",{value:function(e,r){function n(){return new t((function(t,n){o(e,r,t,n)}))}return a=a?a.then(n,n):n()}})}function _(e,t,r){var n="suspendedStart";return function(o,i){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===o)throw i;return j()}for(r.method=o,r.arg=i;;){var a=r.delegate;if(a){var c=L(a,r);if(c){if(c===h)continue;return c}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if("suspendedStart"===n)throw n="completed",r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n="executing";var u=d(e,t,r);if("normal"===u.type){if(n=r.done?"completed":"suspendedYield",u.arg===h)continue;return{value:u.arg,done:r.done}}"throw"===u.type&&(n="completed",r.method="throw",r.arg=u.arg)}}}function L(e,t){var r=e.iterator[t.method];if(void 0===r){if(t.delegate=null,"throw"===t.method){if(e.iterator.return&&(t.method="return",t.arg=void 0,L(e,t),"throw"===t.method))return h;t.method="throw",t.arg=new TypeError("The iterator does not provide a 'throw' method")}return h}var n=d(r,e.iterator,t.arg);if("throw"===n.type)return t.method="throw",t.arg=n.arg,t.delegate=null,h;var o=n.arg;return o?o.done?(t[e.resultName]=o.value,t.next=e.nextLoc,"return"!==t.method&&(t.method="next",t.arg=void 0),t.delegate=null,h):o:(t.method="throw",t.arg=new TypeError("iterator result is not an object"),t.delegate=null,h)}function Z(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function O(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function k(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(Z,this),this.reset(!0)}function S(e){if(e){var t=e[c];if(t)return t.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var n=-1,o=function t(){for(;++n<e.length;)if(r.call(e,n))return t.value=e[n],t.done=!1,t;return t.value=void 0,t.done=!0,t};return o.next=o}}return{next:j}}function j(){return{value:void 0,done:!0}}return v.prototype=y,i(g,"constructor",{value:y,configurable:!0}),i(y,"constructor",{value:v,configurable:!0}),v.displayName=l(y,s,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===v||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,y):(e.__proto__=y,l(e,s,"GeneratorFunction")),e.prototype=Object.create(g),e},e.awrap=function(e){return{__await:e}},x(E.prototype),l(E.prototype,u,(function(){return this})),e.AsyncIterator=E,e.async=function(t,r,n,o,i){void 0===i&&(i=Promise);var a=new E(f(t,r,n,o),i);return e.isGeneratorFunction(r)?a:a.next().then((function(e){return e.done?e.value:a.next()}))},x(g),l(g,s,"Generator"),l(g,c,(function(){return this})),l(g,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},e.values=S,k.prototype={constructor:k,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(O),!e)for(var t in this)"t"===t.charAt(0)&&r.call(this,t)&&!isNaN(+t.slice(1))&&(this[t]=void 0)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var t=this;function n(r,n){return a.type="throw",a.arg=e,t.next=r,n&&(t.method="next",t.arg=void 0),!!n}for(var o=this.tryEntries.length-1;o>=0;--o){var i=this.tryEntries[o],a=i.completion;if("root"===i.tryLoc)return n("end");if(i.tryLoc<=this.prev){var c=r.call(i,"catchLoc"),u=r.call(i,"finallyLoc");if(c&&u){if(this.prev<i.catchLoc)return n(i.catchLoc,!0);if(this.prev<i.finallyLoc)return n(i.finallyLoc)}else if(c){if(this.prev<i.catchLoc)return n(i.catchLoc,!0)}else{if(!u)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return n(i.finallyLoc)}}}},abrupt:function(e,t){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var i=o;break}}i&&("break"===e||"continue"===e)&&i.tryLoc<=t&&t<=i.finallyLoc&&(i=null);var a=i?i.completion:{};return a.type=e,a.arg=t,i?(this.method="next",this.next=i.finallyLoc,h):this.complete(a)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),h},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),O(r),h}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;O(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,t,r){return this.delegate={iterator:S(e),resultName:t,nextLoc:r},"next"===this.method&&(this.arg=void 0),h}},e
/***/}},
/***/66886:
/***/(e,t,r)=>{
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */c
/* harmony export */});
/* harmony import */var n=r(66470),o=r(22446),i=r(64013),a=r(53764);
/* harmony import */function c(e,t){return(0,n/* ["default"] */.Z)(e)||(0,o/* ["default"] */.Z)(e,t)||(0,i/* ["default"] */.Z)(e,t)||(0,a/* ["default"] */.Z)()}
/***/},
/***/33940:
/***/(e,t,r)=>{function n(e){return n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(e)
/***/}
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */n
/* harmony export */})},
/***/64013:
/***/(e,t,r)=>{
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(1793);function o(e,t){if(e){if("string"==typeof e)return(0,n/* ["default"] */.Z)(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(e):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?(0,n/* ["default"] */.Z)(e,t):void 0}}
/***/
/******/}},l={};
/************************************************************************/
/******/ // The module cache
/******/
/******/
/******/ // The require function
/******/function f(e){
/******/ // Check if module is in cache
/******/var t=l[e];
/******/if(void 0!==t)
/******/return t.exports;
/******/
/******/ // Create a new module (and put it into the cache)
/******/var r=l[e]={
/******/ // no module.id needed
/******/ // no module.loaded needed
/******/exports:{}
/******/};
/******/
/******/ // Execute the module function
/******/
/******/
/******/ // Return the exports of the module
/******/return s[e](r,r.exports,f),r.exports;
/******/}
/******/
/************************************************************************/
/******/ /* webpack/runtime/compat get default export */
/******/
/******/ // getDefaultExport function for compatibility with non-harmony modules
/******/f.n=e=>{
/******/var t=e&&e.__esModule?
/******/()=>e.default
/******/:()=>e
/******/;
/******/return f.d(t,{a:t}),t;
/******/},
/******/ // define getter functions for harmony exports
/******/f.d=(e,t)=>{
/******/for(var r in t)
/******/f.o(t,r)&&!f.o(e,r)&&
/******/Object.defineProperty(e,r,{enumerable:!0,get:t[r]})
/******/;
/******/},
/******/f.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t)
/******/,
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
r=f(11092),n=f(27791),o=f(91976),i=f(22951),a=f(64649),c=f(69307),u=f(39246),
/**
 * WordPress dependencies
 */
/**
 * Internal dependencies
 */
t=(0,o/* ["default"] */.Z)((
/**
   * Exit survey constructor.
   *
   * @param {string} href Link to deactivate plugin.
   */
function e(t){var o=this,s=t.href;(0,i/* ["default"] */.Z)(this,e),(0,a/* ["default"] */.Z)(this,"href",void 0),(0,a/* ["default"] */.Z)(this,"container",void 0),
/**
     * Create and open a modal with an exit survey form.
     *
     */
(0,a/* ["default"] */.Z)(this,"open",(function(){var e=document.querySelector("#sensei-exit-survey");e||((e=document.createElement("div")).setAttribute("id","sensei-exit-survey-modal"),document.body.appendChild(e)),o.container=e,(0,c.render)((0,c.createElement)(u/* .ExitSurveyForm */.C,{submit:o.submitExitSurvey,skip:o.closeAndDeactivate}),e)})),
/**
     * Submit exit survey to AJAX endpoint.
     *
     * @param {Object} data
     */
(0,a/* ["default"] */.Z)(this,"submitExitSurvey",function(){var e=(0,n/* ["default"] */.Z)((0,r/* ["default"] */.Z)().mark((function e(t){var n,i;return(0,r/* ["default"] */.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return(i=new window.FormData).append("action","exit_survey"),i.append("_wpnonce",null===(n=window.sensei_exit_survey)||void 0===n?void 0:n.nonce),i.append("reason",t.reason),i.append("details",t.details),e.next=7,window.fetch(window.ajaxurl,{method:"POST",body:i});case 7:o.closeAndDeactivate();case 8:case"end":return e.stop()}}),e)})));return function(_x){return e.apply(this,arguments)}}()),
/**
     * Close survey modal and continue plugin deactivation.
     */
(0,a/* ["default"] */.Z)(this,"closeAndDeactivate",(function(){o.container.remove(),window.location=o.href})),this.href=s})),[(e=function(e){return document.querySelector('#the-list [data-slug="'.concat(e,'"] span.deactivate a'))})("sensei-lms"),e("sensei-with-woocommerce-paid-courses"),e("woocommerce-com-woocommerce-paid-courses")].filter((function(e){return!!e})).forEach((function(e){e.addEventListener("click",(function(e){e.preventDefault(),new t({href:e.target.href}).open()}))}))})
/******/();