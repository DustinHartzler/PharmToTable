(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[19],{129:function(e,t,c){"use strict";var n=c(5),l=c.n(n),o=c(0),a=c(14),s=c(4),r=c.n(s);c(157),t.a=e=>{let{className:t="",disabled:c=!1,name:n,permalink:s="",rel:i,style:u,onClick:b,...d}=e;const p=r()("wc-block-components-product-name",t);if(c){const e=d;return Object(o.createElement)("span",l()({className:p},e,{dangerouslySetInnerHTML:{__html:Object(a.decodeEntities)(n)}}))}return Object(o.createElement)("a",l()({className:p,href:s,rel:i},d,{dangerouslySetInnerHTML:{__html:Object(a.decodeEntities)(n)},style:u}))}},157:function(e,t){},248:function(e,t,c){"use strict";var n=c(70);let l={headingLevel:{type:"number",default:2},showProductLink:{type:"boolean",default:!0},productId:{type:"number",default:0}};Object(n.b)()&&(l={...l,align:{type:"string"},color:{type:"string"},customColor:{type:"string"},fontSize:{type:"string"},customFontSize:{type:"number"}}),t.a=l},249:function(e,t,c){"use strict";var n=c(0),l=c(4),o=c.n(l),a=c(29),s=c(70),r=c(54),i=c(129),u=c(60),b=(c(300),c(113));const d=e=>{let{children:t,headingLevel:c,elementType:l="h"+c,...o}=e;return Object(n.createElement)(l,o,t)};t.a=Object(r.withProductDataContext)(e=>{const{className:t,headingLevel:c=2,showProductLink:l=!0,align:r}=e,{parentClassName:p}=Object(a.useInnerBlockLayoutContext)(),{product:m}=Object(a.useProductDataContext)(),{dispatchStoreEvent:y}=Object(u.a)(),j=Object(b.a)(e),O=Object(b.b)(e),k=Object(b.c)(e);return m.id?Object(n.createElement)(d,{headingLevel:c,className:o()(t,j.className,"wc-block-components-product-title",{[p+"__product-title"]:p,["wc-block-components-product-title--align-"+r]:r&&Object(s.b)()}),style:Object(s.b)()?{...O.style,...k.style,...j.style}:{}},Object(n.createElement)(i.a,{disabled:!l,name:m.name,permalink:m.permalink,rel:l?"nofollow":"",onClick:()=>{y("product-view-link",{product:m})}})):Object(n.createElement)(d,{headingLevel:c,className:o()(t,j.className,"wc-block-components-product-title",{[p+"__product-title"]:p,["wc-block-components-product-title--align-"+r]:r&&Object(s.b)()}),style:Object(s.b)()?{...O.style,...k.style,...j.style}:{}})})},300:function(e,t){},502:function(e,t,c){"use strict";c.r(t);var n=c(54),l=c(249),o=c(248);t.default=Object(n.withFilteredAttributes)(o.a)(l.a)}}]);