/*! Thrive Leads - The ultimate Lead Capture solution for wordpress - 2022-06-29
* https://thrivethemes.com 
* Copyright (c) 2022 * Thrive Themes */

var ThriveLeadsInconclusive=ThriveLeadsInconclusive||{};ThriveLeadsInconclusive.inconclusive_tests=ThriveLeadsInconclusive.inconclusive_tests||{},function(a){jQuery(document).on("click",".tve_error_inconclusive_test_notice .notice-dismiss",function(){var a=jQuery(this).parent().attr("data-test-id");ThriveLeadsInconclusive.inconclusive_tests.dismiss_notice(a,!1)}),ThriveLeadsInconclusive.inconclusive_tests.trigger_dismiss_notice=function(a){this.dismiss_notice(a,!0)},ThriveLeadsInconclusive.inconclusive_tests.dismiss_notice=function(b,c){a.ajax({method:"POST",url:ThriveLeadsInconclusive.routes.ajaxurl,data:{test_id:b,redirect:c,action:"thrive_leads_backend_ajax",route:"inconclusivetests",actionType:"delete-download",security:ThriveLeadsConst.security}}).done(function(a){1==a.result&&(1==c&&(top.location.href=a.redirect_url),jQuery("div").find('[data-test-id="'+b+'"]').remove())})}}(jQuery);