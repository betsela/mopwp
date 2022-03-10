var kt_woo_extra_isMobile={Android:function(){return navigator.userAgent.match(/Android/i)},BlackBerry:function(){return navigator.userAgent.match(/BlackBerry/i)},iOS:function(){return navigator.userAgent.match(/iPhone|iPad|iPod/i)},Opera:function(){return navigator.userAgent.match(/Opera Mini/i)},Windows:function(){return navigator.userAgent.match(/IEMobile/i)},any:function(){return kt_woo_extra_isMobile.Android()||kt_woo_extra_isMobile.BlackBerry()||kt_woo_extra_isMobile.iOS()||kt_woo_extra_isMobile.Opera()||kt_woo_extra_isMobile.Windows()}};jQuery(document).ready((function(t){function e(){t(".variations_form").each((function(){var e=t(this),i=e.find(".variations td.product_value select"),n=e.find(".single_variation_wrap"),a=!1===e.data("product_variations"),r=e.data("product_variations"),s=e.find(".variations td.value").length;e.on("click",".reset_variations",(function(){return e.find(".kad_radio_variations .selectedValue").removeClass("selectedValue"),e.find(".kad_radio_variations label").removeClass("kt_disabled "),e.find('.kad_radio_variations input[type="radio"]:checked').prop("checked",!1),!1})),e.on("reset_data",(function(){e.find(".single_variation_wrap_kad").find(".quantity").hide(),e.find(".single_variation .price").hide()})),e.on("click",".select-option",(function(t){t.preventDefault()})),e.on("change",'.variations input[type="radio"]',(function(i){var n=t(this),a,r=n.closest(".kt-radio-variation-container").find("select").first(),s=n.val();if(-1!==s.indexOf('"'))var l="value='"+s+"'";else var l='value="'+s+'"';r.trigger("focusin"),r.find("option["+l+"]").length||(e.find(".variations select").val("").change(),e.find(".kad_radio_variations .selectedValue").removeClass("selectedValue"),e.find(".kad_radio_variations label").removeClass("kt_disabled "),o&&t(".variations .kad-select").select2({minimumResultsForSearch:-1}),e.find('.kad_radio_variations input[type="radio"]:checked').prop("checked",!1),e.trigger("reset_data"),e.find('.kad_radio_variations input[type="radio"]['+l+"]").prop("checked",!0)),r.trigger("focusin").val(s).trigger("change"),e.find(".kad_radio_variations .selectedValue").removeClass("selectedValue"),e.find('.kad_radio_variations input[type="radio"]:checked').next().addClass("selectedValue")})),e.on("woocommerce_variation_has_changed",(function(){t(".kad-select").trigger("update"),a&&t(window).width()>790&&!kt_woo_extra_isMobile.any()&&o&&t(".kad-select").select2({minimumResultsForSearch:-1})})),e.on("woocommerce_variation_has_changed",(function(){if(!a){var i=[];e.find(".variations select").each((function(){t(this).trigger("focusin"),t(this).find("option.enabled").each((function(){i.push(t(this).val())}))})),e.find(".variations .kad_radio_variations").each((function(e,o){var n=t(o),a=n.data("attribute_name"),r;for(n.find("input").removeClass("attached"),n.find("input").removeClass("enabled"),n.find("label").removeClass("kt_disabled "),r=0;r<i.length;++r)-1!==i[r].indexOf('"')?n.find("input[value='"+i[r]+"']").addClass("attached enabled"):n.find('input[value="'+i[r]+'"]').addClass("attached enabled");n.find("input:not(.attached)").next().addClass("kt_disabled")}))}})),n.on("hide_variation",(function(){t(this).css("height","auto")})),i.on("select2-opening",(function(){a||(e.trigger("woocommerce_variation_select_focusin"),e.trigger("check_variations",[t(this).data("attribute_name")||t(this).attr("name"),!0]))})),t((function(){"undefined"!=typeof wc_add_to_cart_variation_params&&t(".variations_form").each((function(){t(this).find('.variations input[type="radio"]:checked').change()}))}))}))}var i="function"==typeof t().tooltip,o="function"==typeof t().select2;if(0==i){if(!kt_woo_extra_isMobile.any()){if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");!function(t){"use strict";var e=t.fn.jquery.split(" ")[0].split(".");if(e[0]<2&&e[1]<9||1==e[0]&&9==e[1]&&e[2]<1||e[0]>3)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")}(jQuery),function(t){"use strict";function e(e){return this.each((function(){var o=t(this),n=o.data("bs.tooltip"),a="object"==typeof e&&e;!n&&/destroy|hide/.test(e)||(n||o.data("bs.tooltip",n=new i(this,a)),"string"==typeof e&&n[e]())}))}var i=function(t,e){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",t,e)};i.VERSION="3.3.7",i.TRANSITION_DURATION=150,i.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},i.prototype.init=function(e,i,o){if(this.enabled=!0,this.type=e,this.$element=t(i),this.options=this.getOptions(o),this.$viewport=this.options.viewport&&t(t.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var n=this.options.trigger.split(" "),a=n.length;a--;){var r=n[a];if("click"==r)this.$element.on("click."+this.type,this.options.selector,t.proxy(this.toggle,this));else if("manual"!=r){var s="hover"==r?"mouseenter":"focusin",l="hover"==r?"mouseleave":"focusout";this.$element.on(s+"."+this.type,this.options.selector,t.proxy(this.enter,this)),this.$element.on(l+"."+this.type,this.options.selector,t.proxy(this.leave,this))}}this.options.selector?this._options=t.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},i.prototype.getDefaults=function(){return i.DEFAULTS},i.prototype.getOptions=function(e){return(e=t.extend({},this.getDefaults(),this.$element.data(),e)).delay&&"number"==typeof e.delay&&(e.delay={show:e.delay,hide:e.delay}),e},i.prototype.getDelegateOptions=function(){var e={},i=this.getDefaults();return this._options&&t.each(this._options,(function(t,o){i[t]!=o&&(e[t]=o)})),e},i.prototype.enter=function(e){var i=e instanceof this.constructor?e:t(e.currentTarget).data("bs."+this.type);return i||(i=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,i)),e instanceof t.Event&&(i.inState["focusin"==e.type?"focus":"hover"]=!0),i.tip().hasClass("in")||"in"==i.hoverState?void(i.hoverState="in"):(clearTimeout(i.timeout),i.hoverState="in",i.options.delay&&i.options.delay.show?void(i.timeout=setTimeout((function(){"in"==i.hoverState&&i.show()}),i.options.delay.show)):i.show())},i.prototype.isInStateTrue=function(){for(var t in this.inState)if(this.inState[t])return!0;return!1},i.prototype.leave=function(e){var i=e instanceof this.constructor?e:t(e.currentTarget).data("bs."+this.type);return i||(i=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,i)),e instanceof t.Event&&(i.inState["focusout"==e.type?"focus":"hover"]=!1),i.isInStateTrue()?void 0:(clearTimeout(i.timeout),i.hoverState="out",i.options.delay&&i.options.delay.hide?void(i.timeout=setTimeout((function(){"out"==i.hoverState&&i.hide()}),i.options.delay.hide)):i.hide())},i.prototype.show=function(){var e=t.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(e);var o=t.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(e.isDefaultPrevented()||!o)return;var n=this,a=this.tip(),r=this.getUID(this.type);this.setContent(),a.attr("id",r),this.$element.attr("aria-describedby",r),this.options.animation&&a.addClass("fade");var s="function"==typeof this.options.placement?this.options.placement.call(this,a[0],this.$element[0]):this.options.placement,l=/\s?auto?\s?/i,p=l.test(s);p&&(s=s.replace(l,"")||"top"),a.detach().css({top:0,left:0,display:"block"}).addClass(s).data("bs."+this.type,this),this.options.container?a.appendTo(this.options.container):a.insertAfter(this.$element),this.$element.trigger("inserted.bs."+this.type);var h=this.getPosition(),d=a[0].offsetWidth,c=a[0].offsetHeight;if(p){var u=s,f=this.getPosition(this.$viewport);s="bottom"==s&&h.bottom+c>f.bottom?"top":"top"==s&&h.top-c<f.top?"bottom":"right"==s&&h.right+d>f.width?"left":"left"==s&&h.left-d<f.left?"right":s,a.removeClass(u).addClass(s)}var v=this.getCalculatedOffset(s,h,d,c);this.applyPlacement(v,s);var g=function(){var t=n.hoverState;n.$element.trigger("shown.bs."+n.type),n.hoverState=null,"out"==t&&n.leave(n)};t.support.transition&&this.$tip.hasClass("fade")?a.one("bsTransitionEnd",g).emulateTransitionEnd(i.TRANSITION_DURATION):g()}},i.prototype.applyPlacement=function(e,i){var o=this.tip(),n=o[0].offsetWidth,a=o[0].offsetHeight,r=parseInt(o.css("margin-top"),10),s=parseInt(o.css("margin-left"),10);isNaN(r)&&(r=0),isNaN(s)&&(s=0),e.top+=r,e.left+=s,t.offset.setOffset(o[0],t.extend({using:function(t){o.css({top:Math.round(t.top),left:Math.round(t.left)})}},e),0),o.addClass("in");var l=o[0].offsetWidth,p=o[0].offsetHeight;"top"==i&&p!=a&&(e.top=e.top+a-p);var h=this.getViewportAdjustedDelta(i,e,l,p);h.left?e.left+=h.left:e.top+=h.top;var d=/top|bottom/.test(i),c=d?2*h.left-n+l:2*h.top-a+p,u=d?"offsetWidth":"offsetHeight";o.offset(e),this.replaceArrow(c,o[0][u],d)},i.prototype.replaceArrow=function(t,e,i){this.arrow().css(i?"left":"top",50*(1-t/e)+"%").css(i?"top":"left","")},i.prototype.setContent=function(){var t=this.tip(),e=this.getTitle();t.find(".tooltip-inner")[this.options.html?"html":"text"](e),t.removeClass("fade in top bottom left right")},i.prototype.hide=function(e){function o(){"in"!=n.hoverState&&a.detach(),n.$element&&n.$element.removeAttr("aria-describedby").trigger("hidden.bs."+n.type),e&&e()}var n=this,a=t(this.$tip),r=t.Event("hide.bs."+this.type);return this.$element.trigger(r),r.isDefaultPrevented()?void 0:(a.removeClass("in"),t.support.transition&&a.hasClass("fade")?a.one("bsTransitionEnd",o).emulateTransitionEnd(i.TRANSITION_DURATION):o(),this.hoverState=null,this)},i.prototype.fixTitle=function(){var t=this.$element;(t.attr("title")||"string"!=typeof t.attr("data-original-title"))&&t.attr("data-original-title",t.attr("title")||"").attr("title","")},i.prototype.hasContent=function(){return this.getTitle()},i.prototype.getPosition=function(e){var i=(e=e||this.$element)[0],o="BODY"==i.tagName,n=i.getBoundingClientRect();null==n.width&&(n=t.extend({},n,{width:n.right-n.left,height:n.bottom-n.top}));var a=window.SVGElement&&i instanceof window.SVGElement,r=o?{top:0,left:0}:a?null:e.offset(),s={scroll:o?document.documentElement.scrollTop||document.body.scrollTop:e.scrollTop()},l=o?{width:t(window).width(),height:t(window).height()}:null;return t.extend({},n,s,l,r)},i.prototype.getCalculatedOffset=function(t,e,i,o){return"bottom"==t?{top:e.top+e.height,left:e.left+e.width/2-i/2}:"top"==t?{top:e.top-o,left:e.left+e.width/2-i/2}:"left"==t?{top:e.top+e.height/2-o/2,left:e.left-i}:{top:e.top+e.height/2-o/2,left:e.left+e.width}},i.prototype.getViewportAdjustedDelta=function(t,e,i,o){var n={top:0,left:0};if(!this.$viewport)return n;var a=this.options.viewport&&this.options.viewport.padding||0,r=this.getPosition(this.$viewport);if(/right|left/.test(t)){var s=e.top-a-r.scroll,l=e.top+a-r.scroll+o;s<r.top?n.top=r.top-s:l>r.top+r.height&&(n.top=r.top+r.height-l)}else{var p=e.left-a,h=e.left+a+i;p<r.left?n.left=r.left-p:h>r.right&&(n.left=r.left+r.width-h)}return n},i.prototype.getTitle=function(){var t,e=this.$element,i=this.options;return t=e.attr("data-original-title")||("function"==typeof i.title?i.title.call(e[0]):i.title)},i.prototype.getUID=function(t){do{t+=~~(1e6*Math.random())}while(document.getElementById(t));return t},i.prototype.tip=function(){if(!this.$tip&&(this.$tip=t(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},i.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},i.prototype.enable=function(){this.enabled=!0},i.prototype.disable=function(){this.enabled=!1},i.prototype.toggleEnabled=function(){this.enabled=!this.enabled},i.prototype.toggle=function(e){var i=this;e&&((i=t(e.currentTarget).data("bs."+this.type))||(i=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,i))),e?(i.inState.click=!i.inState.click,i.isInStateTrue()?i.enter(i):i.leave(i)):i.tip().hasClass("in")?i.leave(i):i.enter(i)},i.prototype.destroy=function(){var t=this;clearTimeout(this.timeout),this.hide((function(){t.$element.off("."+t.type).removeData("bs."+t.type),t.$tip&&t.$tip.detach(),t.$tip=null,t.$arrow=null,t.$viewport=null,t.$element=null}))};var o=t.fn.tooltip;t.fn.tooltip=e,t.fn.tooltip.Constructor=i,t.fn.tooltip.noConflict=function(){return t.fn.tooltip=o,this}}(jQuery),t(".variations [data-toggle=tooltip]").tooltip()}}else t(".variations [data-toggle=tooltip]").tooltip();t(document).on("woosq_loaded",(function(){e()})),t(".bundle_form .bundle_data").each((function(){t(this).on("woocommerce-product-bundle-initialized",(function(){e()}))})),e()}));