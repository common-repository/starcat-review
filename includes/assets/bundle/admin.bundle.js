!function(t){var n={};function e(o){if(n[o])return n[o].exports;var i=n[o]={i:o,l:!1,exports:{}};return t[o].call(i.exports,i,i.exports,e),i.l=!0,i.exports}e.m=t,e.c=n,e.d=function(t,n,o){e.o(t,n)||Object.defineProperty(t,n,{enumerable:!0,get:o})},e.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},e.t=function(t,n){if(1&n&&(t=e(t)),8&n)return t;if(4&n&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(e.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&n&&"string"!=typeof t)for(var i in t)e.d(o,i,function(n){return t[n]}.bind(null,i));return o},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},e.p="./",e(e.s=40)}({40:function(t,n,e){"use strict";e.r(n),e(42);var o=e(41),i={init:function(){this.eventhandlers()},eventhandlers:function(){this.iconsOptins(),this.enableProsandCons(),this.animateStats(),o.init()},iconsOptins:function(){jQuery(".ui.dropdown.scr-dropdown").dropdownX()},enableProsandCons:function(){var t=jQuery("#_scr_post_options");"0"==SCROptions.enable_prosandcons&&(t.find("[data-section='_scr_post_options_2']").css({display:"none"}),t.find("[data-section='_scr_post_options_3']").css({display:"none"}))},animateStats:function(){var t=jQuery(".reviewed-list");"1"==t.attr("data-animate")&&t.find(".reviewed-item").each((function(t){var n=jQuery(this),e=n.find("input[name]").attr("value");n.find(".stars-result").css({transition:"width 1s",width:e+"%"}),n.find(".bars-result").css({transition:"width 1s",width:e+"%"})}))}};jQuery(document).ready((function(){i.init()}))},41:function(t,n){var e=".scr_clipboard",o=".csf-after-text",i={init:function(){this.eventHandler()},eventHandler:function(){jQuery(e).on("click",o,(function(){let t=jQuery(this).parent().find("input[type='text']").val(),n=document.createElement("textarea");n.value=t,document.body.appendChild(n),n.select(),document.execCommand("Copy"),n.remove(),i.showNotice("Copied!",100),i.showNotice("Copy Clipboard",1e3)}))},showNotice:function(t,n){setTimeout((function(){jQuery(e).find(o).html(t)}),n)}};t.exports=i},42:function(t,n,e){}});
//# sourceMappingURL=admin.bundle.js.map