(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[60],{255:function(t,e,n){"use strict";n.d(e,"a",(function(){return o}));var o=function(){return(o=Object.assign||function(t){for(var e,n=1,o=arguments.length;n<o;n++)for(var r in e=arguments[n])Object.prototype.hasOwnProperty.call(e,r)&&(t[r]=e[r]);return t}).apply(this,arguments)};Object.create,Object.create},256:function(t,e,n){"use strict";function o(t){return t.toLowerCase()}n.d(e,"a",(function(){return c}));var r=[/([a-z0-9])([A-Z])/g,/([A-Z])([A-Z][a-z])/g],l=/[^A-Z0-9]+/gi;function c(t,e){void 0===e&&(e={});for(var n=e.splitRegexp,c=void 0===n?r:n,a=e.stripRegexp,s=void 0===a?l:a,u=e.transform,d=void 0===u?o:u,f=e.delimiter,v=void 0===f?" ":f,y=i(i(t,c,"$1\0$2"),s,"\0"),b=0,g=y.length;"\0"===y.charAt(b);)b++;for(;"\0"===y.charAt(g-1);)g--;return y.slice(b,g).split("\0").map(d).join(v)}function i(t,e,n){return e instanceof RegExp?t.replace(e,n):e.reduce((function(t,e){return t.replace(e,n)}),t)}},264:function(t,e,n){"use strict";n.d(e,"a",(function(){return l}));var o=n(255),r=n(256);function l(t,e){return void 0===e&&(e={}),function(t,e){return void 0===e&&(e={}),Object(r.a)(t,Object(o.a)({delimiter:"."},e))}(t,Object(o.a)({delimiter:"-"},e))}},265:function(t,e,n){"use strict";n.d(e,"a",(function(){return d}));var o=n(6),r=n.n(o),l=n(27),c=n(36),i=n(264),a=n(100);function s(){let t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};const e={};return Object(a.getCSSRules)(t,{selector:""}).forEach(t=>{e[t.key]=t.value}),e}function u(t,e){return t&&e?`has-${Object(i.a)(e)}-${t}`:""}const d=t=>{const e=(t=>{const e=Object(l.a)(t)?t:{style:{}};let n=e.style;return Object(c.a)(n)&&(n=JSON.parse(n)||{}),Object(l.a)(n)||(n={}),{...e,style:n}})(t),n=function(t){var e,n,o,c,i,a,d;const{backgroundColor:f,textColor:v,gradient:y,style:b}=t,g=u("background-color",f),p=u("color",v),m=function(t){if(t)return`has-${t}-gradient-background`}(y),h=m||(null==b||null===(e=b.color)||void 0===e?void 0:e.gradient);return{className:r()(p,m,{[g]:!h&&!!g,"has-text-color":v||(null==b||null===(n=b.color)||void 0===n?void 0:n.text),"has-background":f||(null==b||null===(o=b.color)||void 0===o?void 0:o.background)||y||(null==b||null===(c=b.color)||void 0===c?void 0:c.gradient),"has-link-color":Object(l.a)(null==b||null===(i=b.elements)||void 0===i?void 0:i.link)?null==b||null===(a=b.elements)||void 0===a||null===(d=a.link)||void 0===d?void 0:d.color:void 0}),style:s({color:(null==b?void 0:b.color)||{}})}}(e),o=function(t){var e;const n=(null===(e=t.style)||void 0===e?void 0:e.border)||{};return{className:function(t){var e;const{borderColor:n,style:o}=t,l=n?u("border-color",n):"";return r()({"has-border-color":n||(null==o||null===(e=o.border)||void 0===e?void 0:e.color),borderColorClass:l})}(t),style:s({border:n})}}(e),i=function(t){var e;return{className:void 0,style:s({spacing:(null===(e=t.style)||void 0===e?void 0:e.spacing)||{}})}}(e),a=(t=>{const e=Object(l.a)(t.style.typography)?t.style.typography:{},n=Object(c.a)(e.fontFamily)?e.fontFamily:"";return{className:t.fontFamily?`has-${t.fontFamily}-font-family`:n,style:{fontSize:t.fontSize?`var(--wp--preset--font-size--${t.fontSize})`:e.fontSize,fontStyle:e.fontStyle,fontWeight:e.fontWeight,letterSpacing:e.letterSpacing,lineHeight:e.lineHeight,textDecoration:e.textDecoration,textTransform:e.textTransform}}})(e);return{className:r()(a.className,n.className,o.className,i.className),style:{...a.style,...n.style,...o.style,...i.style}}}},272:function(t,e,n){"use strict";n.d(e,"a",(function(){return r})),n.d(e,"b",(function(){return l}));var o=n(27);const r=function(){let t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",e=arguments.length>1?arguments[1]:void 0;return t.includes("is-style-outline")?"outlined":t.includes("is-style-fill")?"contained":e},l=t=>t.some(t=>Array.isArray(t)?l(t):Object(o.a)(t)&&null!==t.key)},394:function(t,e,n){"use strict";n.r(e);var o=n(0),r=n(20),l=n(113),c=n(6),i=n.n(c),a=n(265),s=n(1);const u=Object(s.__)("View my cart","woo-gutenberg-products-block");var d=n(272);e.default=t=>{let{className:e,cartButtonLabel:n,style:c}=t;const s=Object(a.a)({style:c});return r.c?Object(o.createElement)(l.a,{className:i()(e,s.className,"wc-block-mini-cart__footer-cart"),style:s.style,href:r.c,variant:Object(d.a)(e,"outlined")},n||u):null}}}]);