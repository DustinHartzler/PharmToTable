(()=>{"use strict";var e={n:t=>{var s=t&&t.__esModule?()=>t.default:()=>t;return e.d(s,{a:s}),s},d:(t,s)=>{for(var a in s)e.o(s,a)&&!e.o(t,a)&&Object.defineProperty(t,a,{enumerable:!0,get:s[a]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t)};const t=window.wp.element,s=window.wp.plugins,a=window.wp.i18n,n=window.wp.components,r=window.wp.coreData,o=window.moment;var i=e.n(o);const l=window.wp.data,c="expires-after",p="expires-on",_="starts-on",u=(0,a.__)("Set Course Expiration Date","sensei-pro"),d=(0,a.__)("Set Course Start Date","sensei-pro"),m=e=>i()(e).format(i().HTML5_FMT.DATE);let w;const v=e=>{const[t,s]=(0,r.useEntityProp)("postType","course","meta");return[t[e],t=>s({[e]:t})]},x=()=>{const[e,s]=v("_course_expiration_type"),[r,o]=(0,t.useState)(e),[i,x]=v("_course_start_type"),[E,b]=(0,t.useState)(i),[y,h]=(0,t.useState)(!1),[S,g]=(0,t.useState)(!1),[f,C]=v("_course_expiration_length"),[D,P]=v("_course_expires_on_date"),[T,N]=v("_course_starts_on_date"),[k,A]=v("_course_expiration_period"),F=()=>{w={expirationType:e,startType:i}};w||F(),(0,t.useEffect)((()=>{(e=>{let{subscribeListener:t=(()=>{}),onSetDirty:s=(()=>{}),onSaveStart:a=(()=>{}),onSave:n=(()=>{})}=e;const r=(0,l.select)("core/editor");let o=!1,i=!1;(0,l.subscribe)((()=>{t();const e=r.isEditedPostDirty(),l=r.isSavingPost()&&!r.isAutosavingPost();!i&&e?(i=!0,s()):i=e,o&&!l?(o=l,n()):!o&&l?(o=l,a()):o=l}))})({onSave:()=>F})}));const L=(0,t.createElement)(t.Fragment,null,(0,t.createElement)("div",{className:"sensei-wcpc-course-expiration__expires-after"},(0,t.createElement)(n.TextControl,{className:"sensei-wcpc-course-expiration__expiration-length",label:(0,a.__)("Expiration Length","sensei-pro"),hideLabelFromVision:!0,type:"number",step:1,min:1,value:f,onChange:e=>{const t=Math.max(1,parseInt(e.replace(/\D/g,"")||1,10));C(t)},onKeyPress:e=>{/\D/.test(e.key)&&e.preventDefault()}}),(0,t.createElement)(n.SelectControl,{label:(0,a.__)("Expiration Period","sensei-pro"),hideLabelFromVision:!0,value:k,options:[{label:(0,a.__)("Month(s)","sensei-pro"),value:"month"},{label:(0,a.__)("Week(s)","sensei-pro"),value:"week"},{label:(0,a.__)("Day(s)","sensei-pro"),value:"day"}],onChange:A})),"day"===k&&1===f&&(0,t.createElement)("small",{className:"sensei-wcpc-course-expiration__help-text"},(0,a.__)("The learner access will expire at midnight on the day of enrollment.","sensei-pro"))),M=(e,s,a,r,o)=>(0,t.createElement)("div",null,(0,t.createElement)(n.Button,{onClick:()=>{e(!0)},className:"datepicker","data-testid":"set-date-button"},a),r&&(0,t.createElement)(n.DatePicker,{currentDate:s,onChange:o}));return(0,t.createElement)(n.PanelBody,{title:(0,a.__)("Access Period","sensei-pro"),className:"sensei-wcpc-course-expiration"},(0,t.createElement)("p",{className:"sensei-wcpc-course-expiration__intro"},(0,a.__)("Set a timeframe that students will have access to this course.","sensei-pro")),(0,t.createElement)("div",{className:"access-period-starts"},(0,t.createElement)(n.SelectControl,{label:(0,a.__)("Course Access Starts","sensei-pro"),value:E,options:[{label:(0,a.__)("Immediately","sensei-pro"),value:"immediately"},{label:(0,a.__)("On a specific date","sensei-pro"),value:_}],onChange:e=>{b(e),(e=>{x(e!==_||T?e:w.startType)})(e)},"data-testid":"access-period-starts"}),_===E&&M(g,T,T?(0,a.sprintf)(
/* translators: %s is replaced with start date string in format YYYY-MM-DD */
(0,a.__)("Starts on %s","sensei-pro"),m(T)):d,S,(e=>{N(e),g(!1),x(E)}))),(0,t.createElement)("div",{className:"access-period-expires"},(0,t.createElement)(n.SelectControl,{label:(0,a.__)("Course Access Ends","sensei-pro"),value:r,options:[{label:(0,a.__)("Never","sensei-pro"),value:"no-expiration"},{label:(0,a.__)("After a set period","sensei-pro"),value:c},{label:(0,a.__)("On a specific date","sensei-pro"),value:p}],onChange:e=>{o(e),(e=>{s(e!==p||D?e:w.expirationType)})(e)},"data-testid":"access-period-expires"}),c===r&&L,p===r&&M(h,D,D?(0,a.sprintf)(
/* translators: %s is replaced with start date string in format YYYY-MM-DD */
(0,a.__)("Expires on %s","sensei-pro"),m(D)):u,y,(e=>{P(e),h(!1),s(r)}))))};(0,s.registerPlugin)("sensei-pro-course-expiration-plugin",{render:()=>(0,t.createElement)(n.Fill,{name:"SenseiCourseSidebar"},(0,t.createElement)(x,null)),icon:null})})();