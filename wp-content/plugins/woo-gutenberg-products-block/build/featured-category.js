this.wc=this.wc||{},this.wc.blocks=this.wc.blocks||{},this.wc.blocks["featured-category"]=function(e){function t(t){for(var c,a,l=t[0],s=t[1],i=t[2],d=0,b=[];d<l.length;d++)a=l[d],Object.prototype.hasOwnProperty.call(o,a)&&o[a]&&b.push(o[a][0]),o[a]=0;for(c in s)Object.prototype.hasOwnProperty.call(s,c)&&(e[c]=s[c]);for(u&&u(t);b.length;)b.shift()();return n.push.apply(n,i||[]),r()}function r(){for(var e,t=0;t<n.length;t++){for(var r=n[t],c=!0,l=1;l<r.length;l++){var s=r[l];0!==o[s]&&(c=!1)}c&&(n.splice(t--,1),e=a(a.s=r[0]))}return e}var c={},o={23:0},n=[];function a(t){if(c[t])return c[t].exports;var r=c[t]={i:t,l:!1,exports:{}};return e[t].call(r.exports,r,r.exports,a),r.l=!0,r.exports}a.m=e,a.c=c,a.d=function(e,t,r){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(a.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var c in e)a.d(r,c,function(t){return e[t]}.bind(null,c));return r},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="";var l=window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[],s=l.push.bind(l);l.push=t,l=l.slice();for(var i=0;i<l.length;i++)t(l[i]);var u=s;return n.push([352,0]),r()}({0:function(e,t){e.exports=window.wp.element},1:function(e,t){e.exports=window.wp.i18n},103:function(e,t,r){"use strict";var c=r(0),o=r(18);const n=Object(c.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},Object(c.createElement)("path",{d:"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"}));t.a=n},104:function(e,t){},11:function(e,t){e.exports=window.wp.apiFetch},12:function(e,t){e.exports=window.wp.blocks},13:function(e,t){e.exports=window.wp.data},14:function(e,t){e.exports=window.wp.htmlEntities},15:function(e,t){e.exports=window.wp.url},17:function(e,t,r){"use strict";r.d(t,"a",(function(){return a})),r.d(t,"c",(function(){return s})),r.d(t,"d",(function(){return i})),r.d(t,"b",(function(){return u}));var c=r(0),o=r(6),n=r(1);const a={clear:Object(n.__)("Clear all selected items","woo-gutenberg-products-block"),noItems:Object(n.__)("No items found.","woo-gutenberg-products-block"),
/* Translators: %s search term */
noResults:Object(n.__)("No results for %s","woo-gutenberg-products-block"),search:Object(n.__)("Search for items","woo-gutenberg-products-block"),selected:e=>Object(n.sprintf)(
/* translators: Number of items selected from list. */
Object(n._n)("%d item selected","%d items selected",e,"woo-gutenberg-products-block"),e),updated:Object(n.__)("Search results updated.","woo-gutenberg-products-block")},l=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:e;const r=Object(o.groupBy)(e,"parent"),c=Object(o.keyBy)(t,"id"),n=function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};if(!e.parent)return e.name?[e.name]:[];const t=n(c[e.parent]);return[...t,e.name]},a=e=>e.map(e=>{const t=r[e.id];return delete r[e.id],{...e,breadcrumbs:n(c[e.parent]),children:t&&t.length?a(t):[]}}),l=a(r[0]||[]);return delete r[0],Object(o.forEach)(r,e=>{l.push(...a(e||[]))}),l},s=(e,t,r)=>{if(!t)return r?l(e):e;const c=new RegExp(t.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"),"i"),o=e.map(e=>!!c.test(e.name)&&e).filter(Boolean);return r?l(o,e):o},i=(e,t)=>{if(!t)return e;const r=new RegExp(t.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"),"ig");return e.split(r).map((e,r)=>0===r?e:Object(c.createElement)(c.Fragment,{key:r},Object(c.createElement)("strong",null,t),e))},u=e=>1===e.length?e.slice(0,1).toString():2===e.length?e.slice(0,1).toString()+" › "+e.slice(-1).toString():e.slice(0,1).toString()+" … "+e.slice(-1).toString()},18:function(e,t){e.exports=window.wp.primitives},2:function(e,t){e.exports=window.wc.wcSettings},24:function(e,t,r){"use strict";r.d(t,"o",(function(){return n})),r.d(t,"m",(function(){return a})),r.d(t,"l",(function(){return l})),r.d(t,"n",(function(){return s})),r.d(t,"j",(function(){return i})),r.d(t,"e",(function(){return u})),r.d(t,"f",(function(){return d})),r.d(t,"g",(function(){return b})),r.d(t,"k",(function(){return g})),r.d(t,"c",(function(){return m})),r.d(t,"d",(function(){return p})),r.d(t,"h",(function(){return h})),r.d(t,"a",(function(){return O})),r.d(t,"i",(function(){return j})),r.d(t,"b",(function(){return w}));var c,o=r(2);const n=Object(o.getSetting)("wcBlocksConfig",{buildPhase:1,pluginUrl:"",productCount:0,defaultAvatar:"",restApiRoutes:{},wordCountType:"words"}),a=n.pluginUrl+"images/",l=n.pluginUrl+"build/",s=n.buildPhase,i=null===(c=o.STORE_PAGES.shop)||void 0===c?void 0:c.permalink,u=o.STORE_PAGES.checkout.id,d=o.STORE_PAGES.checkout.permalink,b=o.STORE_PAGES.privacy.permalink,g=(o.STORE_PAGES.privacy.title,o.STORE_PAGES.terms.permalink),m=(o.STORE_PAGES.terms.title,o.STORE_PAGES.cart.id),p=o.STORE_PAGES.cart.permalink,h=(o.STORE_PAGES.myaccount.permalink?o.STORE_PAGES.myaccount.permalink:Object(o.getSetting)("wpLoginUrl","/wp-login.php"),Object(o.getSetting)("shippingCountries",{})),O=Object(o.getSetting)("allowedCountries",{}),j=Object(o.getSetting)("shippingStates",{}),w=Object(o.getSetting)("allowedStates",{})},27:function(e,t,r){"use strict";r.d(t,"h",(function(){return i})),r.d(t,"e",(function(){return u})),r.d(t,"b",(function(){return d})),r.d(t,"i",(function(){return b})),r.d(t,"f",(function(){return g})),r.d(t,"c",(function(){return m})),r.d(t,"d",(function(){return p})),r.d(t,"g",(function(){return h})),r.d(t,"a",(function(){return O}));var c=r(15),o=r(11),n=r.n(o),a=r(6),l=r(2),s=r(24);const i=e=>{let{selected:t=[],search:r="",queryArgs:o={}}=e;const l=(e=>{let{selected:t=[],search:r="",queryArgs:o={}}=e;const n=s.o.productCount>100,a={per_page:n?100:0,catalog_visibility:"any",search:r,orderby:"title",order:"asc"},l=[Object(c.addQueryArgs)("/wc/store/products",{...a,...o})];return n&&t.length&&l.push(Object(c.addQueryArgs)("/wc/store/products",{catalog_visibility:"any",include:t,per_page:0})),l})({selected:t,search:r,queryArgs:o});return Promise.all(l.map(e=>n()({path:e}))).then(e=>Object(a.uniqBy)(Object(a.flatten)(e),"id").map(e=>({...e,parent:0}))).catch(e=>{throw e})},u=e=>n()({path:"/wc/store/products/"+e}),d=()=>n()({path:"wc/store/products/attributes"}),b=e=>n()({path:`wc/store/products/attributes/${e}/terms`}),g=e=>{let{selected:t=[],search:r}=e;const o=(e=>{let{selected:t=[],search:r}=e;const o=Object(l.getSetting)("limitTags",!1),n=[Object(c.addQueryArgs)("wc/store/products/tags",{per_page:o?100:0,orderby:o?"count":"name",order:o?"desc":"asc",search:r})];return o&&t.length&&n.push(Object(c.addQueryArgs)("wc/store/products/tags",{include:t})),n})({selected:t,search:r});return Promise.all(o.map(e=>n()({path:e}))).then(e=>Object(a.uniqBy)(Object(a.flatten)(e),"id"))},m=e=>n()({path:Object(c.addQueryArgs)("wc/store/products/categories",{per_page:0,...e})}),p=e=>n()({path:"wc/store/products/categories/"+e}),h=e=>n()({path:Object(c.addQueryArgs)("wc/store/products",{per_page:0,type:"variation",parent:e})}),O=(e,t)=>{if(!e.title.raw)return e.slug;const r=1===t.filter(t=>t.title.raw===e.title.raw).length;return e.title.raw+(r?"":" - "+e.slug)}},28:function(e,t,r){"use strict";r.d(t,"a",(function(){return o})),r.d(t,"b",(function(){return n}));var c=r(1);const o=async e=>{if("function"==typeof e.json)try{const t=await e.json();return{message:t.message,type:t.type||"api"}}catch(e){return{message:e.message,type:"general"}}return{message:e.message,type:e.type||"general"}},n=e=>{if(e.data&&"rest_invalid_param"===e.code){const t=Object.values(e.data.params);if(t[0])return t[0]}return(null==e?void 0:e.message)||Object(c.__)("Something went wrong. Please contact us to get assistance.","woo-gutenberg-products-block")}},3:function(e,t){e.exports=window.wp.components},31:function(e,t){e.exports=window.wp.escapeHtml},32:function(e,t,r){"use strict";var c=r(0),o=r(1),n=r(31);t.a=e=>{let{error:t}=e;return Object(c.createElement)("div",{className:"wc-block-error-message"},(e=>{let{message:t,type:r}=e;return t?"general"===r?Object(c.createElement)("span",null,Object(o.__)("The following error was returned","woo-gutenberg-products-block"),Object(c.createElement)("br",null),Object(c.createElement)("code",null,Object(n.escapeHTML)(t))):"api"===r?Object(c.createElement)("span",null,Object(o.__)("The following error was returned from the API","woo-gutenberg-products-block"),Object(c.createElement)("br",null),Object(c.createElement)("code",null,Object(n.escapeHTML)(t))):t:Object(o.__)("An unknown error occurred which prevented the block from being updated.","woo-gutenberg-products-block")})(t))}},33:function(e,t,r){"use strict";r.d(t,"a",(function(){return l}));var c=r(5),o=r.n(c),n=r(0),a=r(17);const l=e=>{let{countLabel:t,className:r,depth:c=0,controlId:l="",item:s,isSelected:i,isSingle:u,onSelect:d,search:b="",...g}=e;const m=null!=t&&void 0!==s.count&&null!==s.count,p=[r,"woocommerce-search-list__item"];p.push("depth-"+c),u&&p.push("is-radio-button"),m&&p.push("has-count");const h=s.breadcrumbs&&s.breadcrumbs.length,O=g.name||"search-list-item-"+l,j=`${O}-${s.id}`;return Object(n.createElement)("label",{htmlFor:j,className:p.join(" ")},u?Object(n.createElement)("input",o()({type:"radio",id:j,name:O,value:s.value,onChange:d(s),checked:i,className:"woocommerce-search-list__item-input"},g)):Object(n.createElement)("input",o()({type:"checkbox",id:j,name:O,value:s.value,onChange:d(s),checked:i,className:"woocommerce-search-list__item-input"},g)),Object(n.createElement)("span",{className:"woocommerce-search-list__item-label"},h?Object(n.createElement)("span",{className:"woocommerce-search-list__item-prefix"},Object(a.b)(s.breadcrumbs)):null,Object(n.createElement)("span",{className:"woocommerce-search-list__item-name"},Object(a.d)(s.name,b))),!!m&&Object(n.createElement)("span",{className:"woocommerce-search-list__item-count"},t||s.count))};t.b=l},352:function(e,t,r){e.exports=r(446)},353:function(e,t){},354:function(e,t){},43:function(e,t,r){"use strict";var c=r(0);t.a=function(e){let{srcElement:t,size:r=24,...o}=e;return Object(c.isValidElement)(t)?Object(c.cloneElement)(t,{width:r,height:r,...o}):null}},446:function(e,t,r){"use strict";r.r(t);var c=r(0),o=r(1),n=r(7),a=r(12),l=r(2),s=r(43),i=r(18),u=Object(c.createElement)(i.SVG,{xmlns:"http://www.w3.org/2000/SVG",viewBox:"0 0 24 24"},Object(c.createElement)("path",{fill:"none",d:"M0 0h24v24H0V0z"}),Object(c.createElement)("path",{d:"M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5.17l2 2H20v10zm-6.92-3.96L12.39 17 15 15.47 17.61 17l-.69-2.96 2.3-1.99-3.03-.26L15 9l-1.19 2.79-3.03.26z"})),d=(r(353),r(354),r(5)),b=r.n(d),g=r(25),m=r.n(g),p=r(3),h=r(4),O=r.n(h),j=r(13),w=r(9),f=(r(10),r(63)),_=r(71),y=r(6);function k(e){return e&&Object(y.isObject)(e.image)?e.image.src:""}var E=r(27),v=r(28),S=Object(w.createHigherOrderComponent)(e=>class extends c.Component{constructor(){super(...arguments),this.state={error:null,loading:!1,category:"preview"===this.props.attributes.categoryId?this.props.attributes.previewCategory:null},this.loadCategory=this.loadCategory.bind(this)}componentDidMount(){this.loadCategory()}componentDidUpdate(e){e.attributes.categoryId!==this.props.attributes.categoryId&&this.loadCategory()}loadCategory(){const{categoryId:e}=this.props.attributes;"preview"!==e&&(e?(this.setState({loading:!0}),Object(E.d)(e).then(e=>{this.setState({category:e,loading:!1,error:null})}).catch(async e=>{const t=await Object(v.a)(e);this.setState({category:null,loading:!1,error:t})})):this.setState({category:null,loading:!1,error:null}))}render(){const{error:t,loading:r,category:o}=this.state;return Object(c.createElement)(e,b()({},this.props,{error:t,getCategory:this.loadCategory,isLoading:r,category:o}))}},"withCategory"),C=Object(w.compose)([S,Object(n.withColors)({overlayColor:"background-color"}),p.withSpokenMessages,Object(j.withSelect)((e,t,r)=>{var c,o;let{clientId:n}=t,{dispatch:a}=r;const l=e("core/block-editor").getBlock(n),s=(null==l||null===(c=l.innerBlocks[0])||void 0===c?void 0:c.clientId)||"";return{updateBlockAttributes:e=>{s&&a("core/block-editor").updateBlockAttributes(s,e)},currentButtonAttributes:(null==l||null===(o=l.innerBlocks[0])||void 0===o?void 0:o.attributes)||{}}}),Object(w.createHigherOrderComponent)(e=>{class t extends c.Component{constructor(){super(...arguments),m()(this,"state",{doUrlUpdate:!1}),m()(this,"triggerUrlUpdate",()=>{this.setState({doUrlUpdate:!0})})}componentDidUpdate(){const{attributes:e,updateBlockAttributes:t,currentButtonAttributes:r,category:c}=this.props;this.state.doUrlUpdate&&!e.editMode&&null!=c&&c.permalink&&null!=r&&r.url&&c.permalink!==r.url&&(t({...r,url:c.permalink}),this.setState({doUrlUpdate:!1}))}render(){return Object(c.createElement)(e,b()({triggerUrlUpdate:this.triggerUrlUpdate},this.props))}}return t},"withUpdateButtonAttributes")])(e=>{let{attributes:t,isSelected:r,setAttributes:a,error:i,getCategory:d,isLoading:b,category:g,overlayColor:m,setOverlayColor:h,debouncedSpeak:j,triggerUrlUpdate:w=(()=>{})}=e;const{editMode:E}=t;return i?Object(c.createElement)(_.a,{className:"wc-block-featured-category-error",error:i,isLoading:b,onRetry:d}):E?Object(c.createElement)(p.Placeholder,{icon:Object(c.createElement)(s.a,{srcElement:u}),label:Object(o.__)("Featured Category","woo-gutenberg-products-block"),className:"wc-block-featured-category"},Object(o.__)("Visually highlight a product category and encourage prompt action.","woo-gutenberg-products-block"),Object(c.createElement)("div",{className:"wc-block-featured-category__selection"},Object(c.createElement)(f.a,{selected:[t.categoryId],onChange:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[];const t=e[0]?e[0].id:0;a({categoryId:t,mediaId:0,mediaSrc:""}),w()},isSingle:!0}),Object(c.createElement)(p.Button,{isPrimary:!0,onClick:()=>{a({editMode:!1}),j(Object(o.__)("Showing Featured Product block preview.","woo-gutenberg-products-block"))}},Object(o.__)("Done","woo-gutenberg-products-block")))):Object(c.createElement)(c.Fragment,null,(()=>{const{contentAlign:e,mediaSrc:r}=t,l=t.mediaId||function(e){return e&&Object(y.isObject)(e.image)?e.image.id:0}(g);return Object(c.createElement)(n.BlockControls,null,Object(c.createElement)(n.AlignmentToolbar,{value:e,onChange:e=>{a({contentAlign:e})}}),Object(c.createElement)(n.MediaReplaceFlow,{mediaId:l,mediaURL:r,accept:"image/*",onSelect:e=>{a({mediaId:e.id,mediaSrc:e.url})},allowedTypes:["image"]}),Object(c.createElement)(p.ToolbarGroup,{controls:[{icon:"edit",title:Object(o.__)("Edit selected category","woo-gutenberg-products-block"),onClick:()=>a({editMode:!E}),isActive:E}]}))})(),(()=>{const e=t.mediaSrc||k(g),{focalPoint:r={x:.5,y:.5}}=t,l="function"==typeof p.FocalPointPicker;return Object(c.createElement)(n.InspectorControls,{key:"inspector"},Object(c.createElement)(p.PanelBody,{title:Object(o.__)("Content","woo-gutenberg-products-block")},Object(c.createElement)(p.ToggleControl,{label:Object(o.__)("Show description","woo-gutenberg-products-block"),checked:t.showDesc,onChange:()=>a({showDesc:!t.showDesc})})),Object(c.createElement)(n.PanelColorSettings,{title:Object(o.__)("Overlay","woo-gutenberg-products-block"),colorSettings:[{value:m.color,onChange:h,label:Object(o.__)("Overlay Color","woo-gutenberg-products-block")}]},!!e&&Object(c.createElement)(c.Fragment,null,Object(c.createElement)(p.RangeControl,{label:Object(o.__)("Background Opacity","woo-gutenberg-products-block"),value:t.dimRatio,onChange:e=>a({dimRatio:e}),min:0,max:100,step:10}),l&&Object(c.createElement)(p.FocalPointPicker,{label:Object(o.__)("Focal Point Picker","woo-gutenberg-products-block"),url:e,value:r,onChange:e=>a({focalPoint:e})}))))})(),g?(()=>{const{className:e,contentAlign:s,dimRatio:i,focalPoint:u,height:d,showDesc:h}=t,j=O()("wc-block-featured-category",{"is-selected":r&&"preview"!==t.productId,"is-loading":!g&&b,"is-not-found":!g&&!b,"has-background-dim":0!==i},0===(w=i)||50===w?null:"has-background-dim-"+10*Math.round(w/10),"center"!==s&&`has-${s}-content`,e);var w;const f=t.mediaSrc||k(g),_=g&&(y=f)?{backgroundImage:`url(${y})`}:{};var y;if(m.color&&(_.backgroundColor=m.color),u){const e=100*u.x,t=100*u.y;_.backgroundPosition=`${e}% ${t}%`}return Object(c.createElement)(p.ResizableBox,{className:j,size:{height:d},minHeight:Object(l.getSetting)("min_height",500),enable:{bottom:!0},onResizeStop:(e,t,r)=>{a({height:parseInt(r.style.height,10)})},style:_},Object(c.createElement)("div",{className:"wc-block-featured-category__wrapper"},Object(c.createElement)("h2",{className:"wc-block-featured-category__title",dangerouslySetInnerHTML:{__html:g.name}}),h&&Object(c.createElement)("div",{className:"wc-block-featured-category__description",dangerouslySetInnerHTML:{__html:g.description}}),Object(c.createElement)("div",{className:"wc-block-featured-category__link"},(()=>{const e=O()("wp-block-button__link","is-style-fill");return"preview"===t.categoryId?Object(c.createElement)("div",{className:"wp-block-button aligncenter",style:{width:"100%"}},Object(c.createElement)(n.RichText.Content,{tagName:"a",className:e,href:g.permalink,title:t.linkText,style:{backgroundColor:"vivid-green-cyan",borderRadius:"5px"},value:t.linkText,target:g.permalink})):Object(c.createElement)(n.InnerBlocks,{template:[["core/button",{text:Object(o.__)("Shop now","woo-gutenberg-products-block"),url:g.permalink,align:"center"}]],templateLock:"all"})})())))})():Object(c.createElement)(p.Placeholder,{className:"wc-block-featured-category",icon:Object(c.createElement)(s.a,{srcElement:u}),label:Object(o.__)("Featured Category","woo-gutenberg-products-block")},b?Object(c.createElement)(p.Spinner,null):Object(o.__)("No product category is selected.","woo-gutenberg-products-block")))}),x=r(24);const N=[{id:1,name:Object(o.__)("Clothing","woo-gutenberg-products-block"),slug:"clothing",parent:0,count:10,description:`<p>${Object(o.__)("Branded t-shirts, jumpers, pants and more!","woo-gutenberg-products-block")}</p>\n`,image:{id:1,date_created:"2019-07-15T17:05:04",date_created_gmt:"2019-07-15T17:05:04",date_modified:"2019-07-15T17:05:04",date_modified_gmt:"2019-07-15T17:05:04",src:x.m+"previews/collection.jpg",name:"",alt:""},permalink:"#"}],P={attributes:{contentAlign:"center",dimRatio:50,editMode:!1,height:Object(l.getSetting)("default_height",500),mediaSrc:"",showDesc:!0,categoryId:"preview",previewCategory:N[0]}};Object(a.registerBlockType)("woocommerce/featured-category",{title:Object(o.__)("Featured Category","woo-gutenberg-products-block"),icon:{src:Object(c.createElement)(s.a,{srcElement:u,className:"wc-block-editor-components-block-icon"})},category:"woocommerce",keywords:[Object(o.__)("WooCommerce","woo-gutenberg-products-block")],description:Object(o.__)("Visually highlight a product category and encourage prompt action.","woo-gutenberg-products-block"),supports:{align:["wide","full"],html:!1},example:P,attributes:{contentAlign:{type:"string",default:"center"},dimRatio:{type:"number",default:50},editMode:{type:"boolean",default:!0},focalPoint:{type:"object"},height:{type:"number",default:Object(l.getSetting)("default_height",500)},mediaId:{type:"number",default:0},mediaSrc:{type:"string",default:""},overlayColor:{type:"string"},customOverlayColor:{type:"string"},linkText:{type:"string",default:Object(o.__)("Shop now","woo-gutenberg-products-block")},categoryId:{type:"number"},showDesc:{type:"boolean",default:!0},previewCategory:{type:"object",default:null}},edit:e=>Object(c.createElement)(C,e),save:()=>Object(c.createElement)(n.InnerBlocks.Content,null)})},6:function(e,t){e.exports=window.lodash},63:function(e,t,r){"use strict";var c=r(5),o=r.n(c),n=r(0),a=r(1),l=(r(10),r(33)),s=r(87),i=r(3),u=r(9),d=r(27),b=r(28),g=Object(u.createHigherOrderComponent)(e=>class extends n.Component{constructor(){super(...arguments),this.state={error:null,loading:!1,categories:[]},this.loadCategories=this.loadCategories.bind(this)}componentDidMount(){this.loadCategories()}loadCategories(){this.setState({loading:!0}),Object(d.c)().then(e=>{this.setState({categories:e,loading:!1,error:null})}).catch(async e=>{const t=await Object(b.a)(e);this.setState({categories:[],loading:!1,error:t})})}render(){const{error:t,loading:r,categories:c}=this.state;return Object(n.createElement)(e,o()({},this.props,{error:t,isLoading:r,categories:c}))}},"withCategories"),m=r(32),p=r(4),h=r.n(p);r(91);const O=e=>{let{categories:t,error:r,isLoading:c,onChange:u,onOperatorChange:d,operator:b,selected:g,isCompact:p,isSingle:O,showReviewCount:j}=e;const w={clear:Object(a.__)("Clear all product categories","woo-gutenberg-products-block"),list:Object(a.__)("Product Categories","woo-gutenberg-products-block"),noItems:Object(a.__)("Your store doesn't have any product categories.","woo-gutenberg-products-block"),search:Object(a.__)("Search for product categories","woo-gutenberg-products-block"),selected:e=>Object(a.sprintf)(
/* translators: %d is the count of selected categories. */
Object(a._n)("%d category selected","%d categories selected",e,"woo-gutenberg-products-block"),e),updated:Object(a.__)("Category search results updated.","woo-gutenberg-products-block")};return r?Object(n.createElement)(m.a,{error:r}):Object(n.createElement)(n.Fragment,null,Object(n.createElement)(s.a,{className:"woocommerce-product-categories",list:t,isLoading:c,selected:g.map(e=>t.find(t=>t.id===e)).filter(Boolean),onChange:u,renderItem:e=>{const{item:t,search:r,depth:c=0}=e,s=t.breadcrumbs.length?`${t.breadcrumbs.join(", ")}, ${t.name}`:t.name,i=j?Object(a.sprintf)(
/* translators: %1$s is the item name, %2$d is the count of reviews for the item. */
Object(a._n)("%1$s, has %2$d review","%1$s, has %2$d reviews",t.review_count,"woo-gutenberg-products-block"),s,t.review_count):Object(a.sprintf)(
/* translators: %1$s is the item name, %2$d is the count of products for the item. */
Object(a._n)("%1$s, has %2$d product","%1$s, has %2$d products",t.count,"woo-gutenberg-products-block"),s,t.count),u=j?Object(a.sprintf)(
/* translators: %d is the count of reviews. */
Object(a._n)("%d review","%d reviews",t.review_count,"woo-gutenberg-products-block"),t.review_count):Object(a.sprintf)(
/* translators: %d is the count of products. */
Object(a._n)("%d product","%d products",t.count,"woo-gutenberg-products-block"),t.count);return Object(n.createElement)(l.a,o()({className:h()("woocommerce-product-categories__item","has-count",{"is-searching":r.length>0,"is-skip-level":0===c&&0!==t.parent})},e,{countLabel:u,"aria-label":i}))},messages:w,isCompact:p,isHierarchical:!0,isSingle:O}),!!d&&Object(n.createElement)("div",{hidden:g.length<2},Object(n.createElement)(i.SelectControl,{className:"woocommerce-product-categories__operator",label:Object(a.__)("Display products matching","woo-gutenberg-products-block"),help:Object(a.__)("Pick at least two categories to use this setting.","woo-gutenberg-products-block"),value:b,onChange:d,options:[{label:Object(a.__)("Any selected categories","woo-gutenberg-products-block"),value:"any"},{label:Object(a.__)("All selected categories","woo-gutenberg-products-block"),value:"all"}]})))};O.defaultProps={operator:"any",isCompact:!1,isSingle:!1},t.a=g(O)},7:function(e,t){e.exports=window.wp.blockEditor},71:function(e,t,r){"use strict";var c=r(0),o=r(1),n=r(43),a=r(103),l=r(4),s=r.n(l),i=r(3),u=r(32);r(104),t.a=e=>{let{className:t,error:r,isLoading:l=!1,onRetry:d}=e;return Object(c.createElement)(i.Placeholder,{icon:Object(c.createElement)(n.a,{srcElement:a.a}),label:Object(o.__)("Sorry, an error occurred","woo-gutenberg-products-block"),className:s()("wc-block-api-error",t)},Object(c.createElement)(u.a,{error:r}),d&&Object(c.createElement)(c.Fragment,null,l?Object(c.createElement)(i.Spinner,null):Object(c.createElement)(i.Button,{isSecondary:!0,onClick:d},Object(o.__)("Retry","woo-gutenberg-products-block"))))}},87:function(e,t,r){"use strict";r.d(t,"a",(function(){return k}));var c=r(5),o=r.n(c),n=r(0),a=r(1),l=r(3),s=r(484),i=r(486),u=r(4),d=r.n(u),b=r(9),g=r(17),m=r(33),p=r(485),h=r(14);const O=e=>{let{id:t,label:r,popoverContents:c,remove:o,screenReaderLabel:i,className:u=""}=e;const[g,m]=Object(n.useState)(!1),j=Object(b.useInstanceId)(O);if(i=i||r,!r)return null;r=Object(h.decodeEntities)(r);const w=d()("woocommerce-tag",u,{"has-remove":!!o}),f="woocommerce-tag__label-"+j,_=Object(n.createElement)(n.Fragment,null,Object(n.createElement)("span",{className:"screen-reader-text"},i),Object(n.createElement)("span",{"aria-hidden":"true"},r));return Object(n.createElement)("span",{className:w},c?Object(n.createElement)(l.Button,{className:"woocommerce-tag__text",id:f,onClick:()=>m(!0)},_):Object(n.createElement)("span",{className:"woocommerce-tag__text",id:f},_),c&&g&&Object(n.createElement)(l.Popover,{onClose:()=>m(!1)},c),o&&Object(n.createElement)(l.Button,{className:"woocommerce-tag__remove",onClick:o(t),label:Object(a.sprintf)(// Translators: %s label.
Object(a.__)("Remove %s","woo-gutenberg-products-block"),r),"aria-describedby":f},Object(n.createElement)(s.a,{icon:p.a,size:20,className:"clear-icon"})))};var j=O;const w=e=>Object(n.createElement)(m.b,e),f=e=>{const{list:t,selected:r,renderItem:c,depth:a=0,onSelect:l,instanceId:s,isSingle:i,search:u}=e;return t?Object(n.createElement)(n.Fragment,null,t.map(t=>{const d=-1!==r.findIndex(e=>{let{id:r}=e;return r===t.id});return Object(n.createElement)(n.Fragment,{key:t.id},Object(n.createElement)("li",null,c({item:t,isSelected:d,onSelect:l,isSingle:i,search:u,depth:a,controlId:s})),Object(n.createElement)(f,o()({},e,{list:t.children,depth:a+1})))})):null},_=e=>{let{isLoading:t,isSingle:r,selected:c,messages:o,onChange:s,onRemove:i}=e;if(t||r||!c)return null;const u=c.length;return Object(n.createElement)("div",{className:"woocommerce-search-list__selected"},Object(n.createElement)("div",{className:"woocommerce-search-list__selected-header"},Object(n.createElement)("strong",null,o.selected(u)),u>0?Object(n.createElement)(l.Button,{isLink:!0,isDestructive:!0,onClick:()=>s([]),"aria-label":o.clear},Object(a.__)("Clear all","woo-gutenberg-products-block")):null),u>0?Object(n.createElement)("ul",null,c.map((e,t)=>Object(n.createElement)("li",{key:t},Object(n.createElement)(j,{label:e.name,id:e.id,remove:i})))):null)},y=e=>{let{filteredList:t,search:r,onSelect:c,instanceId:o,...l}=e;const{messages:u,renderItem:d,selected:b,isSingle:g}=l,m=d||w;return 0===t.length?Object(n.createElement)("div",{className:"woocommerce-search-list__list is-not-found"},Object(n.createElement)("span",{className:"woocommerce-search-list__not-found-icon"},Object(n.createElement)(s.a,{icon:i.a})),Object(n.createElement)("span",{className:"woocommerce-search-list__not-found-text"},r?Object(a.sprintf)(u.noResults,r):u.noItems)):Object(n.createElement)("ul",{className:"woocommerce-search-list__list"},Object(n.createElement)(f,{list:t,selected:b,renderItem:m,onSelect:c,instanceId:o,isSingle:g,search:r}))},k=e=>{const{className:t="",isCompact:r,isHierarchical:c,isLoading:a,isSingle:s,list:i,messages:u=g.a,onChange:m,onSearch:p,selected:h,debouncedSpeak:O}=e,[j,w]=Object(n.useState)(""),f=Object(b.useInstanceId)(k),E=Object(n.useMemo)(()=>({...g.a,...u}),[u]),v=Object(n.useMemo)(()=>Object(g.c)(i,j,c),[i,j,c]);Object(n.useEffect)(()=>{O&&O(E.updated)},[O,E]),Object(n.useEffect)(()=>{"function"==typeof p&&p(j)},[j,p]);const S=Object(n.useCallback)(e=>()=>{s&&m([]);const t=h.findIndex(t=>{let{id:r}=t;return r===e});m([...h.slice(0,t),...h.slice(t+1)])},[s,h,m]),C=Object(n.useCallback)(e=>()=>{-1===h.findIndex(t=>{let{id:r}=t;return r===e.id})?m(s?[e]:[...h,e]):S(e.id)()},[s,S,m,h]);return Object(n.createElement)("div",{className:d()("woocommerce-search-list",t,{"is-compact":r})},Object(n.createElement)(_,o()({},e,{onRemove:S,messages:E})),Object(n.createElement)("div",{className:"woocommerce-search-list__search"},Object(n.createElement)(l.TextControl,{label:E.search,type:"search",value:j,onChange:e=>w(e)})),a?Object(n.createElement)("div",{className:"woocommerce-search-list__list is-loading"},Object(n.createElement)(l.Spinner,null)):Object(n.createElement)(y,o()({},e,{search:j,filteredList:v,messages:E,onSelect:C,instanceId:f})))};Object(l.withSpokenMessages)(k)},9:function(e,t){e.exports=window.wp.compose},91:function(e,t){}});