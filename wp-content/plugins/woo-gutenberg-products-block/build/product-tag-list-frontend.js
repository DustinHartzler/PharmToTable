(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[68],{115:function(t,e,n){"use strict";n.d(e,"a",(function(){return s})),n(104);var c=n(47);const s=()=>c.m>1},136:function(t,e,n){"use strict";n.d(e,"c",(function(){return l})),n.d(e,"d",(function(){return u})),n.d(e,"b",(function(){return i})),n.d(e,"a",(function(){return b}));var c=n(73),s=n(115),r=n(52),a=n(19);const o=t=>Object(r.a)(t)?JSON.parse(t)||{}:Object(a.a)(t)?t:{},l=t=>{if(!Object(s.a)()||"function"!=typeof c.__experimentalGetSpacingClassesAndStyles)return{style:{}};const e=Object(a.a)(t)?t:{},n=o(e.style);return Object(c.__experimentalGetSpacingClassesAndStyles)({...e,style:n})},u=t=>{const e=Object(a.a)(t)?t:{},n=o(e.style),c=Object(a.a)(n.typography)?n.typography:{};return{style:{fontSize:e.fontSize?`var(--wp--preset--font-size--${e.fontSize})`:c.fontSize,lineHeight:c.lineHeight,fontWeight:c.fontWeight,textTransform:c.textTransform,fontFamily:e.fontFamily}}},i=t=>{if(!Object(s.a)())return{className:"",style:{}};const e=Object(a.a)(t)?t:{},n=o(e.style);return Object(c.__experimentalUseColorProps)({...e,style:n})},b=t=>{if(!Object(s.a)())return{className:"",style:{}};const e=Object(a.a)(t)?t:{},n=o(e.style);return Object(c.__experimentalUseBorderProps)({...e,style:n})}},19:function(t,e,n){"use strict";n.d(e,"a",(function(){return c})),n.d(e,"b",(function(){return s}));const c=t=>!(t=>null===t)(t)&&t instanceof Object&&t.constructor===Object;function s(t,e){return c(t)&&e in t}},355:function(t,e){},404:function(t,e,n){"use strict";n.r(e);var c=n(0),s=n(1),r=n(4),a=n.n(r),o=n(46),l=n(3),u=n(120),i=(n(355),n(136));e.default=Object(u.withProductDataContext)(t=>{const{className:e}=t,{parentClassName:n}=Object(o.useInnerBlockLayoutContext)(),{product:r}=Object(o.useProductDataContext)(),u=Object(i.b)(t),b=Object(i.d)(t);return Object(l.isEmpty)(r.tags)?null:Object(c.createElement)("div",{className:a()(e,u.className,"wc-block-components-product-tag-list",{[n+"__product-tag-list"]:n}),style:{...u.style,...b.style}},Object(s.__)("Tags:","woo-gutenberg-products-block")," ",Object(c.createElement)("ul",null,Object.values(r.tags).map(t=>{let{name:e,link:n,slug:s}=t;return Object(c.createElement)("li",{key:"tag-list-item-"+s},Object(c.createElement)("a",{href:n},e))})))})}}]);