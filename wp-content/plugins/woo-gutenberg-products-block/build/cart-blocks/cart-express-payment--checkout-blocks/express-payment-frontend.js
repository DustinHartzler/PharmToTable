(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[6],{27:function(e,t,n){"use strict";n.d(t,"a",(function(){return a}));var o=n(0),s=n(14),c=n.n(s);function a(e){const t=Object(o.useRef)(e);return c()(e,t.current)||(t.current=e),t.current}},311:function(e,t,n){"use strict";n.d(t,"a",(function(){return l}));var o=n(1),s=n(4),c=n(3),a=n(22),r=n(9),i=n(46);const l=function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";const{cartCoupons:t,cartIsLoading:n}=Object(i.a)(),{createErrorNotice:l}=Object(s.useDispatch)("core/notices"),{createNotice:p}=Object(s.useDispatch)("core/notices"),{setValidationErrors:u}=Object(s.useDispatch)(c.VALIDATION_STORE_KEY),{isApplyingCoupon:m,isRemovingCoupon:d}=Object(s.useSelect)(e=>{const t=e(c.CART_STORE_KEY);return{isApplyingCoupon:t.isApplyingCoupon(),isRemovingCoupon:t.isRemovingCoupon()}},[l,p]),{applyCoupon:h,removeCoupon:b}=Object(s.useDispatch)(c.CART_STORE_KEY),g=t=>h(t).then(()=>(Object(r.applyCheckoutFilter)({filterName:"showApplyCouponNotice",defaultValue:!0,arg:{couponCode:t,context:e}})&&p("info",Object(o.sprintf)(
/* translators: %s coupon code. */
Object(o.__)('Coupon code "%s" has been applied to your cart.',"woo-gutenberg-products-block"),t),{id:"coupon-form",type:"snackbar",context:e}),Promise.resolve(!0))).catch(e=>(u({coupon:{message:Object(a.decodeEntities)(e.message),hidden:!1}}),Promise.resolve(!1))),y=t=>b(t).then(()=>(Object(r.applyCheckoutFilter)({filterName:"showRemoveCouponNotice",defaultValue:!0,arg:{couponCode:t,context:e}})&&p("info",Object(o.sprintf)(
/* translators: %s coupon code. */
Object(o.__)('Coupon code "%s" has been removed from your cart.',"woo-gutenberg-products-block"),t),{id:"coupon-form",type:"snackbar",context:e}),Promise.resolve(!0))).catch(t=>(l(t.message,{id:"coupon-form",context:e}),Promise.resolve(!1)));return{appliedCoupons:t,isLoading:n,applyCoupon:g,removeCoupon:y,isApplyingCoupon:m,isRemovingCoupon:d}}},312:function(e,t){},318:function(e,t,n){"use strict";n.d(t,"b",(function(){return i})),n.d(t,"a",(function(){return l}));var o=n(27),s=n(23),c=n(4),a=n(3);const r=function(){let e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];const{paymentMethodsInitialized:t,expressPaymentMethodsInitialized:n,availablePaymentMethods:r,availableExpressPaymentMethods:i}=Object(c.useSelect)(e=>{const t=e(a.PAYMENT_STORE_KEY);return{paymentMethodsInitialized:t.paymentMethodsInitialized(),expressPaymentMethodsInitialized:t.expressPaymentMethodsInitialized(),availableExpressPaymentMethods:t.getAvailableExpressPaymentMethods(),availablePaymentMethods:t.getAvailablePaymentMethods()}}),l=Object.values(r).map(e=>{let{name:t}=e;return t}),p=Object.values(i).map(e=>{let{name:t}=e;return t}),u=Object(s.getPaymentMethods)(),m=Object(s.getExpressPaymentMethods)(),d=Object.keys(u).reduce((e,t)=>(l.includes(t)&&(e[t]=u[t]),e),{}),h=Object.keys(m).reduce((e,t)=>(p.includes(t)&&(e[t]=m[t]),e),{}),b=Object(o.a)(d),g=Object(o.a)(h);return{paymentMethods:e?g:b,isInitialized:e?n:t}},i=()=>r(!1),l=()=>r(!0)},333:function(e,t,n){"use strict";var o=n(13),s=n.n(o),c=n(0),a=n(5),r=n.n(a);const i=e=>"wc-block-components-payment-method-icon wc-block-components-payment-method-icon--"+e;var l=e=>{let{id:t,src:n=null,alt:o=""}=e;return n?Object(c.createElement)("img",{className:i(t),src:n,alt:o}):null},p=n(26);const u=[{id:"alipay",alt:"Alipay",src:p.n+"payment-methods/alipay.svg"},{id:"amex",alt:"American Express",src:p.n+"payment-methods/amex.svg"},{id:"bancontact",alt:"Bancontact",src:p.n+"payment-methods/bancontact.svg"},{id:"diners",alt:"Diners Club",src:p.n+"payment-methods/diners.svg"},{id:"discover",alt:"Discover",src:p.n+"payment-methods/discover.svg"},{id:"eps",alt:"EPS",src:p.n+"payment-methods/eps.svg"},{id:"giropay",alt:"Giropay",src:p.n+"payment-methods/giropay.svg"},{id:"ideal",alt:"iDeal",src:p.n+"payment-methods/ideal.svg"},{id:"jcb",alt:"JCB",src:p.n+"payment-methods/jcb.svg"},{id:"laser",alt:"Laser",src:p.n+"payment-methods/laser.svg"},{id:"maestro",alt:"Maestro",src:p.n+"payment-methods/maestro.svg"},{id:"mastercard",alt:"Mastercard",src:p.n+"payment-methods/mastercard.svg"},{id:"multibanco",alt:"Multibanco",src:p.n+"payment-methods/multibanco.svg"},{id:"p24",alt:"Przelewy24",src:p.n+"payment-methods/p24.svg"},{id:"sepa",alt:"Sepa",src:p.n+"payment-methods/sepa.svg"},{id:"sofort",alt:"Sofort",src:p.n+"payment-methods/sofort.svg"},{id:"unionpay",alt:"Union Pay",src:p.n+"payment-methods/unionpay.svg"},{id:"visa",alt:"Visa",src:p.n+"payment-methods/visa.svg"},{id:"wechat",alt:"WeChat",src:p.n+"payment-methods/wechat.svg"}];var m=n(28);n(312),t.a=e=>{let{icons:t=[],align:n="center",className:o}=e;const a=(e=>{const t={};return e.forEach(e=>{let n={};"string"==typeof e&&(n={id:e,alt:e,src:null}),"object"==typeof e&&(n={id:e.id||"",alt:e.alt||"",src:e.src||null}),n.id&&Object(m.a)(n.id)&&!t[n.id]&&(t[n.id]=n)}),Object.values(t)})(t);if(0===a.length)return null;const i=r()("wc-block-components-payment-method-icons",{"wc-block-components-payment-method-icons--align-left":"left"===n,"wc-block-components-payment-method-icons--align-right":"right"===n},o);return Object(c.createElement)("div",{className:i},a.map(e=>{const t={...e,...(n=e.id,u.find(e=>e.id===n)||{})};var n;return Object(c.createElement)(l,s()({key:"payment-method-icon-"+e.id},t))}))}},379:function(e,t){},380:function(e,t,n){"use strict";var o=n(17),s=n.n(o),c=n(0),a=n(1),r=n(2),i=n(9),l=n(37);class p extends c.Component{constructor(){super(...arguments),s()(this,"state",{errorMessage:"",hasError:!1})}static getDerivedStateFromError(e){return{errorMessage:e.message,hasError:!0}}render(){const{hasError:e,errorMessage:t}=this.state,{isEditor:n}=this.props;if(e){let e=Object(a.__)("We are experiencing difficulties with this payment method. Please contact us for assistance.","woo-gutenberg-products-block");(n||r.CURRENT_USER_IS_ADMIN)&&(e=t||Object(a.__)("There was an error with this payment method. Please verify it's configured correctly.","woo-gutenberg-products-block"));const o=[{id:"0",content:e,isDismissible:!1,status:"error"}];return Object(c.createElement)(i.StoreNoticesContainer,{additionalNotices:o,context:l.d.PAYMENTS})}return this.props.children}}p.defaultProps={isEditor:!1},t.a=p},408:function(e,t){},409:function(e,t,n){"use strict";var o=n(0),s=n(1),c=n(318),a=n(459),r=n(52),i=n(18),l=n.n(i),p=n(4),u=n(380),m=n(106);t.a=()=>{const{isEditor:e}=Object(r.a)(),{activePaymentMethod:t,paymentMethodData:n}=Object(p.useSelect)(e=>{const t=e(m.a);return{activePaymentMethod:t.getActivePaymentMethod(),paymentMethodData:t.getPaymentMethodData()}}),{__internalSetActivePaymentMethod:i,__internalSetExpressPaymentStarted:d,__internalSetPaymentIdle:h,__internalSetPaymentError:b,__internalSetPaymentMethodData:g,__internalSetExpressPaymentError:y}=Object(p.useDispatch)(m.a),{paymentMethods:v}=Object(c.a)(),O=Object(a.a)(),E=Object(o.useRef)(t),j=Object(o.useRef)(n),P=Object(o.useCallback)(e=>()=>{E.current=t,j.current=n,d(),i(e)},[t,n,i,d]),S=Object(o.useCallback)(()=>{h(),i(E.current,j.current)},[i,h]),k=Object(o.useCallback)(e=>{b(),g(e),y(e),i(E.current,j.current)},[i,b,g,y]),C=Object(o.useCallback)((function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";l()("Express Payment Methods should use the provided onError handler instead.",{alternative:"onError",plugin:"woocommerce-gutenberg-products-block",link:"https://github.com/woocommerce/woocommerce-gutenberg-products-block/pull/4228"}),e?k(e):y("")}),[y,k]),f=Object.entries(v),_=f.length>0?f.map(t=>{let[n,s]=t;const c=e?s.edit:s.content;return Object(o.isValidElement)(c)?Object(o.createElement)("li",{key:n,id:"express-payment-method-"+n},Object(o.cloneElement)(c,{...O,onClick:P(n),onClose:S,onError:k,setExpressPaymentError:C})):null}):Object(o.createElement)("li",{key:"noneRegistered"},Object(s.__)("No registered Payment Methods","woo-gutenberg-products-block"));return Object(o.createElement)(u.a,{isEditor:e},Object(o.createElement)("ul",{className:"wc-block-components-express-payment__event-buttons"},_))}},459:function(e,t,n){"use strict";n.d(t,"a",(function(){return T}));var o=n(1),s=n(39),c=n(0),a=n(5),r=n.n(a),i=n(12),l=Object(c.createElement)(i.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},Object(c.createElement)("g",{fill:"none",fillRule:"evenodd"},Object(c.createElement)("path",{d:"M0 0h24v24H0z"}),Object(c.createElement)("path",{fill:"#000",fillRule:"nonzero",d:"M17.3 8v1c1 .2 1.4.9 1.4 1.7h-1c0-.6-.3-1-1-1-.8 0-1.3.4-1.3.9 0 .4.3.6 1.4 1 1 .2 2 .6 2 1.9 0 .9-.6 1.4-1.5 1.5v1H16v-1c-.9-.1-1.6-.7-1.7-1.7h1c0 .6.4 1 1.3 1 1 0 1.2-.5 1.2-.8 0-.4-.2-.8-1.3-1.1-1.3-.3-2.1-.8-2.1-1.8 0-.9.7-1.5 1.6-1.6V8h1.3zM12 10v1H6v-1h6zm2-2v1H6V8h8zM2 4v16h20V4H2zm2 14V6h16v12H4z"}),Object(c.createElement)("path",{stroke:"#000",strokeLinecap:"round",d:"M6 16c2.6 0 3.9-3 1.7-3-2 0-1 3 1.5 3 1 0 1-.8 2.8-.8"}))),p=Object(c.createElement)(i.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},Object(c.createElement)(i.Path,{fillRule:"evenodd",d:"M18.646 9H20V8l-1-.5L12 4 5 7.5 4 8v1h14.646zm-3-1.5L12 5.677 8.354 7.5h7.292zm-7.897 9.44v-6.5h-1.5v6.5h1.5zm5-6.5v6.5h-1.5v-6.5h1.5zm5 0v6.5h-1.5v-6.5h1.5zm2.252 8.81c0 .414-.334.75-.748.75H4.752a.75.75 0 010-1.5h14.5a.75.75 0 01.749.75z",clipRule:"evenodd"})),u=Object(c.createElement)(i.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},Object(c.createElement)(i.Path,{d:"M3.25 12a8.75 8.75 0 1117.5 0 8.75 8.75 0 01-17.5 0zM12 4.75a7.25 7.25 0 100 14.5 7.25 7.25 0 000-14.5zm-1.338 4.877c-.314.22-.412.452-.412.623 0 .171.098.403.412.623.312.218.783.377 1.338.377.825 0 1.605.233 2.198.648.59.414 1.052 1.057 1.052 1.852 0 .795-.461 1.438-1.052 1.852-.41.286-.907.486-1.448.582v.316a.75.75 0 01-1.5 0v-.316a3.64 3.64 0 01-1.448-.582c-.59-.414-1.052-1.057-1.052-1.852a.75.75 0 011.5 0c0 .171.098.403.412.623.312.218.783.377 1.338.377s1.026-.159 1.338-.377c.314-.22.412-.452.412-.623 0-.171-.098-.403-.412-.623-.312-.218-.783-.377-1.338-.377-.825 0-1.605-.233-2.198-.648-.59-.414-1.052-1.057-1.052-1.852 0-.795.461-1.438 1.052-1.852a3.64 3.64 0 011.448-.582V7.5a.75.75 0 011.5 0v.316c.54.096 1.039.296 1.448.582.59.414 1.052 1.057 1.052 1.852a.75.75 0 01-1.5 0c0-.171-.098-.403-.412-.623-.312-.218-.783-.377-1.338-.377s-1.026.159-1.338.377z"})),m=Object(c.createElement)(i.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},Object(c.createElement)(i.Path,{fillRule:"evenodd",d:"M5.5 9.5v-2h13v2h-13zm0 3v4h13v-4h-13zM4 7a1 1 0 011-1h14a1 1 0 011 1v10a1 1 0 01-1 1H5a1 1 0 01-1-1V7z",clipRule:"evenodd"})),d=n(82),h=n(28),b=n(21);n(379);const g={bank:p,bill:u,card:m,checkPayment:l};var y=e=>{let{icon:t="",text:n=""}=e;const o=!!t,s=Object(c.useCallback)(e=>o&&Object(h.a)(e)&&Object(b.b)(g,e),[o]),a=r()("wc-block-components-payment-method-label",{"wc-block-components-payment-method-label--with-icon":o});return Object(c.createElement)("span",{className:a},s(t)?Object(c.createElement)(d.a,{icon:g[t]}):t,n)},v=n(333),O=n(2),E=n(18),j=n.n(E),P=n(149),S=n(4),k=n(3),C=n(9),f=n(46),_=n(311),w=n(37),R=n(77),M=n(123),x=n(78);const I=(e,t)=>{const n=[],s=(t,n)=>{const o=n+"_tax",s=Object(b.b)(e,n)&&Object(h.a)(e[n])?parseInt(e[n],10):0;return{key:n,label:t,value:s,valueWithTax:s+(Object(b.b)(e,o)&&Object(h.a)(e[o])?parseInt(e[o],10):0)}};return n.push(s(Object(o.__)("Subtotal:","woo-gutenberg-products-block"),"total_items")),n.push(s(Object(o.__)("Fees:","woo-gutenberg-products-block"),"total_fees")),n.push(s(Object(o.__)("Discount:","woo-gutenberg-products-block"),"total_discount")),n.push({key:"total_tax",label:Object(o.__)("Taxes:","woo-gutenberg-products-block"),value:parseInt(e.total_tax,10),valueWithTax:parseInt(e.total_tax,10)}),t&&n.push(s(Object(o.__)("Shipping:","woo-gutenberg-products-block"),"total_shipping")),n};var A=n(94);const T=()=>{const{onCheckoutBeforeProcessing:e,onCheckoutValidationBeforeProcessing:t,onCheckoutAfterProcessingWithSuccess:n,onCheckoutAfterProcessingWithError:a,onSubmit:r,onCheckoutSuccess:i,onCheckoutFail:l,onCheckoutValidation:p}=Object(R.b)(),{isCalculating:u,isComplete:m,isIdle:d,isProcessing:h,customerId:b}=Object(S.useSelect)(e=>{const t=e(k.CHECKOUT_STORE_KEY);return{isComplete:t.isComplete(),isIdle:t.isIdle(),isProcessing:t.isProcessing(),customerId:t.getCustomerId(),isCalculating:t.isCalculating()}}),{paymentStatus:g,activePaymentMethod:E,shouldSavePayment:T}=Object(S.useSelect)(e=>{const t=e(k.PAYMENT_STORE_KEY);return{paymentStatus:{get isPristine(){return j()("isPristine",{since:"9.6.0",alternative:"isIdle",plugin:"WooCommerce Blocks",link:"https://github.com/woocommerce/woocommerce-blocks/pull/8110"}),t.isPaymentIdle()},isIdle:t.isPaymentIdle(),isStarted:t.isExpressPaymentStarted(),isProcessing:t.isPaymentProcessing(),get isFinished(){return j()("isFinished",{since:"9.6.0",plugin:"WooCommerce Blocks",link:"https://github.com/woocommerce/woocommerce-blocks/pull/8110"}),t.hasPaymentError()||t.isPaymentReady()},hasError:t.hasPaymentError(),get hasFailed(){return j()("hasFailed",{since:"9.6.0",plugin:"WooCommerce Blocks",link:"https://github.com/woocommerce/woocommerce-blocks/pull/8110"}),t.hasPaymentError()},get isSuccessful(){return j()("isSuccessful",{since:"9.6.0",plugin:"WooCommerce Blocks",link:"https://github.com/woocommerce/woocommerce-blocks/pull/8110"}),t.isPaymentReady()},isReady:t.isPaymentReady(),isDoingExpressPayment:t.isExpressPaymentMethodActive()},activePaymentMethod:t.getActivePaymentMethod(),shouldSavePayment:t.getShouldSavePaymentMethod()}}),{__internalSetExpressPaymentError:z}=Object(S.useDispatch)(k.PAYMENT_STORE_KEY),{onPaymentProcessing:D,onPaymentSetup:V}=Object(M.b)(),{shippingErrorStatus:N,shippingErrorTypes:F,onShippingRateSuccess:B,onShippingRateFail:W,onShippingRateSelectSuccess:Y,onShippingRateSelectFail:L}=Object(x.b)(),{shippingRates:H,isLoadingRates:K,selectedRates:G,isSelectingRate:U,selectShippingRate:J,needsShipping:q}=Object(A.a)(),{billingAddress:Q,shippingAddress:X}=Object(S.useSelect)(e=>e(k.CART_STORE_KEY).getCustomerData()),{setShippingAddress:Z}=Object(S.useDispatch)(k.CART_STORE_KEY),{cartItems:$,cartFees:ee,cartTotals:te,extensions:ne}=Object(f.a)(),{appliedCoupons:oe}=Object(_.a)(),se=Object(c.useRef)(I(te,q)),ce=Object(c.useRef)({label:Object(o.__)("Total","woo-gutenberg-products-block"),value:parseInt(te.total_price,10)});Object(c.useEffect)(()=>{se.current=I(te,q),ce.current={label:Object(o.__)("Total","woo-gutenberg-products-block"),value:parseInt(te.total_price,10)}},[te,q]);const ae=Object(c.useCallback)((function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";j()("setExpressPaymentError should only be used by Express Payment Methods (using the provided onError handler).",{alternative:"",plugin:"woocommerce-gutenberg-products-block",link:"https://github.com/woocommerce/woocommerce-gutenberg-products-block/pull/4228"}),z(e)}),[z]);return{activePaymentMethod:E,billing:{appliedCoupons:oe,billingAddress:Q,billingData:Q,cartTotal:ce.current,cartTotalItems:se.current,currency:Object(s.getCurrencyFromPriceResponse)(te),customerId:b,displayPricesIncludingTax:Object(O.getSetting)("displayCartPricesIncludingTax",!1)},cartData:{cartItems:$,cartFees:ee,extensions:ne},checkoutStatus:{isCalculating:u,isComplete:m,isIdle:d,isProcessing:h},components:{LoadingMask:P.a,PaymentMethodIcons:v.a,PaymentMethodLabel:y,ValidationInputError:C.ValidationInputError},emitResponse:{noticeContexts:w.d,responseTypes:w.e},eventRegistration:{onCheckoutAfterProcessingWithError:a,onCheckoutAfterProcessingWithSuccess:n,onCheckoutBeforeProcessing:e,onCheckoutValidationBeforeProcessing:t,onCheckoutSuccess:i,onCheckoutFail:l,onCheckoutValidation:p,onPaymentProcessing:D,onPaymentSetup:V,onShippingRateFail:W,onShippingRateSelectFail:L,onShippingRateSelectSuccess:Y,onShippingRateSuccess:B},onSubmit:r,paymentStatus:g,setExpressPaymentError:ae,shippingData:{isSelectingRate:U,needsShipping:q,selectedRates:G,setSelectedRates:J,setShippingAddress:Z,shippingAddress:X,shippingRates:H,shippingRatesLoading:K},shippingStatus:{shippingErrorStatus:N,shippingErrorTypes:F},shouldSavePayment:T}}}}]);