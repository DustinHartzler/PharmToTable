(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[37],{489:function(e,t,i){"use strict";i.r(t);var s=i(0),n=i(6),l=i.n(n),o=i(112),d=i(294),r=i(385),c=i(5),a=i(3),b=i(54),p=i(40),h=i(34),u=i(392),g=i(389),m=i(7),O=i(390),j=e=>{let{showCompanyField:t=!1,showApartmentField:i=!1,showPhoneField:n=!1,requireCompanyField:l=!1,requirePhoneField:o=!1}=e;const{defaultAddressFields:d,billingAddress:c,setBillingAddress:a,setShippingAddress:j,setBillingPhone:w,setShippingPhone:F,useBillingAsShipping:E}=Object(r.a)(),{dispatchCheckoutEvent:f}=Object(b.a)(),{isEditor:S}=Object(p.a)();Object(s.useEffect)(()=>{n||w("")},[n,w]);const[_,y]=Object(s.useState)(!1);Object(s.useEffect)(()=>{_||(E&&j(c),y(!0))},[_,j,c,E]);const A=Object(s.useMemo)(()=>({company:{hidden:!t,required:l},address_2:{hidden:!i}}),[t,l,i]),k=S?g.a:s.Fragment,C=E?[h.d.BILLING_ADDRESS,h.d.SHIPPING_ADDRESS]:[h.d.BILLING_ADDRESS];return Object(s.createElement)(k,null,Object(s.createElement)(m.StoreNoticesContainer,{context:C}),Object(s.createElement)(u.a,{id:"billing",type:"billing",onChange:e=>{a(e),E&&(j(e),f("set-shipping-address")),f("set-billing-address")},values:c,fields:Object.keys(d),fieldConfig:A}),n&&Object(s.createElement)(O.a,{id:"billing-phone",errorId:"billing_phone",isRequired:o,value:c.phone,onChange:e=>{w(e),f("set-phone-number",{step:"billing"}),E&&(F(e),f("set-phone-number",{step:"shipping"}))}}))},w=i(269),F=i(1);const E=Object(F.__)("Billing address","woo-gutenberg-products-block"),f=Object(F.__)("Enter the billing address that matches your payment method.","woo-gutenberg-products-block"),S=Object(F.__)("Billing and shipping address","woo-gutenberg-products-block"),_=Object(F.__)("Enter the billing and shipping address that matches your payment method.","woo-gutenberg-products-block");var y={...Object(w.a)({defaultTitle:E,defaultDescription:f}),className:{type:"string",default:""},lock:{type:"object",default:{move:!0,remove:!0}}},A=i(128);t.default=Object(o.withFilteredAttributes)(y)(e=>{let{title:t,description:i,showStepNumber:n,children:o,className:b}=e;const p=Object(c.useSelect)(e=>e(a.CHECKOUT_STORE_KEY).isProcessing()),{requireCompanyField:h,requirePhoneField:u,showApartmentField:g,showCompanyField:m,showPhoneField:O}=Object(A.b)(),{showBillingFields:w,forcedBillingAddress:F,useBillingAsShipping:y}=Object(r.a)();return w||y?(t=((e,t)=>t?e===E?S:e:e===S?E:e)(t,F),i=((e,t)=>t?e===f?_:e:e===_?f:e)(i,F),Object(s.createElement)(d.a,{id:"billing-fields",disabled:p,className:l()("wc-block-checkout__billing-fields",b),title:t,description:i,showStepNumber:n},Object(s.createElement)(j,{requireCompanyField:h,showApartmentField:g,showCompanyField:m,showPhoneField:O,requirePhoneField:u}),o)):null})}}]);