(function(a,d,n){requirejs.config({baseUrl:"js/",shim:{"mylibs/charts/jquery.flot.orderBars":["mylibs/charts/jquery.flot"],"mylibs/charts/jquery.flot.time":["mylibs/charts/jquery.flot"],"mylibs/charts/jquery.flot.pie":["mylibs/charts/jquery.flot"],"mylibs/charts/jquery.flot.resize":["mylibs/charts/jquery.flot"],"mylibs/syntaxhighlighter/shAutoloader":["mylibs/syntaxhighlighter/shCore"],"mylibs/fullstats/jquery.sparkline":["mylibs/fullstats/jquery.css-transform","mylibs/fullstats/jquery.animate-css-rotate-scale"]},waitSeconds:15});var j=a("#main"),k=a("#toolbar"),l=a("aside");(function(b){var c=b.document;if(Modernizr.touch&&!location.hash&&b.addEventListener){window.scrollTo(0,1);var e=1,g=function(){return b.pageYOffset||c.compatMode==="CSS1Compat"&&c.documentElement.scrollTop||c.body.scrollTop||0},f=setInterval(function(){if(c.body){clearInterval(f);e=g();b.scrollTo(0,e===1?0:1)}},15);b.addEventListener("load",function(){setTimeout(function(){if(g()<20)b.scrollTo(0,e===1?0:1)},0)})}})(window);a("html").attr("manifest")&&window.addEventListener("load",function(){try{var b=window.applicationCache;b.update();b.status==b.UPDATEREADY&&b.swapCache();b.addEventListener("updateready",function(){if(window.applicationCache.status==window.applicationCache.UPDATEREADY){console.info("Updating Application Cache :)");window.applicationCache.swapCache();if(a.jGrowl)a.jGrowl(d.config.lang.appcache.PLEASE_RELOAD,{header:d.config.lang.appcache.PLEASE_RELOAD_TITLE});else confirm(d.config.lang.appcache.PROMT_RELOAD)&&window.location.reload()}},false)}catch(c){console.error(c)}},false);d.loaded(function(){j=a("#main");k=a("#toolbar");l=a("aside")});d.loaded(function(){k.find("div.right").find("a").has("span:not(.icon)").addClass("with_red");if(a.browser.mozilla)a("html").addClass("moz");else a.browser.webkit&&a("html").addClass("webkit");var b=!!a.browser.msie,c=parseInt(a.browser.version);if(b){a("html").addClass("ie");c==9&&a("html").addClass("ie9")}});var o=_.once(function(){a(document).on("click",".alert:not(.sticky) .close",function(){a(this).parent().slideUp(d.config.fxSpeed)});a(document).on("click",".alert.closeEverywhere:not(.sticky)",function(){a(this).slideUp(d.config.fxSpeed)});a(window).on("resize scroll",function(){a(".ui-dialog").filter(":visible").position({my:"center",at:"center",of:window})})});d.register("content",d.loaded,function(){var b=a("#content");a("h1:not(:has(span))").each(function(){a(this).wrapInner("<span ></20>")});b.find(".box:has(.header a.menu):not(.ready)").each(function(){var c=a(this),e=c.find(".header").find("a.menu"),g=e.next("menu");e.on({mousedown:function(){a(this).addClass("active")},mouseup:function(){a(this).removeClass("active")},click:function(){g.fadeToggle(d.config.fxSpeed);e.toggleClass("open")}});g.find("a").on({mousedown:function(){a(this).addClass("active")},mouseup:function(){a(this).removeClass("active")},click:function(){window.location=this.href;return false},dragstart:function(){return false}}).filter(":has(.icon)").addClass("with-icon");c.addClass("ready")});b.data("sort")&&!(Modernizr.touch&&!d.config.contents.sortableOnTouchDevices)&&b.sortable({handle:".header",items:b.find(".box").parent(),distance:5,tolerance:"pointer",placeholder:"placeholder",forcePlaceholderSize:true,forceHelperSize:true});a("#content .accordion").not(".toggle").each(function(){a(this).accordion()});a("#content .accordion.toggle").each(function(){a(this).multiAccordion()});a("#content .tabbedBox").not(".ready").tabbedBox().addClass("ready");a("#content .vertical-tabs").not(".ready").tabbedBox({header:a(".right-sidebar"),content:a(".vertical-tabs")}).addClass("ready");a(".alert:not(:has(span.close))").not(".sticky").find(".icon").after(a("<span>").addClass("close").text("x"));o()});d.loaded(function(){var b=a("nav").clone();b.addClass("phone").children("ul").removeClass("collapsible accordion").end().find(".badge").remove().end().find(".icon").remove().end().find("img").remove().end().insertAfter("header");var c=b.children("ul").children("li").has("ul").children("a");c.addClass("with-sub");c.click(function(){var e=a(this),g=e.next();if(g.is("ul")){if(g.is(":visible"))g.slideUp(d.config.fxSpeed,function(){e.parent().toggleClass("open")});else{c.next().not(g).slideUp(d.config.fxSpeed);g.slideDown(d.config.fxSpeed);e.parent().toggleClass("open")}return false}});a("#toolbar").find(".phone").find(".navigation").click(function(){b.fadeToggle(d.config.fxSpeed)})});d.loaded(function(){if(window.devicePixelRatio&&window.devicePixelRatio>1&&Modernizr.touch){var b=a(".phone-title");b.is("img")||(b=b.find("img"));if(!(b.length==0||b.data("no2x"))){var c=b[0].src;b.error(function(){b.attr("src",c)});b.attr("src",c.replace(".png","@2x.png"));b.one("error",function(){b.attr("src",c.replace("@2x.png",".png"))})}}});d.loaded(function(){var b=l.find("nav").children("ul");b.initMenu();b.find("li").find("ul").find("li").has(".icon").addClass("with-icon");l.css("min-height",l.find(".top").height()+l.find(".bottom").height())});var m=function(){if(a.validator){var b=a(this).parents("form").data("validator");b&&b.element(this)}};d.register("jQueryUI",["mylibs/forms/jquery.ui.datetimepicker","mylibs/forms/jquery.ui.spinner"],{wrapper:d.loaded,func:function(){a("#content,.right-sidebar").find(".ui-progressbar").not(".ready").not(".manual").each(function(){var c=a(this);c.progressbar(c.data())}).addClass("ready");a("input[type=date]").each(function(){var c=a(this);if(a.browser.webkit)c[0].type="text";c.datepicker(c.data())});a("input[type=datetime]").each(function(){a(this).datetimepicker(a(this).data()).blur(m)});a("input[type=time]").each(function(){var c=a(this);if(a.browser.webkit)c[0].type="text";a(this).timepicker(a.extend(true,{ampm:a(this).data("timeformat")==12},a(this).data())).blur(m)});d.ready(function(){a(".hasDatepicker").each(function(){var c=a(this);c.val()&&c.datepicker("setDate",c.val())});setTimeout(function(){a(".hasDatepicker").datepicker("refresh")},3E3)});var b={onselect:_.debounce(function(c,e){(e.input||e.$input).data("mirror").val(c)},300),setup:function(c){var e=a("<input>",{id:c.data("id"),"class":"mirror",name:c.data("name"),required:c.attr("required")||"false"}).hide().insertAfter(c);c.data("mirror",e)}};a("div[data-type=date]").each(function(){var c=a(this);b.setup(c);c.datepicker(a.extend(true,{onSelect:b.onselect},c.data()))});a("div[data-type=datetime]").each(function(){var c=a(this);b.setup(c);c.datetimepicker(a.extend(true,{onSelect:b.onselect},c.data()))});a("div[data-type=time]").each(function(){var c=a(this);b.setup(c);c.timepicker(a.extend(true,{onSelect:b.onselect,ampm:a(this).data("timeformat")==12},c.data()))});a("input[data-type=range]").mslider();(function(){var c=a("input.eq[data-type=range]").next(),e=c.length+1;c.each(function(){a(this).css("z-index",e--)})})();a("[data-type=autocomplete]").each(function(){var c=a(this);c.attr("autocomplete","off");c.autocomplete({source:c.data("data")||c.data("source"),disabled:!!c.attr("disabled"),minLength:c.data("minlength")||1,position:{my:"top",at:"bottom",offset:"0 10",collision:"none"},select:m})})},init:function(){a.extend(a.ui.dialog.prototype.options,{minWidth:350,resizable:false,show:{effect:"fade",duration:800},hide:{effect:"fade",duration:800}});a.extend(a.datepicker,{_checkOffset:function(c,e){return e}});a.extend(a.datepicker._defaults,{showButtonPanel:true,showOtherMonths:true,closeText:"Close"});var b={onSelect:m,onClose:m};a.extend(a.datepicker._defaults,b);a.extend(a.timepicker._defaults,b);a(window).resize(_.debounce(function(){a("[data-type=autocomplete]").each(function(){var c=a(this);c.data("autocomplete").menu.element.width(c.outerWidth()).position({my:"top",at:"bottom",offset:"0 10",collision:"none",of:c})})},300))},check:function(){return a(".ui-progressbar").length!=0||a("input[type*=date]").length!=0||a("input[type*=time]").length!=0||a("div[data-type*=date]").length!=0||a("div[data-type*=time]").length!=0||a("input[data-type=range]").length!=0||a("[data-type=autocomplete]").length!=0}});d.register("calendar",["mylibs/forms/jquery.fullcalendar"],{wrapper:d.loaded,func:function(){a.fullCalendar.setDefaults({header:{left:"prev,next",center:"title",right:"month,agendaWeek,agendaDay"}})},check:function(){return false}});d.register("charts",["mylibs/charts/jquery.flot","mylibs/charts/jquery.flot.orderBars","mylibs/charts/jquery.flot.pie","mylibs/charts/jquery.flot.resize"],{wrapper:d.loaded,func:function(){a(".chart").not(".manual").not(".ready").chart();a(".chart").not(".manual").addClass("ready")},check:function(){return a(".chart").length}});d.register("fullstats",["mylibs/fullstats/jquery.sparkline"],{wrapper:d.loaded,func:function(){a(".full-stats").fullstats();a(".full-stats.equalHeight").equalHeight()},check:function(){return a(".full-stats").length}});d.register("forms",["mylibs/forms/jquery.checkbox","mylibs/forms/jquery.chosen","mylibs/forms/jquery.fileinput","mylibs/forms/jquery.ellipsis","mylibs/forms/jquery.autosize","mylibs/forms/jquery.pwdmeter","mylibs/forms/jquery.maskedinput","mylibs/forms/jquery.cleditor"],{wrapper:d.loaded,init:function(){a(window).on("fontsloaded",function(){d.utils.forms.resize()});a(window).width();a(window).on("resize",_.debounce(function(){d.utils.forms.resize()},200));var b=function(){a("#content,#login,.ui-dialog:not(:has(#settings))").find("form").each(function(){var c=a(this),e=c.find(".row"),g=e.children("label");e=e.children("div");g.css("width","");e.css("height","");e.css("margin-left","");g.equalWidth();e.css("margin-left",g.width()+parseInt(g.css("margin-right")));g.each(function(){var f=a(this),h=f.next();f=f.outerHeight();var i=h.height();f>i&&h.height(f)});!c.parents(".box").length&&!c.is(".box")&&!c.is(".no-style")&&c.addClass("no-box");c.find(":password.meter").each(function(){a(this).data("reposition")&&a(this).data("reposition")()})})};b();d.utils.forms={resize:b}},func:function(){a("input:checkbox").not(".ready").checkbox({cls:"checkbox",empty:"img/elements/checkbox/empty.png"}).addClass("ready");a("input:radio").not(".ready").checkbox({cls:"radiobutton",empty:"img/elements/checkbox/empty.png"}).addClass("ready");a("input:checkbox,input:radio").off("disable enable").on({disable:function(){this.wrapper&&this.wrapper.next().addClass("disabled")},enable:function(){this.wrapper&&this.wrapper.next().removeClass("disabled")}}).filter(":disabled").trigger("disable");a("select").not(".dualselects").not(".dualselect select").each(function(){var b=a(this);b.chosen({disable_search_threshold:b.hasClass("search")?0:Number.MAX_VALUE,allow_single_deselect:true,width:b.data("width")||"100%"})});a(".chzn-done").not(".ready").on("change.mango",function(){var b=a(this).parents("form").validate();b&&b.element(a(this))}).each(function(){var b=a(this),c=b.parents("form");c.on("reset",function(){b[0].selectedIndex=-1;b.trigger("liszt:updated")});c.data("chzn-reset",true)}).addClass("ready");Modernizr.touch||a("select.dualselects").dualselect();a("input:file").not(".ready").fileInput().addClass("ready");a("input[data-type=spinner]").each(function(){var b=a(this),c=b.data();if(c.format){c.numberformat=c.format;c.format=n}b.spinner(c)});a("textarea.editor").each(function(){var b=a(this),c=b.hasClass("full");b.cleditor({width:c?"auto":"100%",height:"250px",bodyStyle:"margin: 10px; font: 12px Arial,Verdana; cursor:text",useCSS:true});c&&b.not(".full").parents(".cleditorMain").addClass("full")});a("textarea").not(".nogrow").not(".editor").not(".ready").autosize().addClass("ready");a(".maskDate").mask("99/99/9999");a(".maskPhone").mask("(999) 999-9999");a(".maskPhoneExt").mask("(999) 999-9999? x99999");a(".maskIntPhone").mask("+33 999 999 999");a(".maskTin").mask("99-9999999");a(".maskSsn").mask("999-99-9999");a(".maskProd").mask("a*-999-a999");a(".maskPo").mask("PO: aaa-999-***");a(".maskPct").mask("99%");a(".maskCustom").each(function(){a(this).mask(a(this).data("mask")||"")});d.utils.forms.resize();a("form").each(function(){var b=a(this),c=b.find("label.inline");b=b.map(function(){var e=a(this);return e.parents(".shortcuts").length?e.parents(".content").parents("li").children("div")[0]:e.parents(".popup")[0]});b.show();c.each(function(){var e=a(this),g=a("#"+e.attr("for"));g.css("padding-left",e.outerWidth(true));a(window).on("fontsloaded show",function(){g.css("padding-left",e.outerWidth(true))});var f=a.browser.msie&&parseInt(a.browser.version)==8;f&&e.css("position","absolute");e.position({my:"left center",at:"left center",of:g,using:function(h){e.css("top",h.top);f&&e.css("top",h.top*2)}})});b.hide()})},check:function(){return a("input:checkbox,input:radio").length||a("select").length||a("input:file").length||a("input[data-type=spinner]").length||a("textarea").length||a("input:password.meter").length||a("form").length}});d.loaded(function(){d.ready(function(){a("#animprog").progressbar({fx:{animate:true,duration:5,start:new Date((new Date).getTime()+5E3)}})})});d.ready(function(){a("a.button.disabled").click(function(){return false})});d.register("validation",{wrapper:d.ready,func:function(){a("form.validate").not(".ready").each(function(){a(this).validate({submitHandler:function(b){a(this).data("submit")?a(this).data("submit")():b.submit()}})});a("form.validate").on("reset",function(){var b=a(this);b.validate().resetForm();b.find("label.error").remove().end().find(".error-icon").remove().end().find(".valid-icon").remove().end().find(".valid").removeClass("valid").end().find(".customfile.error").removeClass("error")}).addClass("ready");if(!("form"in document.createElement("input"))){a("input:submit").each(function(){var b=a(this);b.attr("form")&&b.click(function(){a("#"+b.attr("form")).submit()})});a("input:reset").each(function(){var b=a(this);b.attr("form")&&b.click(function(){a("#"+b.attr("form"))[0].reset()})})}},init:function(){a.validator.addMethod("strongpw",function(b){return a.pwdStrength(b)>d.config.forms.pwdmeter.okayThreshold},"Your password is insecure");a.validator.addMethod("checked",function(b,c){return!!a(c)[0].checked},"You have to select this option");a.validator.setDefaults({ignore:":hidden:not(select.chzn-done):not(input.mirror):not(:checkbox):not(:radio):not(.dualselects),.ignore",success:function(b){a(b).prev().filter(".error-icon").removeClass("error-icon").addClass("valid-icon");a(b).prev(".customfile").removeClass("error")},errorPlacement:function(b,c){if(c.hasClass("customfile-input-hidden"))b.insertAfter(c.parent().addClass("error"));else if(c.is(":password.meter")||c.is("textarea")||c.is(".ui-spinner-input")||c.is("input.mirror"))b.insertAfter(c);else if(c.is(":checkbox")||c.is(":radio"))c.is(":checkbox")?b.insertAfter(c.next().next()):b.insertAfter(a("[name="+c[0].name+"]").last().next().next());else if(c.is("select.chzn-done")||c.is(".dualselects"))b.insertAfter(c.next());else{b.insertAfter(c);a('<div class="error-icon icon" ></div>').insertAfter(c).position({my:"right",at:"right",of:c,offset:"-5 0",overflow:"none",using:function(e){var g=a(this).offsetParent().outerWidth()-e.left-a(this).outerWidth();a(this).css({left:"",right:g,top:e.top})}})}},showErrors:function(b,c){var e=this;this.defaultShowErrors();c.forEach(function(g){var f=a(g.element);g=e.errorsFor(g.element);if(f.data("errorType")=="inline"||f.is("select")||f.is("textarea")||f.hasClass("customfile-input-hidden")||f.is("input.mirror")||f.is(":checkbox")||f.is(":radio")||f.is(".dualselect")){var h;if(f.is("select"))h=f.next();else if(f.is(":checkbox")||f.is(":radio")){h=f.is(":checkbox")?f.next():a("[name="+f[0].name+"]").last().next().next();g.css("display","block")}else h=f.is("input.mirror")?f.prev():f;g.addClass("inline").position({my:"left top",at:"left bottom",of:h,offset:"0 5",collision:"none"});f.is(":checkbox")&&f.is(":radio")||g.css("left","")}else g.position({my:"right top",at:"right bottom",of:f,offset:"1 8",using:function(i){var p=a(this).offsetParent().outerWidth()-i.left-a(this).outerWidth();a(this).css({left:"",right:p,top:i.top})}});g.prev().filter(".valid-icon").removeClass("valid-icon").addClass("error-icon");if(f.hasClass("noerror")){g.hide();f.next(".icon").hide()}});this.successList.forEach(function(g){e.errorsFor(g).hide()})}})}});d.register("fixes",["mylibs/forms/jquery.placeholder"],{wrapper:d.ready,func:function(){var b=!!a.browser.msie,c=parseInt(a.browser.version);if(b&&c<8){a("input[type=search]").addClass("search");a('input[type="search"] + ul.searchResults').addClass("in_toolbar")}b&&c==9&&a("button, input:submit, input:reset, input:button").addClass("gradient");b&&c<9&&k.find("div.right").find("a").has("span.icon").addClass("has_icon");b&&c==9&&l.find(".badge").addClass("gradient");a("input, textarea").placeholder()},check:function(){return!!a.browser.msie||!Modernizr.input.placeholder}});d.ready(function(){var b=j.find("section.toolbar").find("div.user"),c=j.find("ul.shortcuts").children("li").has("div"),e=k.children().find("ul").find("li").has("div.popup"),g=j.find(".box").find(".header").find("menu");a("html").click(function(f){var h=a(f.target);if(!(h.hasClass("ui-widget-overlay")||h.hasClass("ui-dialog ui-widget")||h.parents(".ui-dialog").length)){f.target!==b[0]&&!b.doesHave(f.target)&&b.hasClass("clicked")&&b.find("ul").slideUp(d.config.fxSpeed,function(){b.removeClass("clicked")});c.doesHave(f.target)||c.removeClass("active").children("div:visible").fadeOut(d.config.fxSpeed);e.doesHave(f.target)||e.removeClass("active").children("div.popup:visible").fadeOut(d.config.fxSpeed);g.each(function(){var i=a(this);if(i.is(":visible")&&f.target!=i.prev()[0]&&!i.doesHave(f.target)){i.prev().removeClass("open");i.fadeOut(d.config.fxSpeed)}})}})});d.register("tooltips",["mylibs/tooltips/jquery.tipsy"],{func:function(){a(".tooltip").not(".ready").each(function(){var b=a(this),c=b.data("gravity")||a.fn.tipsy.autoNS,e=b.data("anim")||true;b.tipsy({gravity:c,fade:e})}).addClass("ready")},init:function(){a.fn.tipsy.defaults.opacity=1},check:function(){return a(".tooltip").length}});d.ready(function(){j.find("section.toolbar").find("div.user").click(function(){var b=a(this);if(b.hasClass("clicked"))b.find("ul").slideUp(d.config.fxSpeed,function(){b.removeClass("clicked")});else{b.find("ul").slideDown(d.config.fxSpeed);b.addClass("clicked")}}).find("ul").click(d.utils.noBubbling)});d.ready(function(){var b=j.find("ul.shortcuts").children("li").has("div").each(function(){var c=a(this),e=c.children("div");c.click(function(){b.not(c).children("div").fadeOut(d.config.fxSpeed,function(){b.not(c).removeClass("active")});e.fadeToggle(d.config.fxSpeed,function(){e.trigger("show")});c.toggleClass("active")});e.click(d.utils.noBubbling)})});d.ready(function(){var b=k.children().find("ul").find("li").has("div.popup").each(function(){var e=a(this),g=e.children("div");e.click(function(){if(e.hasClass("disabled"))return false;b.not(e).children("div").fadeOut(d.config.fxSpeed,function(){b.not(e).removeClass("active")});a("html").hasClass("lt-ie9")&&g.is(":hidden")&&g.show().css({left:0}).position({my:"top",at:"bottom",of:e,offset:"0 15",using:function(f){g.css({left:f.left,top:37})}}).hide();g.fadeToggle(d.config.fxSpeed,function(){g.trigger("show")});e.toggleClass("active");return false});g.click(d.utils.noBubbling)}),c=a(".mail").has(".text");c.on("click","li",function(){c.find(".text:visible").slideUp(d.config.fxSpeed/2);a(this).find(".text:hidden").slideToggle(d.config.fxSpeed/2)}).on("hover","li",function(){d.isOldIE&&a(this).toggleClass("hover");a(this).toggleClass("normal")}).find(".text").hover(function(){d.isOldIE&&a(this).toggleClass("hover");a(this).parent("li").toggleClass("normal")}).click(d.utils.noBubbling);b.each(function(){var e=a(this);e.find(".popup").show().position({my:"top",at:"bottom",of:e,offset:"0 15"}).hide()})});d.ready(function(){if(d.config.scollToTop){var b=a("<a>",{href:"#top",id:"gotoTop"}).appendTo("body"),c=a(window);c.scroll(_.debounce(function(){jQuery.support.hrefNormalized||b.css({position:"absolute",top:c.scrollTop()+c.height()-settings.ieOffset});c.scrollTop()>=1?b.fadeIn():b.fadeOut()},300)).scroll();b.click(function(){a("html, body").animate({scrollTop:0});return false})}});d.register("notifications",["mylibs/jquery.jgrowl"],{func:function(){a.jGrowl.defaults.life=8E3;a.jGrowl.defaults.pool=5},check:function(){return false}});d.register("syntaxhighlighter",["mylibs/syntaxhighlighter/shCore","mylibs/syntaxhighlighter/shAutoloader"],{wrapper:d.loaded,func:function(){SyntaxHighlighter.autoload=function(b){$$.ready(function(){SyntaxHighlighter.autoloader.apply(this,b);SyntaxHighlighter.all()})};SyntaxHighlighter.autoload(function(b,c){for(var e=[],g=0;g<c.length;++g){var f=c[g].slice();f[f.length-1]=b+f[f.length-1];e.push(f)}return e}("js/mylibs/syntaxhighlighter/",[["applescript","shBrushAppleScript.js"],["actionscript3","as3","shBrushAS3.js"],["bash","shell","shBrushBash.js"],["coldfusion","cf","shBrushColdFusion.js"],["cpp","c","shBrushCpp.js"],["c#","c-sharp","csharp","shBrushCSharp.js"],["css","shBrushCss.js"],["delphi","pascal","shBrushDelphi.js"],["diff","patch","pas","shBrushDiff.js"],["erl","erlang","shBrushErlang.js"],["groovy","shBrushGroovy.js"],["java","shBrushJava.js"],["jfx","javafx","shBrushJavaFX.js"],["js","jscript","javascript","shBrushJScript.js"],["perl","pl","shBrushPerl.js"],["php","shBrushPhp.js"],["text","plain","shBrushPlain.js"],["py","python","shBrushPython.js"],["ruby","rails","ror","rb","shBrushRuby.js"],["sass","scss","shBrushSass.js"],["scala","shBrushScala.js"],["sql","shBrushSql.js"],["vb","vbnet","shBrushVb.js"],["xml","xhtml","xslt","html","shBrushXml.js"]]))},check:function(){return a("pre").filter(function(){return _.contains(a(this).attr("class"),"brush:")}).length}});d.ready(function(){var b=a("#login"),c=b.find(".login-messages");c.height(c.height());c.children().css("position","absolute");b.find("form").validationOptions({invalidHandler:function(){c.find(".welcome").fadeOut();c.find(".failure").fadeIn()}})});d.ready(function(){var b=[k.find("li"),a("nav").find("li"),a("section.toolbar").find("li").find("a"),a("header").find("img"),a("div.avatar").find("img"),a("ul.shortcuts").find("li"),a("a.button"),a(".profile").find(".avatar").children(),a(".messages").find(".buttons").children(),a(".full-stats").find(".stat"),a(".ui-slider"),a(".checkbox"),a(".radiobutton"),a("#gotoTop"),a(".dataTables_paginate"),a(".avatar"),a("header a"),a(".tabletools").find("a")];a.each(b,function(){a(this).on("dragstart",function(c){c.preventDefault()})})});d.ready(function(){a("#loading").fadeOut(d.config.fxSpeed);a("#loading-overlay").delay(100+d.config.fxSpeed).fadeOut(d.config.fxSpeed*2);setTimeout(function(){a("#lock-screen").length&&a("#btn-lock").length&&!d.utils.isPhone&&d.registry.jQueryUI(function(){d.lock()})},d.config.fxSpeed)});d.ready(function(){if(d.config.preload.enabled){d.utils.preload(["img/layout/navigation/arrow-active.png","img/layout/navigation/arrow-hover.png","img/layout/navigation/arrow.png","img/layout/navigation/bg-current.png","img/layout/navigation/bg-active.png","img/layout/navigation/bg-hover.png","img/layout/navigation/bg-normal.png"]);d.utils.preload(["img/layout/sidebar/bg-right.png","img/layout/sidebar/bg.png","img/layout/sidebar/divider.png","img/layout/sidebar/shadow-right.png","img/layout/sidebar/shadow.png","img/layout/sidebar-right/header-bg.png","img/layout/sidebar-right/nav-bg-hover.png","img/layout/sidebar-right/nav-bg.png"]);d.utils.preload(["img/layout/toolbar/bg.png","img/layout/toolbar/buttons/bg-active.png","img/layout/toolbar/buttons/bg-disabled.png","img/layout/toolbar/buttons/bg-hover.png","img/layout/toolbar/buttons/bg-red-active.png","img/layout/toolbar/buttons/bg-red-disabled.png","img/layout/toolbar/buttons/bg-red-hover.png","img/layout/toolbar/buttons/bg-red.png","img/layout/toolbar/buttons/bg.png","img/layout/toolbar/buttons/divider.png"]);d.utils.preload(["img/layout/footer/divider.png"]);d.utils.preload(["img/layout/bg.png","img/layout/content/box/actions-bg.png","img/layout/content/box/bg.png","img/layout/content/box/header-bg.png","img/layout/content/box/menu-active-bg.png","img/layout/content/box/menu-arrow.png","img/layout/content/box/menu-bg.png","img/layout/content/box/menu-item-bg-hover.png","img/layout/content/box/menu-item-bg.png","img/layout/content/box/tab-hover.png","img/layout/content/toolbar/bg-shortcuts.png","img/layout/content/toolbar/bg.png","img/layout/content/toolbar/divider.png","img/layout/content/toolbar/popup-arrow.png","img/layout/content/toolbar/popup-header.png","img/layout/content/toolbar/user/arrow-normal.png","img/layout/content/toolbar/user/avatar-bg.png","img/layout/content/toolbar/user/avatar.png","img/layout/content/toolbar/user/bg-hover.png","img/layout/content/toolbar/user/bg-menu-hover.png","img/layout/content/toolbar/user/counter.png"]);d.utils.preload(["img/elements/alert-boxes/bg-error.png","img/elements/alert-boxes/bg-information.png","img/elements/alert-boxes/bg-note.png","img/elements/alert-boxes/bg-success.png","img/elements/alert-boxes/bg-warning.png","img/elements/alert-boxes/error.png","img/elements/alert-boxes/information.png","img/elements/alert-boxes/note.png","img/elements/alert-boxes/success.png","img/elements/alert-boxes/warning.png"]);d.utils.preload(["img/elements/breadcrumb/bg-active.png","img/elements/breadcrumb/bg-hover.png","img/elements/breadcrumb/divider-active.png","img/elements/breadcrumb/divider-hover.png"]);d.utils.preload(["img/elements/headerbuttons/bg-active.png","img/elements/headerbuttons/bg-hover.png"]);d.utils.preload(["img/elements/autocomplete/el-bg-hover.png"]);d.utils.preload(["img/elements/calendar/arrow-hover-bg.png"]);d.utils.preload(["img/elements/charts/hover-bg.png"]);d.utils.preload(["img/elements/messages/button-active-bg.png","img/elements/messages/button-hover-bg.png"]);d.utils.preload(["img/elements/messages/button-active-bg.png","img/elements/messages/button-hover-bg.png"]);d.utils.preload(["img/elements/mail/actions-bg.png","img/elements/mail/button-bg-disabled.png","img/elements/mail/button-bg-hover.png","img/elements/mail/button-bg.png","img/elements/mail/button-red-bg-hover.png","img/elements/mail/button-red-bg.png","img/elements/mail/button-red-disabled.png","img/elements/mail/hover-bg.png","img/elements/mail/mail.png","img/elements/mail/text-arrow.png","img/elements/mail/text-bg.png"]);d.utils.preload(["img/elements/fullstats/list/hover-bg.png","img/elements/fullstats/simple/a-active.png","img/elements/fullstats/simple/a-hover.png"]);d.utils.preload(["img/elements/checkbox/checked-active.png","img/elements/checkbox/checked-disabled.png","img/elements/checkbox/checked-hover.png","img/elements/checkbox/checked-normal.png","img/elements/checkbox/unchecked-active.png","img/elements/checkbox/unchecked-disabled.png","img/elements/checkbox/unchecked-hover.png","img/elements/checkbox/unchecked-normal.png"]);d.utils.preload(["img/elements/radiobutton/checked-active.png","img/elements/radiobutton/checked-disabled.png","img/elements/radiobutton/checked-hover.png","img/elements/radiobutton/checked-normal.png","img/elements/radiobutton/unchecked-active.png","img/elements/radiobutton/unchecked-disabled.png","img/elements/radiobutton/unchecked-hover.png","img/elements/radiobutton/unchecked-normal.png"]);d.utils.preload(["img/elements/forms/icon-error.png","img/elements/forms/icon-success.png","img/elements/forms/tooltip-error-arrow.png","img/elements/forms/tooltip-error.png"]);d.utils.preload(["img/elements/profile/change-active-bg.png","img/elements/profile/change-hover-bg.png"]);d.utils.preload(["img/elements/search/arrow.png","img/elements/search/glass.png","img/elements/search/list-hover.png","img/elements/search/loading.gif"]);d.utils.preload(["img/elements/select/bg-active.png","img/elements/select/bg-hover.png","img/elements/select/bg-right-hover.png","img/elements/select/list-hover-bg.png"]);d.utils.preload(["img/elements/settings/header-bg.png","img/elements/settings/header-current-bg.png","img/elements/settings/header-hover-bg.png","img/elements/settings/seperator-current-left.png","img/elements/settings/seperator-current-right.png","img/elements/settings/seperator.png"]);d.utils.preload(["img/elements/slide-unlock/lock-slider.png"]);d.utils.preload(["img/elements/spinner/arrow-down-active.png","img/elements/spinner/arrow-down-hover.png","img/elements/spinner/arrow-up-active.png","img/elements/spinner/arrow-up-hover.png","img/elements/table/pagination/active.png","img/elements/table/pagination/disabled.png","img/elements/table/pagination/hover.png","img/elements/table/toolbar/hover.png","img/elements/table/sorting-asc.png","img/elements/table/sorting-desc.png","img/elements/table/sorting.png"]);d.utils.preload(["img/elements/tags/bg.png","img/elements/tags/left.png"]);d.utils.preload(["img/elements/to-top/active.png","img/elements/to-top/hover.png","img/elements/to-top/normal.png"]);d.utils.preload(["img/elements/tooltips/bg.png"]);d.utils.preload(["img/external/chosen-sprite.png"]);d.utils.preload(["img/external/editor/buttons.gif","img/external/editor/toolbar.gif"]);d.utils.preload(["img/external/jquery-ui/ui-bg_flat_0_000000_40x100.png","img/external/jquery-ui/ui-bg_flat_30_000000_40x100.png","img/external/jquery-ui/ui-bg_flat_65_e3e3e3_40x100.png","img/external/jquery-ui/ui-bg_flat_75_ffffff_40x100.png","img/external/jquery-ui/ui-bg_glass_55_fbf9ee_1x400.png","img/external/jquery-ui/ui-bg_highlight-hard_100_f0f0f0_1x100.png","img/external/jquery-ui/ui-bg_highlight-soft_100_e8e8e8_1x100.png","img/external/jquery-ui/ui-bg_highlight-soft_75_b3bfcb_1x100.png","img/external/jquery-ui/ui-bg_inset-soft_95_fef1ec_1x100.png","img/external/jquery-ui/ui-icons_222222_256x240.png","img/external/jquery-ui/ui-icons_2e83ff_256x240.png","img/external/jquery-ui/ui-icons_3a4450_256x240.png","img/external/jquery-ui/ui-icons_454545_256x240.png","img/external/jquery-ui/ui-icons_888888_256x240.png","img/external/jquery-ui/ui-icons_cd0a0a_256x240.png"]);d.utils.preload(["img/jquery-ui/accordion-header-active.png","img/jquery-ui/accordion-header-hover.png","img/jquery-ui/accordion-header.png","img/jquery-ui/datepicker/arrow-left.png","img/jquery-ui/datepicker/arrow-right.png","img/jquery-ui/datepicker/button-bg.png","img/jquery-ui/datepicker/button-hover-bg.png","img/jquery-ui/datepicker/button-seperator.png","img/jquery-ui/datepicker/day-current.png","img/jquery-ui/datepicker/day-hover.png","img/jquery-ui/datepicker/days-of-week-bg.png","img/jquery-ui/datepicker/header-bg.png","img/jquery-ui/datepicker/time-bg.png","img/jquery-ui/datepicker/top-arrow.png","img/jquery-ui/dialog-titlebar-close-hover.png","img/jquery-ui/dialog-titlebar.png","img/jquery-ui/loading.gif","img/jquery-ui/progressbar/bg.png","img/jquery-ui/progressbar/fill-blue-small.png","img/jquery-ui/progressbar/fill-blue.gif","img/jquery-ui/progressbar/fill-blue.png","img/jquery-ui/progressbar/fill-grey.gif","img/jquery-ui/progressbar/fill-grey.png","img/jquery-ui/progressbar/fill-orange-small.png","img/jquery-ui/progressbar/fill-orange.gif","img/jquery-ui/progressbar/fill-orange.png","img/jquery-ui/progressbar/fill-red-small.png","img/jquery-ui/progressbar/fill-red.gif","img/jquery-ui/progressbar/fill-red.png","img/jquery-ui/slider/bg-range.png","img/jquery-ui/slider/bg.png","img/jquery-ui/slider/disabled-bg-range.png","img/jquery-ui/slider/disabled-bg.png","img/jquery-ui/slider/disabled-picker.png","img/jquery-ui/slider/disabled-vertical-bg-range.png","img/jquery-ui/slider/disabled-vertical-bg.png","img/jquery-ui/slider/disabled-vertical-picker.png","img/jquery-ui/slider/picker.png","img/jquery-ui/slider/vertical-bg-range.png","img/jquery-ui/slider/vertical-bg.png","img/jquery-ui/slider/vertical-picker.png"])}})})(jQuery,$$);