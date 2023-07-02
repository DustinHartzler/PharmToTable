!function(e){var t={};function a(n){if(t[n])return t[n].exports;var i=t[n]={i:n,l:!1,exports:{}};return e[n].call(i.exports,i,i.exports,a),i.l=!0,i.exports}a.m=e,a.c=t,a.d=function(e,t,n){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(a.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)a.d(n,i,function(t){return e[t]}.bind(null,i));return n},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="",a(a.s=3)}([function(e,t,a){"use strict";function n(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}a.d(t,"a",(function(){return i}));var i=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e)}var t,a,i;return t=e,i=[{key:"requestHandler",value:function(t){var a=new FormData,n=t.requestData||{};for(var i in n)a.append(i,n[i]);m.request({method:t.method||"POST",url:e.getAFWParams("ajaxurl")||"",params:{action:n.dashboard},body:a,withCredentials:t.withCredentials||!1,responseType:t.responseType||"json"}).then((function(e){t.hasOwnProperty("callback")&&t.callback(e)}))}},{key:"getDate",value:function(e){var t,a=new Date,n="";switch(e){case"today":t=a,n=a;break;case"yesterday":t=new Date(a.getFullYear(),a.getMonth(),a.getDate()-1),n=new Date(a.getFullYear(),a.getMonth(),a.getDate()-1);break;case"this_week":t=new Date(a.getFullYear(),a.getMonth(),a.getDate()-(a.getDay()-1)),n=a;break;case"last_week":t=new Date(a.getFullYear(),a.getMonth(),a.getDate()-(a.getDay()-1)-7),n=new Date(a.getFullYear(),a.getMonth(),a.getDate()-(a.getDay()-1)-1);break;case"last_4_week":t=new Date(a.getFullYear(),a.getMonth(),a.getDate()-29),n=a;break;case"this_month":t=new Date(a.getFullYear(),a.getMonth(),1),n=a;break;case"last_month":t=new Date(a.getFullYear(),a.getMonth()-1,1),n=new Date(a.getFullYear(),a.getMonth(),0);break;case"3_months":t=new Date(a.getFullYear(),a.getMonth()-2,1),n=a;break;case"6_months":t=new Date(a.getFullYear(),a.getMonth()-5,1),n=a;break;case"this_year":t=new Date(a.getFullYear(),0,1),n=a;break;case"last_year":t=new Date(a.getFullYear()-1,0,1),n=new Date(a.getFullYear(),0,0);break;default:t=new Date(a.getFullYear(),a.getMonth(),1),n=a}var i=6e4*(new Date).getTimezoneOffset();return t=new Date(t.getTime()-i),n=new Date(n.getTime()-i),{startDate:t.toISOString().slice(0,10),endDate:n.toISOString().slice(0,10)}}},{key:"getDateTime",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=t.dayStart,n=void 0!==a&&a,i=t.dayEnd,r=void 0!==i&&i,o=n?"00":r?"23":(new Date).getHours(),s=n?"00":r?"59":(new Date).getMinutes(),l=n?"00":r?"59":(new Date).getSeconds();return e+" "+o+":"+s+":"+l}},{key:"getFullDate",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,t=new Date,a=0!==e?new Date(t.setDate(t.getDate()+e)):t;return a.getFullYear()+"-"+("0"+(a.getMonth()+1)).slice(-2)+"-"+("0"+a.getDate()).slice(-2)}},{key:"formatNumber",value:function(e){return accounting.formatNumber(e,{precision:afwcDashboardParams.precision,thousand:afwcDashboardParams.thousandSeperator,decimal:afwcDashboardParams.decimal})}},{key:"getAFWParams",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=Object.keys(t).length?t:afwcDashboardParams||{};if(e.includes(".")){var n=this.getAFWParams(e.slice(0,e.indexOf(".")),a);return this.getAFWParams(e.slice(e.indexOf(".")+1),n)}return""!==e?e in a?a[e]:"":a}},{key:"getCampaignStatus",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",a=e.getAFWParams("campaignStatuses")||{};return""===t?a:t in a?a[t]:""}},{key:"getCommissionPlansStatus",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",a=e.getAFWParams("commissionPlanStatuses")||{};return""===t?a:t in a?a[t]:""}},{key:"isValidDate",value:function(){var e=this,t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",a=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"-";if(!t)return!1;if(Array.isArray(t))return t.every((function(t){return e.isValidDate(t,a)}));var n=t.split(a);return!(3!==n.length||!n[0]||parseInt(n[0])<2e3||parseInt(n[0])>9999||!n[1]||parseInt(n[1])<1||parseInt(n[1])>12||!n[2]||parseInt(n[2])<1||parseInt(n[2])>31)}}],(a=null)&&n(t.prototype,a),i&&n(t,i),e}()},function(e,t,a){"use strict";a.d(t,"a",(function(){return k}));var n=a(0);function i(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var r=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),e.showLoader=e.showLoader||!1,e.msg=e.msg||_x("Loading","default loader message","affiliate-for-woocommerce")}var t,a,n;return t=e,n=[{key:"showLoader",get:function(){return e._showLoader},set:function(t){return e._showLoader=Boolean(t)}},{key:"msg",get:function(){return e._msg},set:function(t){return e._msg=t}}],(a=[{key:"view",value:function(t){return m("div",{class:"fixed w-full flex flex-col left-0 top-0 h-full justify-center text-center items-center space-y-4 z-50"},e.msg?m("div",{class:"text-lg text-gray-600"},e.msg):"",m("div",{class:"text-indigo-600"},m("svg",{xmlns:"http://www.w3.org/2000/svg",class:"w-16 h-16",stroke:"currentColor",fill:"none",viewBox:"0 0 57 57"},m("g",{transform:"translate(1 1)","stroke-width":"2",fill:"none","fill-rule":"evenodd"},m("circle",{cx:"5",cy:"50",r:"5"},m("animate",{attributeName:"cy",begin:"0s",dur:"2.2s",values:"50;5;50;50",calcMode:"linear",repeatCount:"indefinite"}),m("animate",{attributeName:"cx",begin:"0s",dur:"2.2s",values:"5;27;49;5",calcMode:"linear",repeatCount:"indefinite"})),m("circle",{cx:"27",cy:"5",r:"5"},m("animate",{attributeName:"cy",begin:"0s",dur:"2.2s",from:"5",to:"5",values:"5;50;50;5",calcMode:"linear",repeatCount:"indefinite"}),m("animate",{attributeName:"cx",begin:"0s",dur:"2.2s",from:"27",to:"27",values:"27;49;5;27",calcMode:"linear",repeatCount:"indefinite"})),m("circle",{cx:"49",cy:"50",r:"5"},m("animate",{attributeName:"cy",begin:"0s",dur:"2.2s",values:"50;50;5;50",calcMode:"linear",repeatCount:"indefinite"}),m("animate",{attributeName:"cx",from:"49",to:"49",begin:"0s",dur:"2.2s",values:"49;5;27;49",calcMode:"linear",repeatCount:"indefinite"}))))))}}])&&i(t.prototype,a),n&&i(t,n),e}();function o(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var s=function(){function e(t){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),e.data={kpi:{},campaigns:[]},this.params=t||{},this.fetch()}var t,a,i;return t=e,i=[{key:"data",get:function(){return e._data},set:function(t){e._data=t}}],(a=[{key:"fetch",value:function(){r.showLoader=!0,r.msg=_x("Loading Campaigns","Loading text for campaigns","affiliate-for-woocommerce"),n.a.requestHandler({requestData:{cmd:"fetch_dashboard_data",security:n.a.getAFWParams("security.campaign.fetchData")||"",dashboard:"afwc_campaign_controller",campaign_status:afwcDashboardParams.campaign_status||""},callback:function(t){"Success"==t.ACK&&(e.data.kpi=t.result.kpi||{},e.data.campaigns=t.result.campaigns||[]),r.showLoader=!1}})}}])&&o(t.prototype,a),i&&o(t,i),e}();function l(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}s.data={kpi:{},campaigns:[]};var c=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),e.initialize()}var t,a,n;return t=e,n=[{key:"initialize",value:function(){e.reset()}},{key:"reset",value:function(){e.flags={showNotification:0},e.notification={status:"warning",message:"",autoHide:!0,hideDelay:8e3}}},{key:"show",value:function(t){var a=t.status,n=void 0===a?"warning":a,i=t.message,r=void 0===i?"":i,o=t.autoHide,s=void 0===o||o,l=t.hideDelay,c=void 0===l?8e3:l;e.notification={status:n,message:r,autoHide:s,hideDelay:c},e.flags.showNotification=1}},{key:"hide",value:function(){e.reset()}}],(a=null)&&l(t.prototype,a),n&&l(t,n),e}();function u(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}c.initialize();var f=function(){function e(t){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),e.initialize(),this.setCurrentCampaign(e.currentCampaignID)}var t,a,i;return t=e,i=[{key:"initialize",value:function(){e.details={title:"",targetLink:afwcDashboardParams.home_url,slug:"",campaignId:0,shortDescription:"",body:"",status:"Draft",metaData:{}}}},{key:"newCampaign",value:function(){e.currentCampaignID=-1,new e}},{key:"saveCampaign",value:function(){n.a.requestHandler({requestData:{cmd:"save_campaign",campaign:JSON.stringify(e.details),security:n.a.getAFWParams("security.campaign.save")||"",dashboard:"afwc_campaign_controller"},callback:function(t){if("Success"==t.ACK){var a="";t.last_inserted_id?(e.details.campaignId=t.last_inserted_id,s.data.campaigns.push(e.details),a=_x("Campaign created successfully","Notification message for successful campaign creation","affiliate-for-woocommerce")):(s.data.campaigns=s.data.campaigns.map((function(t){return t.campaignId==e.details.campaignId?e.details:t})),a=_x("Campaign updated successfully","Notification message for successful campaign update","affiliate-for-woocommerce")),c.show({status:"success",message:a}),e.currentCampaignID=0}else c.show({status:"error",message:_x("Failed to create or update campaign","Notification message for failed campaign creation or update","affiliate-for-woocommerce")})}})}},{key:"deleteCampaign",value:function(){n.a.requestHandler({requestData:{cmd:"delete_campaign",campaign_id:e.currentCampaignID,security:n.a.getAFWParams("security.campaign.delete")||"",dashboard:"afwc_campaign_controller"},callback:function(t){if("Success"==t.ACK){var a=e.details.campaignId;s.data.campaigns=s.data.campaigns.filter((function(e){return e.campaignId!=a})),e.currentCampaignID=0,c.show({status:"success",message:_x("Campaign deleted successfully","Notification message for successful campaign deletion","affiliate-for-woocommerce")})}else c.show({status:"error",message:_x("Failed to delete campaign","Notification message for failed campaign deletion","affiliate-for-woocommerce")})}})}},{key:"details",get:function(){return e._details},set:function(t){e._details=t}}],(a=[{key:"setCurrentCampaign",value:function(t){var a={};t>0&&(a=s.data.campaigns.filter((function(e){return e.campaignId==t}))[0]),e.details={title:a.title||"",targetLink:a.targetLink||afwcDashboardParams.home_url,slug:a.slug||"",campaignId:a.campaignId||0,shortDescription:a.shortDescription||"",body:a.body||"",status:a.status||"Draft",metaData:a.metaData||{}}}}])&&u(t.prototype,a),i&&u(t,i),e}();function d(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}f.initialize();var g=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e)}var t,a,n;return t=e,n=[{key:"affiliateDisplayLink",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"";try{var n="",i=afwcDashboardParams.isPrettyReferralEnabled||"no",r=afwcDashboardParams.pname,o="admin"==a?"{affiliate_id}":afwcDashboardParams.affiliate_id;return n=-1==(e=e.replace(/\/$/,"")).indexOf("?")?e+("yes"==i?"/"+r+"/"+o:"?"+r+"="+o):"yes"==i?e.substring(0,e.indexOf("?")).replace(/\/$/,"")+"/"+r+"/"+o+"/?"+e.substring(e.indexOf("?")+1):e+"&"+r+"="+o,n+=""!=t?(-1==n.indexOf("?")?"/?":"&")+"utm_campaign="+t:""}catch(e){console.log("In GlobalFunctions for affiliate links:: ",e)}}},{key:"doCopy",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";try{if(e.length>0){var t=document.createElement("input");t.setAttribute("value",e),document.body.appendChild(t),t.select(),navigator&&navigator.clipboard&&navigator.clipboard.writeText(t.value),document.body.removeChild(t)}}catch(e){console.log("In GlobalFunctions to copy:: ",e)}}}],(a=null)&&d(t.prototype,a),n&&d(t,n),e}();function p(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var h=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e)}var t,a,n;return t=e,(a=[{key:"view",value:function(e){var t;return t=g.affiliateDisplayLink(f.details.targetLink,f.details.slug,"frontend"),m("aside",{class:"fixed inset-0 overflow-hidden",style:"z-index:120000"},m("div",{class:"absolute inset-0 overflow-hidden"},m("div",{class:"absolute inset-0 bg-gray-500 transition-opacity opacity-75",onclick:function(){f.currentCampaignID=0}}),m("section",{class:"absolute inset-y-0 right-0 pl-20 max-w-full flex"},m("div",{class:"w-screen max-w-5xl"},m("div",{class:"h-full flex flex-col space-y-6 py-4 bg-white shadow-xl overflow-y-scroll"},m("header",{class:"px-4 sm:px-6"},m("div",{class:"flex items-start justify-between space-x-3"},m("h2",{class:"text-3xl leading-8 text-gray-900"},f.details.title||""),m("div",{class:"h-7 flex items-center space-x-3"},m("button",{"aria-label":"Close panel",class:"leading-none text-gray-400 hover:text-gray-500 transition ease-in-out duration-150 p-0",onclick:function(){f.currentCampaignID=0}},m("svg",{class:"h-6 w-6",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"})))))),m("div",{class:"relative flex-1 px-4 sm:px-6"},m("div",{class:"absolute inset-0 px-4 sm:px-6"},m("p",{class:"text-lg"},f.details.shortDescription||""),m("p",{class:"mt-4"},_x("Link: ","Campaign tracking link","affiliate-for-woocommerce"),m("span",{"data-bs-toggle":"tooltip",title:_x("Click to copy","My account - tooltip title for copying campaign referral link","affiliate-for-woocommerce"),onclick:function(e){g.doCopy(t||"")},class:"cursor-pointer"},m("code",null,t||""))),m("div",{class:"mt-12 mb-4"},m.trust(f.details.body||"")))))))))}}])&&p(t.prototype,a),n&&p(t,n),e}();function v(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var y=function(){function e(t){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.params={id:t.attrs.id||0,startDate:t.attrs.startDate||"",endDate:t.attrs.endDate||""}}var t,a,n;return t=e,(a=[{key:"refreshModel",value:function(e){this.params.id=e,this.model=new f}},{key:"view",value:function(){var e=this;return m("div",null,m("div",null,m("ul",{class:"text-gray-600 m-0 w-full"},s.data.campaigns.map((function(t,a){return m("li",{style:"cursor:pointer",class:"my-2 overflow-hidden text-gray-400 bg-white shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50",onclick:function(){f.currentCampaignID=t.campaignId,e.refreshModel(f.currentCampaignID)}},m("div",{class:"flex items-center pl-4 py-2"},m("div",{class:"text-gray-700 text-lg pr-4 flex-1 sm:truncate leading-6"},t.title),m("div",{class:"mx-2"},m("svg",{class:"h-5 w-5 text-gray-400",fill:"currentColor",viewBox:"0 0 20 20"},m("path",{"fill-rule":"evenodd",d:"M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z","clip-rule":"evenodd"})))))})))),0!==f.currentCampaignID?m(h,{id:f.currentCampaignID}):"")}}])&&v(t.prototype,a),n&&v(t,n),e}();function w(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function b(e,t,a){return t&&w(e.prototype,t),a&&w(e,a),e}f.currentCampaignID=0;var k=function(){function e(t){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.initialize(t.attrs)}return b(e,[{key:"initialize",value:function(e){this.urlParams=Object.keys(e).length?e:{start_date:new Date((new Date).setDate((new Date).getDate()-30)).toISOString().slice(0,10),end_date:(new Date).toISOString().slice(0,10)},this.model=new s(this.urlParams)}}]),b(e,[{key:"onupdate",value:function(e){this.urlParams!=e.attrs&&Object.keys(e.attrs).length&&this.initialize(e.attrs)}},{key:"view",value:function(){return m("div",null,s.data.campaigns.length>0?m("[",null,m(y,null)):m("p",{class:"text-xl"},afwcDashboardParams.no_campaign_string))}}]),e}()},function(e,t,a){"use strict";a.d(t,"a",(function(){return f}));var n=a(0);function i(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var o=function(){function e(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};i(this,e),this.params=t||{},this.affiliateID=this.params.id||0,e.details={},e.flags={loading:!1}}var t,a,o;return t=e,o=[{key:"details",get:function(){return e._details},set:function(t){e._details=t}},{key:"flags",get:function(){return e._flags},set:function(t){e._flags=t}}],(a=[{key:"fetch",value:function(){this.affiliateID&&(e.flags.loading=!0,n.a.requestHandler({requestData:{dashboard:"afwc_dashboard_controller",cmd:"affiliate_chain_data",security:n.a.getAFWParams("security.dashboard.multiTierData")||"",affiliate_id:this.affiliateID},callback:function(t){if("Success"===t.ACK){var a=t.data||{};Object.entries(a).length>0&&(e.details=a.multiTierChain||{})}e.flags.loading=!1}}))}}])&&r(t.prototype,a),o&&r(t,o),e}();function s(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function l(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var c=function(){function e(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};s(this,e),this.params={id:t.attrs.id||0,editURL:t.attrs.editURL||""},this.nodeCss="inline-block p-2 text-sm border-solid border-gray-200 text-gray-500 w-40 border hover:text-gray-900 rounded-lg bg-warning-light",this.placeholderNodeCss="inline-block text-sm border-solid border-gray-200 text-gray-500 w-40 border rounded-lg afw-placeholder m-2 h-8",this.multiTierWrapperCss="multi-tier-tree flex items-center justify-center"}var t,a,n;return t=e,(a=[{key:"oninit",value:function(){new o(this.params).fetch()}},{key:"showPlaceholder",value:function(){return m("div",{class:this.multiTierWrapperCss||""},this.buildList([{id:1,children:[{id:2,children:[]},{id:3,children:[]},{id:4,children:[]}]}],!0,!0))}},{key:"buildList",value:function(){var e=this,t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},a=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],n=arguments.length>2&&void 0!==arguments[2]&&arguments[2];return m("ul",{class:"py-5 relative transition-all"},t.map((function(t){return m("li",{class:"float-left pt-5 pb-0 text-center list-none relative px-5 transition-all"},m("span",{class:n?e.placeholderNodeCss:a?e.nodeCss:""},t.name||""),t.children&&t.children.length>0?e.buildList(t.children,a,n):"")})))}},{key:"emptyText",value:function(){return m("p",{class:"py-8 text-sm text-center text-gray-500"},_x("No children found.","empty chain message","affiliate-for-woocommerce"),this.params.editURL?m("[",null," "+_x("View/set parent affiliate from","Manage the parent affiliate","affiliate-for-woocommerce")," ",m("a",{href:this.params.editURL||"",target:"_blank",class:"text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline numeric"},_x("affiliate's user profile","a href for affiliate admin user profile","affiliate-for-woocommerce")),"."):"")}},{key:"view",value:function(){return m("div",{class:"afwc-multi-tier-tree"},m("section",{class:"my-5 overflow-x-auto"},o.flags.loading?this.showPlaceholder():o.details&&Object.entries(o.details).length>0?m("div",{class:this.multiTierWrapperCss||""},this.buildList(o.details,!0)):this.emptyText()))}}])&&l(t.prototype,a),n&&l(t,n),e}();function u(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var f=function(){function e(t){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.affiliateId=afwcDashboardParams.affiliate_id||0}var t,a,n;return t=e,(a=[{key:"view",value:function(){return this.affiliateId?m("article",{class:"w-full"},m(c,{id:this.affiliateId})):""}}])&&u(t.prototype,a),n&&u(t,n),e}()},function(e,t,a){"use strict";a.r(t),function(e){a(5);var t=a(1),n=a(2);e.__=wp.i18n.__,e._x=wp.i18n._x,e.sprintf=wp.i18n.sprintf;var i=document.getElementsByClassName("afw-campaigns")[0]||"";i&&m.mount(i,{view:function(e){return[m(t.a,e.attrs)]}});var r=document.getElementsByClassName("afw-multi-tier")[0]||"";r&&m.mount(r,{view:function(e){return m(n.a,e.attrs)}})}.call(this,a(4))},function(e,t){var a;a=function(){return this}();try{a=a||new Function("return this")()}catch(e){"object"==typeof window&&(a=window)}e.exports=a},function(e,t,a){}]);