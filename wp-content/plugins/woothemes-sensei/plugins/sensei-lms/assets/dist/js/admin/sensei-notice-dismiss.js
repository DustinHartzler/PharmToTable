/******/(()=>{// webpackBootstrap
/******/"use strict";
/******/var t,e={
/***/47701:
/***/t=>{t.exports=window.wp.domReady;
/***/
/******/}},s={};
/************************************************************************/
/******/ // The module cache
/******/
/******/
/******/ // The require function
/******/function a(t){
/******/ // Check if module is in cache
/******/var o=s[t];
/******/if(void 0!==o)
/******/return o.exports;
/******/
/******/ // Create a new module (and put it into the cache)
/******/var i=s[t]={
/******/ // no module.id needed
/******/ // no module.loaded needed
/******/exports:{}
/******/};
/******/
/******/ // Execute the module function
/******/
/******/
/******/ // Return the exports of the module
/******/return e[t](i,i.exports,a),i.exports;
/******/}
/******/
/************************************************************************/
/******/ /* webpack/runtime/compat get default export */
/******/
/******/ // getDefaultExport function for compatibility with non-harmony modules
/******/a.n=t=>{
/******/var e=t&&t.__esModule?
/******/()=>t.default
/******/:()=>t
/******/;
/******/return a.d(e,{a:e}),e;
/******/},
/******/ // define getter functions for harmony exports
/******/a.d=(t,e)=>{
/******/for(var s in e)
/******/a.o(e,s)&&!a.o(t,s)&&
/******/Object.defineProperty(t,s,{enumerable:!0,get:e[s]})
/******/;
/******/},
/******/a.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e)
/******/,
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
t=a(47701),
/**
 * WordPress dependencies
 */
a.n(t)()((function(){document.body.addEventListener("click",(function(t){var e=t.target.closest(".sensei-notice");if(e&&e.dataset.dismissNonce&&e.dataset.dismissAction&&t.target.classList.contains("notice-dismiss")){var s=new FormData;e.dataset.dismissNotice&&s.append("notice",e.dataset.dismissNotice),s.append("action",e.dataset.dismissAction),s.append("nonce",e.dataset.dismissNonce),fetch(ajaxurl,{method:"POST",body:s})}}))}))})
/******/();