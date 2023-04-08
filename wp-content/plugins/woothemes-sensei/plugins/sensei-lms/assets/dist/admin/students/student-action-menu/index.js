/******/(()=>{// webpackBootstrap
/******/var e={
/***/82857:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>s
/* harmony export */});
/* harmony import */var n=r(69307),o=r(70444);
/* harmony import */
/**
 * WordPress dependencies
 */
const s=(0,n.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,n.createElement)(o.Path,{d:"M13 19h-2v-2h2v2zm0-6h-2v-2h2v2zm0-6h-2V5h2v2z"}));
/* harmony default export */}
//# sourceMappingURL=more-vertical.js.map
/***/,
/***/99854:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>s
/* harmony export */});
/* harmony import */var n=r(69307),o=r(70444);
/* harmony import */
/**
 * WordPress dependencies
 */
const s=(0,n.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,n.createElement)(o.Path,{d:"M13.5 6C10.5 6 8 8.5 8 11.5c0 1.1.3 2.1.9 3l-3.4 3 1 1.1 3.4-2.9c1 .9 2.2 1.4 3.6 1.4 3 0 5.5-2.5 5.5-5.5C19 8.5 16.5 6 13.5 6zm0 9.5c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4z"}));
/* harmony default export */}
//# sourceMappingURL=search.js.map
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
/***/40483:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */});
/* harmony import */var n=r(69307);
/* harmony import */
/**
 * WordPress dependencies
 */
// Solution borrowed from https://gist.github.com/kentcdodds/b36572b6e9227207e6c71fd80e63f3b4.
function o(){var e=(0,n.useRef)(),t=(0,n.useCallback)((function(){return e.current||(e.current=new AbortController),e.current}),[]);return(0,n.useEffect)((function(){return function(){return t().abort()}}),[t]),{getSignal:(0,n.useCallback)((function(){return t().signal}),[t])}}
/***/},
/***/45031:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>p
/* harmony export */});
/* unused harmony export CourseList */
/* harmony import */var n=r(90228),o=r(66886),s=r(69307),i=r(55609),a=r(22629),u=r(65736),c=r(37798),l=r(86938),__=u.__,d=function(){return(0,s.createElement)("li",{className:"sensei-student-modal__course-list--loading"},(0,s.createElement)(i.Spinner,null))},f=function(){return(0,s.createElement)("li",{className:"sensei-student-modal__course-list--empty"},__("No courses found.","sensei-lms"))},h=function(e){var t,r=e.course,n=e.checked,u=void 0!==n&&n,c=e.onChange,l=null==r?void 0:r.id,d=(0,a.decodeEntities)(null==r||null===(t=r.title)||void 0===t?void 0:t.rendered),f=(0,s.useState)(u),h=(0,o/* ["default"] */.Z)(f,2),p=h[0],m=h[1],v=(0,s.useCallback)((function(e){m(e),c({isSelected:e,course:r})}),[r,c]);return(0,s.createElement)("li",{className:"sensei-student-modal__course-list__item",key:l},(0,s.createElement)(i.CheckboxControl,{id:"course-".concat(l),title:d,checked:p,onChange:v}),(0,s.createElement)("label",{htmlFor:"course-".concat(l),title:d},d))};
/* harmony import */
/* harmony default export */const p=function(e){var t=e.searchQuery,r=e.onChange,o=(0,s.useRef)([]),i=(0,s.useCallback)((function(e){var t=e.isSelected,s=e.course;o.current=t?[].concat((0,n/* ["default"] */.Z)(o.current),[s]):o.current.filter((function(e){return e.id!==s.id})),r(o.current)}),[r]),a=(0,l/* ["default"] */.Z)((function(e){var r=e(c.store),n={per_page:100,search:t,filter:"teacher"};return{courses:r.getEntityRecords("postType","course",n)||[],isFetching:!r.hasFinishedResolution("getEntityRecords",["postType","course",n])}}),[t],500),u=a.courses,p=a.isFetching;return(0,s.createElement)(s.Fragment,null,(0,s.createElement)("span",{className:"sensei-student-modal__course-list__header"},__("Your Courses","sensei-lms")),(0,s.createElement)("ul",{className:"sensei-student-modal__course-list"},p&&(0,s.createElement)(d,null),!p&&0===u.length&&(0,s.createElement)(f,null),!p&&0<u.length&&u.map((function(e){return(0,s.createElement)(h,{key:e.id,course:e,onChange:i,checked:o.current.length>0&&o.current.find((function(t){return t.id===e.id}))})}))))};
/***/},
/***/9240:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>v
/* harmony export */});
/* unused harmony export StudentModal */
/* harmony import */var n=r(11092),o=r(27791),s=r(66886),i=r(69307),a=r(55609),u=r(99854),c=r(65736),l=r(81975),d=r(86989),f=r.n(d),h=r(45031),p=r(91442),m=r(40483),__=c.__,_n=c._n;
/* harmony import */
/* harmony default export */const v=function(e){var t=e.action,r=e.onClose,d=e.students,v=e.studentDisplayName,y=function(e,t,r){var n=(0,l.escapeHTML)(r);return{add:{description:t>1?(0,c.sprintf)(
// Translators: placeholder is the number of selected students.
__("Select the course(s) you would like to add <strong>%d students</strong> to:","sensei-lms"),t):(0,c.sprintf)(
// Translators: placeholder is the student's name.
__("Select the course(s) you would like to add <strong>%s</strong> to:","sensei-lms"),n),buttonLabel:__("Add to Course","sensei-lms"),errorMessage:function(e){return _n("Unable to add student. Please try again.","Unable to add students. Please try again.",e.length,"sensei-lms")},sendAction:function(e,t,r){var n=r.signal;return f()({path:"/sensei-internal/v1/course-students/batch",method:"POST",data:{student_ids:e,course_ids:t},signal:n})},isDestructive:!1},remove:{description:t>1?(0,c.sprintf)(
// Translators: placeholder is the number of selected students.
__("Select the course(s) you would like to remove <strong>%d students</strong> from:","sensei-lms"),t):(0,c.sprintf)(
// Translators: placeholder is the student's name.
__("Select the course(s) you would like to remove <strong>%s</strong> from:","sensei-lms"),n),buttonLabel:__("Remove from Course","sensei-lms"),errorMessage:function(e){return _n("Unable to remove student. Please try again.","Unable to remove students. Please try again.",e.length,"sensei-lms")},sendAction:function(e,t,r){var n=r.signal;return f()({path:"/sensei-internal/v1/course-students/batch",method:"DELETE",data:{student_ids:e,course_ids:t},signal:n})},isDestructive:!0},"reset-progress":{
// Translators: placeholder is the number of selected students for plural, student's name for singular.
description:t>1?(0,c.sprintf)(
// Translators: placeholder is the number of selected students.
__("Select the course(s) you would like to reset progress from for <strong>%d students</strong>:","sensei-lms"),t):(0,c.sprintf)(
// Translators: placeholder is the student's name.
__("Select the course(s) you would like to reset progress from for <strong>%s</strong>:","sensei-lms"),n),buttonLabel:__("Reset Progress","sensei-lms"),errorMessage:function(e){return _n("Unable to reset progress for this student. Please try again.","Unable to reset progress for these students. Please try again.",e.length,"sensei-lms")},sendAction:function(e,t,r){var n=r.signal;return f()({path:"/sensei-internal/v1/course-progress/batch",method:"DELETE",data:{student_ids:e,course_ids:t},signal:n})},isDestructive:!0}}[e]}(t,d.length,v),g=y.description,w=y.buttonLabel,b=y.errorMessage,E=y.isDestructive,_=y.sendAction,x=(0,i.useState)([]),Z=(0,s/* ["default"] */.Z)(x,2),S=Z[0],C=Z[1],k=(0,i.useState)(""),L=(0,s/* ["default"] */.Z)(k,2),O=L[0],N=L[1],j=(0,i.useState)(!1),P=(0,s/* ["default"] */.Z)(j,2),A=P[0],D=P[1],F=(0,i.useState)(!1),M=(0,s/* ["default"] */.Z)(F,2),R=M[0],T=M[1],I=(0,m/* ["default"] */.Z)().getSignal,G=(0,i.useCallback)((0,o/* ["default"] */.Z)((0,n/* ["default"] */.Z)().mark((function e(){return(0,n/* ["default"] */.Z)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return D(!0),e.prev=1,e.next=4,_(d,S.map((function(e){return e.id})),{signal:I()});case 4:r(!0),e.next=10;break;case 7:e.prev=7,e.t0=e.catch(1),I().aborted||(T(!0),D(!1));case 10:case"end":return e.stop()}}),e,null,[[1,7]])}))),[_,d,S,r,I]);return(0,i.createElement)(a.Modal,{className:"sensei-student-modal",title:__("Choose Course","sensei-lms"),onRequestClose:function(){return r()}},(0,i.createElement)(i.RawHTML,null,g),(0,i.createElement)(p/* ["default"] */.Z,{iconRight:u/* ["default"] */.Z,onChange:function(e){return N(e)},placeholder:__("Search courses","sensei-lms"),value:O}),(0,i.createElement)(h/* ["default"] */.Z,{searchQuery:O,onChange:function(e){C(e)}}),R&&(0,i.createElement)(a.Notice,{status:"error",isDismissible:!1,className:"sensei-student-modal__notice"},b(d)),(0,i.createElement)("div",{className:"sensei-student-modal__action"},(0,i.createElement)(a.Button,{className:"sensei-student-modal__action",variant:E?"":"primary",onClick:function(){return G()},disabled:A||0===S.length,isDestructive:E},A&&(0,i.createElement)(a.Spinner,null),w)))};
/***/},
/***/91442:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>l
/* harmony export */});
/* harmony import */var n=r(25773),o=r(53782),s=r(69307),i=r(60042),a=r.n(i),u=r(55609),c=["className","id","label","value","help","iconRight","onChange"];
/* harmony import */
/* harmony default export */const l=function(e){var t=e.className,r=e.id,i=e.label,l=e.value,d=e.help,f=e.iconRight,h=e.onChange,p=(0,o/* ["default"] */.Z)(e,c);return(0,s.createElement)(u.BaseControl,{id:r,label:i,help:d},(0,s.createElement)("div",{className:"sensei-input-control"},(0,s.createElement)("input",(0,n/* ["default"] */.Z)({className:a()("sensei-input-control__input",{"sensei-input-control__input--with-icon-right":f},t),type:"text",id:r,value:null===l?"":l,onChange:function(e){return h(e.target.value)}},p)),f&&(0,s.createElement)("span",{className:"sensei-input-control__icon"},(0,s.createElement)(u.Icon,{icon:f}))))};
/***/},
/***/86938:
/***/(e,t,r)=>{"use strict";
/* harmony export */r.d(t,{
/* harmony export */Z:()=>a
/* harmony export */});
/* harmony import */var n=r(66886),o=r(92819),s=r(9818),i=r(69307);
/* harmony import */
/* harmony default export */const a=function(e,t,r){var a=(0,i.useState)(t),u=(0,n/* ["default"] */.Z)(a,2),c=u[0],l=u[1],d=(0,i.useCallback)((0,o.debounce)(l,r),[l,r]);
// eslint-disable-next-line react-hooks/exhaustive-deps -- Using debounce as callback.
return(0,i.useEffect)((function(){d(t);
// eslint-disable-next-line react-hooks/exhaustive-deps -- Dependencies coming from args.
}),t),(0,s.useSelect)(e,c)};
/***/},
/***/92819:
/***/e=>{"use strict";e.exports=window.lodash}
/***/,
/***/86989:
/***/e=>{"use strict";e.exports=window.wp.apiFetch}
/***/,
/***/55609:
/***/e=>{"use strict";e.exports=window.wp.components}
/***/,
/***/37798:
/***/e=>{"use strict";e.exports=window.wp.coreData}
/***/,
/***/9818:
/***/e=>{"use strict";e.exports=window.wp.data}
/***/,
/***/69307:
/***/e=>{"use strict";e.exports=window.wp.element}
/***/,
/***/81975:
/***/e=>{"use strict";e.exports=window.wp.escapeHtml}
/***/,
/***/92694:
/***/e=>{"use strict";e.exports=window.wp.hooks}
/***/,
/***/22629:
/***/e=>{"use strict";e.exports=window.wp.htmlEntities}
/***/,
/***/65736:
/***/e=>{"use strict";e.exports=window.wp.i18n}
/***/,
/***/70444:
/***/e=>{"use strict";e.exports=window.wp.primitives}
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
/***/27791:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(e,t,r,n,o,s,i){try{var a=e[s](i),u=a.value}catch(e){return void r(e)}a.done?t(u):Promise.resolve(u).then(n,o)}function o(e){return function(){var t=this,r=arguments;return new Promise((function(o,s){var i=e.apply(t,r);function a(e){n(i,o,s,a,u,"next",e)}function u(e){n(i,o,s,a,u,"throw",e)}a(void 0)}))}}
/***/r.d(t,{
/* harmony export */Z:()=>/* binding */o
/* harmony export */})},
/***/25773:
/***/(e,t,r)=>{"use strict";
/* harmony export */function n(){return n=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e},n.apply(this,arguments)}
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
o=function(){return e};var e={},t=Object.prototype,r=t.hasOwnProperty,s=Object.defineProperty||function(e,t,r){e[t]=r.value},i="function"==typeof Symbol?Symbol:{},a=i.iterator||"@@iterator",u=i.asyncIterator||"@@asyncIterator",c=i.toStringTag||"@@toStringTag";function l(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{l({},"")}catch(e){l=function(e,t,r){return e[t]=r}}function d(e,t,r,n){var o=t&&t.prototype instanceof p?t:p,i=Object.create(o.prototype),a=new k(n||[]);return s(i,"_invoke",{value:x(e,r,a)}),i}function f(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}e.wrap=d;var h={};function p(){}function m(){}function v(){}var y={};l(y,a,(function(){return this}));var g=Object.getPrototypeOf,w=g&&g(g(L([])));w&&w!==t&&r.call(w,a)&&(y=w);var b=v.prototype=p.prototype=Object.create(y);function E(e){["next","throw","return"].forEach((function(t){l(e,t,(function(e){return this._invoke(t,e)}))}))}function _(e,t){function o(s,i,a,u){var c=f(e[s],e,i);if("throw"!==c.type){var l=c.arg,d=l.value;return d&&"object"==(0,n/* ["default"] */.Z)(d)&&r.call(d,"__await")?t.resolve(d.__await).then((function(e){o("next",e,a,u)}),(function(e){o("throw",e,a,u)})):t.resolve(d).then((function(e){l.value=e,a(l)}),(function(e){return o("throw",e,a,u)}))}u(c.arg)}var i;s(this,"_invoke",{value:function(e,r){function n(){return new t((function(t,n){o(e,r,t,n)}))}return i=i?i.then(n,n):n()}})}function x(e,t,r){var n="suspendedStart";return function(o,s){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===o)throw s;return O()}for(r.method=o,r.arg=s;;){var i=r.delegate;if(i){var a=Z(i,r);if(a){if(a===h)continue;return a}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if("suspendedStart"===n)throw n="completed",r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n="executing";var u=f(e,t,r);if("normal"===u.type){if(n=r.done?"completed":"suspendedYield",u.arg===h)continue;return{value:u.arg,done:r.done}}"throw"===u.type&&(n="completed",r.method="throw",r.arg=u.arg)}}}function Z(e,t){var r=e.iterator[t.method];if(void 0===r){if(t.delegate=null,"throw"===t.method){if(e.iterator.return&&(t.method="return",t.arg=void 0,Z(e,t),"throw"===t.method))return h;t.method="throw",t.arg=new TypeError("The iterator does not provide a 'throw' method")}return h}var n=f(r,e.iterator,t.arg);if("throw"===n.type)return t.method="throw",t.arg=n.arg,t.delegate=null,h;var o=n.arg;return o?o.done?(t[e.resultName]=o.value,t.next=e.nextLoc,"return"!==t.method&&(t.method="next",t.arg=void 0),t.delegate=null,h):o:(t.method="throw",t.arg=new TypeError("iterator result is not an object"),t.delegate=null,h)}function S(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function C(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function k(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(S,this),this.reset(!0)}function L(e){if(e){var t=e[a];if(t)return t.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var n=-1,o=function t(){for(;++n<e.length;)if(r.call(e,n))return t.value=e[n],t.done=!1,t;return t.value=void 0,t.done=!0,t};return o.next=o}}return{next:O}}function O(){return{value:void 0,done:!0}}return m.prototype=v,s(b,"constructor",{value:v,configurable:!0}),s(v,"constructor",{value:m,configurable:!0}),m.displayName=l(v,c,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===m||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,v):(e.__proto__=v,l(e,c,"GeneratorFunction")),e.prototype=Object.create(b),e},e.awrap=function(e){return{__await:e}},E(_.prototype),l(_.prototype,u,(function(){return this})),e.AsyncIterator=_,e.async=function(t,r,n,o,s){void 0===s&&(s=Promise);var i=new _(d(t,r,n,o),s);return e.isGeneratorFunction(r)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},E(b),l(b,c,"Generator"),l(b,a,(function(){return this})),l(b,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},e.values=L,k.prototype={constructor:k,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(C),!e)for(var t in this)"t"===t.charAt(0)&&r.call(this,t)&&!isNaN(+t.slice(1))&&(this[t]=void 0)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var t=this;function n(r,n){return i.type="throw",i.arg=e,t.next=r,n&&(t.method="next",t.arg=void 0),!!n}for(var o=this.tryEntries.length-1;o>=0;--o){var s=this.tryEntries[o],i=s.completion;if("root"===s.tryLoc)return n("end");if(s.tryLoc<=this.prev){var a=r.call(s,"catchLoc"),u=r.call(s,"finallyLoc");if(a&&u){if(this.prev<s.catchLoc)return n(s.catchLoc,!0);if(this.prev<s.finallyLoc)return n(s.finallyLoc)}else if(a){if(this.prev<s.catchLoc)return n(s.catchLoc,!0)}else{if(!u)throw new Error("try statement without catch or finally");if(this.prev<s.finallyLoc)return n(s.finallyLoc)}}}},abrupt:function(e,t){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var s=o;break}}s&&("break"===e||"continue"===e)&&s.tryLoc<=t&&t<=s.finallyLoc&&(s=null);var i=s?s.completion:{};return i.type=e,i.arg=t,s?(this.method="next",this.next=s.finallyLoc,h):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),h},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),C(r),h}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;C(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,t,r){return this.delegate={iterator:L(e),resultName:t,nextLoc:r},"next"===this.method&&(this.arg=void 0),h}},e
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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(()=>{"use strict";
/* unused harmony export StudentActionMenu */
/* harmony import */var e=r(66886),t=r(69307),n=r(55609),o=r(82857),s=r(92694),i=r(65736),a=r(9240),__=i.__,u=function(r){var i=r.studentId,u=r.studentName,c=r.studentDisplayName,l=(0,t.useState)(""),d=(0,e/* ["default"] */.Z)(l,2),f=d[0],h=d[1],p=(0,t.useState)(!1),m=(0,e/* ["default"] */.Z)(p,2),v=m[0],y=m[1],g=function(e){e&&window.location.reload(),y(!1)},w=[{title:__("Add to Course","sensei-lms"),onClick:function(){return E()}},{title:__("Remove from Course","sensei-lms"),onClick:function(){return _()}},{title:__("Reset Progress","sensei-lms"),onClick:function(){return x()}},{title:__("Grading","sensei-lms"),onClick:function(){return window.open("admin.php?page=sensei_grading&view=ungraded&s=".concat(u),"_self")}}],b=(0,s.applyFilters)("senseiStudentActionMenuControls",w,h,y),E=function(){h("add"),y(!0)},_=function(){h("remove"),y(!0)},x=function(){h("reset-progress"),y(!0)},Z=(0,t.createElement)(a/* ["default"] */.Z,{action:f,onClose:g,students:[i],studentDisplayName:c}),S=(0,s.applyFilters)("senseiStudentBulkActionModal",Z,f,g,[i],c);return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(n.DropdownMenu,{icon:o/* ["default"] */.Z,label:__("Select an action","sensei-lms"),controls:b}),v&&S)};
/* harmony import */Array.from(document.getElementsByClassName("student-action-menu")).forEach((function(e){var r,n,o;(0,t.render)((0,t.createElement)(u,{studentId:null==e||null===(r=e.dataset)||void 0===r?void 0:r.userId,studentName:null==e||null===(n=e.dataset)||void 0===n?void 0:n.userName,studentDisplayName:null==e||null===(o=e.dataset)||void 0===o?void 0:o.userDisplayName}),e)}))})()})
/******/();