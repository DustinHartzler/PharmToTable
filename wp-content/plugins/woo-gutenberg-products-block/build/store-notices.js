this.wc=this.wc||{},this.wc.blocks=this.wc.blocks||{},this.wc.blocks["store-notices"]=function(e){function t(t){for(var n,i,s=t[0],l=t[1],u=t[2],p=0,f=[];p<s.length;p++)i=s[p],Object.prototype.hasOwnProperty.call(r,i)&&r[i]&&f.push(r[i][0]),r[i]=0;for(n in l)Object.prototype.hasOwnProperty.call(l,n)&&(e[n]=l[n]);for(a&&a(t);f.length;)f.shift()();return c.push.apply(c,u||[]),o()}function o(){for(var e,t=0;t<c.length;t++){for(var o=c[t],n=!0,s=1;s<o.length;s++){var l=o[s];0!==r[l]&&(n=!1)}n&&(c.splice(t--,1),e=i(i.s=o[0]))}return e}var n={},r={36:0},c=[];function i(t){if(n[t])return n[t].exports;var o=n[t]={i:t,l:!1,exports:{}};return e[t].call(o.exports,o,o.exports,i),o.l=!0,o.exports}i.m=e,i.c=n,i.d=function(e,t,o){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(i.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)i.d(o,n,function(t){return e[t]}.bind(null,n));return o},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="";var s=window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[],l=s.push.bind(s);s.push=t,s=s.slice();for(var u=0;u<s.length;u++)t(s[u]);var a=l;return c.push([459,0]),o()}({0:function(e,t){e.exports=window.wp.element},1:function(e,t){e.exports=window.wp.i18n},11:function(e,t){e.exports=window.wp.primitives},2:function(e,t){e.exports=window.wp.components},247:function(e,t,o){"use strict";var n=o(0),r=o(11);const c=Object(n.createElement)(r.SVG,{xmlns:"http://www.w3.org/2000/SVG",viewBox:"0 0 24 24",fill:"none"},Object(n.createElement)("path",{stroke:"currentColor",strokeWidth:"1.5",fill:"none",d:"M6 3.75h12c.69 0 1.25.56 1.25 1.25v14c0 .69-.56 1.25-1.25 1.25H6c-.69 0-1.25-.56-1.25-1.25V5c0-.69.56-1.25 1.25-1.25z"}),Object(n.createElement)("path",{fill:"currentColor",fillRule:"evenodd",d:"M6.9 7.5A1.1 1.1 0 018 6.4h8a1.1 1.1 0 011.1 1.1v2a1.1 1.1 0 01-1.1 1.1H8a1.1 1.1 0 01-1.1-1.1v-2zm1.2.1v1.8h7.8V7.6H8.1z",clipRule:"evenodd"}),Object(n.createElement)("path",{fill:"currentColor",d:"M8.5 12h1v1h-1v-1zM8.5 14h1v1h-1v-1zM8.5 16h1v1h-1v-1zM11.5 12h1v1h-1v-1zM11.5 14h1v1h-1v-1zM11.5 16h1v1h-1v-1zM14.5 12h1v1h-1v-1zM14.5 14h1v1h-1v-1zM14.5 16h1v1h-1v-1z"}));t.a=c},312:function(e){e.exports=JSON.parse('{"name":"woocommerce/store-notices","version":"1.0.0","title":"Store Notices","description":"Display shopper-facing notifications generated by WooCommerce or extensions.","category":"woocommerce","keywords":["WooCommerce"],"supports":{"multiple":false,"align":["wide","full"]},"attributes":{"align":{"type":"string","default":"wide"}},"textdomain":"woo-gutenberg-products-block","apiVersion":2,"$schema":"https://schemas.wp.org/trunk/block.json"}')},459:function(e,t,o){e.exports=o(523)},460:function(e,t){},5:function(e,t){e.exports=window.wp.blockEditor},523:function(e,t,o){"use strict";o.r(t);var n=o(0),r=o(8),c=o(89),i=o(247),s=o(312),l=o(5),u=o(1),a=o(2);o(460);Object(r.registerBlockType)(s,{icon:{src:Object(n.createElement)(c.a,{icon:i.a,className:"wc-block-editor-components-block-icon"})},attributes:{...s.attributes},edit:()=>{const e=Object(l.useBlockProps)({className:"wc-block-store-notices"});return Object(n.createElement)("div",e,Object(n.createElement)(a.Notice,{status:"info",isDismissible:!1},Object(u.__)("Notices added by WooCommerce or extensions will show up here.","woo-gutenberg-products-block")))},save:()=>null})},8:function(e,t){e.exports=window.wp.blocks}});