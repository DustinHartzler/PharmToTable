/*! Thrive Leads - The ultimate Lead Capture solution for wordpress - 2022-06-29
* https://thrivethemes.com 
* Copyright (c) 2022 * Thrive Themes */

var ThriveLeads=ThriveLeads||{};jQuery(function(){ThriveLeads.objects.titleChanger=new ThriveLeads.models.PageTitle({default_title:document.title}),ThriveLeads.objects.titleChanger.on("title_change",function(a){document.title=a});var a=Backbone.Router.extend({routes:{reporting:"reporting"},reporting:function(){var a=new ThriveLeads.views.Reporting;a.globalSettings=TVE_Page_Data.globalSettings;var b=new ThriveLeads.views.Breadcrumbs({collection:ThriveLeads.objects.BreadcrumbsCollection,el:"#tve-leads-breadcrumbs"});a.render(),TVE_Dash.materialize(a.$el),b.render()}});ThriveLeads.router=new a,Backbone.history.start({hashChange:!0})});