/*!
   Copyright 2009-2021 SpryMedia Ltd.

 This source file is free software, available under the following license:
   MIT license - http://datatables.net/license/mit

 This source file is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.

 For details please refer to: http://www.datatables.net
 FixedHeader 3.2.0
 ©2009-2021 SpryMedia Ltd - datatables.net/license
*/
var $jscomp=$jscomp||{};$jscomp.scope={};$jscomp.findInternal=function(b,g,h){b instanceof String&&(b=String(b));for(var l=b.length,k=0;k<l;k++){var v=b[k];if(g.call(h,v,k,b))return{i:k,v:v}}return{i:-1,v:void 0}};$jscomp.ASSUME_ES5=!1;$jscomp.ASSUME_NO_NATIVE_MAP=!1;$jscomp.ASSUME_NO_NATIVE_SET=!1;$jscomp.SIMPLE_FROUND_POLYFILL=!1;$jscomp.ISOLATE_POLYFILLS=!1;
$jscomp.defineProperty=$jscomp.ASSUME_ES5||"function"==typeof Object.defineProperties?Object.defineProperty:function(b,g,h){if(b==Array.prototype||b==Object.prototype)return b;b[g]=h.value;return b};$jscomp.getGlobal=function(b){b=["object"==typeof globalThis&&globalThis,b,"object"==typeof window&&window,"object"==typeof self&&self,"object"==typeof global&&global];for(var g=0;g<b.length;++g){var h=b[g];if(h&&h.Math==Math)return h}throw Error("Cannot find global object");};$jscomp.global=$jscomp.getGlobal(this);
$jscomp.IS_SYMBOL_NATIVE="function"===typeof Symbol&&"symbol"===typeof Symbol("x");$jscomp.TRUST_ES6_POLYFILLS=!$jscomp.ISOLATE_POLYFILLS||$jscomp.IS_SYMBOL_NATIVE;$jscomp.polyfills={};$jscomp.propertyToPolyfillSymbol={};$jscomp.POLYFILL_PREFIX="$jscp$";var $jscomp$lookupPolyfilledValue=function(b,g){var h=$jscomp.propertyToPolyfillSymbol[g];if(null==h)return b[g];h=b[h];return void 0!==h?h:b[g]};
$jscomp.polyfill=function(b,g,h,l){g&&($jscomp.ISOLATE_POLYFILLS?$jscomp.polyfillIsolated(b,g,h,l):$jscomp.polyfillUnisolated(b,g,h,l))};$jscomp.polyfillUnisolated=function(b,g,h,l){h=$jscomp.global;b=b.split(".");for(l=0;l<b.length-1;l++){var k=b[l];if(!(k in h))return;h=h[k]}b=b[b.length-1];l=h[b];g=g(l);g!=l&&null!=g&&$jscomp.defineProperty(h,b,{configurable:!0,writable:!0,value:g})};
$jscomp.polyfillIsolated=function(b,g,h,l){var k=b.split(".");b=1===k.length;l=k[0];l=!b&&l in $jscomp.polyfills?$jscomp.polyfills:$jscomp.global;for(var v=0;v<k.length-1;v++){var t=k[v];if(!(t in l))return;l=l[t]}k=k[k.length-1];h=$jscomp.IS_SYMBOL_NATIVE&&"es6"===h?l[k]:null;g=g(h);null!=g&&(b?$jscomp.defineProperty($jscomp.polyfills,k,{configurable:!0,writable:!0,value:g}):g!==h&&($jscomp.propertyToPolyfillSymbol[k]=$jscomp.IS_SYMBOL_NATIVE?$jscomp.global.Symbol(k):$jscomp.POLYFILL_PREFIX+k,k=
$jscomp.propertyToPolyfillSymbol[k],$jscomp.defineProperty(l,k,{configurable:!0,writable:!0,value:g})))};$jscomp.polyfill("Array.prototype.find",function(b){return b?b:function(g,h){return $jscomp.findInternal(this,g,h).v}},"es6","es3");
(function(b){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(g){return b(g,window,document)}):"object"===typeof exports?module.exports=function(g,h){g||(g=window);h&&h.fn.dataTable||(h=require("datatables.net")(g,h).$);return b(h,g,g.document)}:b(jQuery,window,document)})(function(b,g,h,l){var k=b.fn.dataTable,v=0,t=function(a,c){if(!(this instanceof t))throw"FixedHeader must be initialised with the 'new' keyword.";!0===c&&(c={});a=new k.Api(a);this.c=b.extend(!0,
{},t.defaults,c);this.s={dt:a,position:{theadTop:0,tbodyTop:0,tfootTop:0,tfootBottom:0,width:0,left:0,tfootHeight:0,theadHeight:0,windowHeight:b(g).height(),visible:!0},headerMode:null,footerMode:null,autoWidth:a.settings()[0].oFeatures.bAutoWidth,namespace:".dtfc"+v++,scrollLeft:{header:-1,footer:-1},enable:!0};this.dom={floatingHeader:null,thead:b(a.table().header()),tbody:b(a.table().body()),tfoot:b(a.table().footer()),header:{host:null,floating:null,floatingParent:b('<div class="dtfh-floatingparent">'),
placeholder:null},footer:{host:null,floating:null,floatingParent:b('<div class="dtfh-floatingparent">'),placeholder:null}};this.dom.header.host=this.dom.thead.parent();this.dom.footer.host=this.dom.tfoot.parent();a=a.settings()[0];if(a._fixedHeader)throw"FixedHeader already initialised on table "+a.nTable.id;a._fixedHeader=this;this._constructor()};b.extend(t.prototype,{destroy:function(){this.s.dt.off(".dtfc");b(g).off(this.s.namespace);this.c.header&&this._modeChange("in-place","header",!0);this.c.footer&&
this.dom.tfoot.length&&this._modeChange("in-place","footer",!0)},enable:function(a,c){this.s.enable=a;if(c||c===l)this._positions(),this._scroll(!0)},enabled:function(){return this.s.enable},headerOffset:function(a){a!==l&&(this.c.headerOffset=a,this.update());return this.c.headerOffset},footerOffset:function(a){a!==l&&(this.c.footerOffset=a,this.update());return this.c.footerOffset},update:function(a){var c=this.s.dt.table().node();b(c).is(":visible")?this.enable(!0,!1):this.enable(!1,!1);0!==b(c).children("thead").length&&
(this._positions(),this._scroll(a!==l?a:!0))},_constructor:function(){var a=this,c=this.s.dt;b(g).on("scroll"+this.s.namespace,function(){a._scroll()}).on("resize"+this.s.namespace,k.util.throttle(function(){a.s.position.windowHeight=b(g).height();a.update()},50));var d=b(".fh-fixedHeader");!this.c.headerOffset&&d.length&&(this.c.headerOffset=d.outerHeight());d=b(".fh-fixedFooter");!this.c.footerOffset&&d.length&&(this.c.footerOffset=d.outerHeight());c.on("column-reorder.dt.dtfc column-visibility.dt.dtfc column-sizing.dt.dtfc responsive-display.dt.dtfc",
function(f,e){a.update()}).on("draw.dt.dtfc",function(f,e){a.update(e===c.settings()[0]?!1:!0)});c.on("destroy.dtfc",function(){a.destroy()});this._positions();b("div.dataTables_scrollHeadInner").height(this.s.position.theadHeight);this._scroll()},_clone:function(a,c){var d=this,f=this.s.dt,e=this.dom[a],p="header"===a?this.dom.thead:this.dom.tfoot;if("footer"!==a||!this._scrollEnabled())if(!c&&e.floating)e.floating.removeClass("fixedHeader-floating fixedHeader-locked");else{e.floating&&(null!==e.placeholder&&
e.placeholder.remove(),this._unsize(a),e.floating.children().detach(),e.floating.remove());c=b(f.table().node());var n=b(c.parent()),q=this._scrollEnabled();e.floating=b(f.table().node().cloneNode(!1)).attr("aria-hidden","true").css({"table-layout":"fixed",top:0,left:0}).removeAttr("id").append(p);e.floatingParent.css({width:n.width(),overflow:"hidden",height:"fit-content",position:"fixed",left:q?c.offset().left+n.scrollLeft():0}).css("header"===a?{top:this.c.headerOffset,bottom:""}:{top:"",bottom:this.c.footerOffset}).addClass("footer"===
a?"dtfh-floatingparentfoot":"dtfh-floatingparenthead").append(e.floating).appendTo("body");this._stickyPosition(e.floating,"-");a=function(){var r=n.scrollLeft();d.s.scrollLeft={footer:r,header:r};e.floatingParent.scrollLeft(d.s.scrollLeft.header)};a();n.scroll(a);e.placeholder=p.clone(!1);e.placeholder.find("*[id]").removeAttr("id");e.host.prepend(e.placeholder);this._matchWidths(e.placeholder,e.floating)}},_stickyPosition:function(a,c){if(this._scrollEnabled()){var d=this,f="rtl"===b(d.s.dt.table().node()).css("direction");
a.find("th").each(function(){if("sticky"===b(this).css("position")){var e=b(this).css("right"),p=b(this).css("left");"auto"===e||f?"auto"!==p&&f&&(e=+p.replace(/px/g,"")+("-"===c?-1:1)*d.s.dt.settings()[0].oBrowser.barWidth,b(this).css("left",0<e?e:0)):(e=+e.replace(/px/g,"")+("-"===c?-1:1)*d.s.dt.settings()[0].oBrowser.barWidth,b(this).css("right",0<e?e:0))}})}},_matchWidths:function(a,c){var d=function(p){return b(p,a).map(function(){return 1*b(this).css("width").replace(/[^\d\.]/g,"")}).toArray()},
f=function(p,n){b(p,c).each(function(q){b(this).css({width:n[q],minWidth:n[q]})})},e=d("th");d=d("td");f("th",e);f("td",d)},_unsize:function(a){var c=this.dom[a].floating;c&&("footer"===a||"header"===a&&!this.s.autoWidth)?b("th, td",c).css({width:"",minWidth:""}):c&&"header"===a&&b("th, td",c).css("min-width","")},_horizontal:function(a,c){var d=this.dom[a],f=this.s.scrollLeft;if(d.floating&&f[a]!==c){if(this._scrollEnabled()){var e=b(b(this.s.dt.table().node()).parent()).scrollLeft();d.floating.scrollLeft(e);
d.floatingParent.scrollLeft(e)}f[a]=c}},_modeChange:function(a,c,d){var f=this.dom[c],e=this.s.position,p=this._scrollEnabled();if("footer"!==c||!p){var n=function(B){f.floating.attr("style",function(w,x){return(x||"")+"width: "+B+"px !important;"});p||f.floatingParent.attr("style",function(w,x){return(x||"")+"width: "+B+"px !important;"})},q=this.dom["footer"===c?"tfoot":"thead"],r=b.contains(q[0],h.activeElement)?h.activeElement:null,m=b(b(this.s.dt.table().node()).parent());if("in-place"===a)f.placeholder&&
(f.placeholder.remove(),f.placeholder=null),this._unsize(c),"header"===c?f.host.prepend(q):f.host.append(q),f.floating&&(f.floating.remove(),f.floating=null,this._stickyPosition(f.host,"+")),f.floatingParent&&f.floatingParent.remove(),b(b(f.host.parent()).parent()).scrollLeft(m.scrollLeft());else if("in"===a){this._clone(c,d);q=m.offset();d=b(h).scrollTop();var y=b(g).height();y=d+y;var z=p?q.top:e.tbodyTop;m=p?q.top+m.outerHeight():e.tfootTop;d="footer"===c?z>y?e.tfootHeight:z+e.tfootHeight-y:d+
this.c.headerOffset+e.theadHeight-m;m="header"===c?"top":"bottom";d=this.c[c+"Offset"]-(0<d?d:0);f.floating.addClass("fixedHeader-floating");f.floatingParent.css(m,d).css({left:e.left,height:"header"===c?e.theadHeight:e.tfootHeight,"z-index":2}).append(f.floating);n(e.width);"footer"===c&&f.floating.css("top","")}else"below"===a?(this._clone(c,d),f.floating.addClass("fixedHeader-locked"),f.floatingParent.css({position:"absolute",top:e.tfootTop-e.theadHeight,left:e.left+"px"}),n(e.width)):"above"===
a&&(this._clone(c,d),f.floating.addClass("fixedHeader-locked"),f.floatingParent.css({position:"absolute",top:e.tbodyTop,left:e.left+"px"}),n(e.width));r&&r!==h.activeElement&&setTimeout(function(){r.focus()},10);this.s.scrollLeft.header=-1;this.s.scrollLeft.footer=-1;this.s[c+"Mode"]=a}},_positions:function(){var a=this.s.dt,c=a.table(),d=this.s.position,f=this.dom;c=b(c.node());var e=this._scrollEnabled(),p=b(a.table().header());a=b(a.table().footer());f=f.tbody;var n=c.parent();d.visible=c.is(":visible");
d.width=c.outerWidth();d.left=c.offset().left;d.theadTop=p.offset().top;d.tbodyTop=e?n.offset().top:f.offset().top;d.tbodyHeight=e?n.outerHeight():f.outerHeight();d.theadHeight=p.outerHeight();d.theadBottom=d.theadTop+d.theadHeight;a.length?(d.tfootTop=d.tbodyTop+d.tbodyHeight,d.tfootBottom=d.tfootTop+a.outerHeight(),d.tfootHeight=a.outerHeight()):(d.tfootTop=d.tbodyTop+f.outerHeight(),d.tfootBottom=d.tfootTop,d.tfootHeight=d.tfootTop)},_scroll:function(a){var c=this._scrollEnabled(),d=b(this.s.dt.table().node()).parent(),
f=d.offset(),e=d.outerHeight(),p=b(h).scrollLeft(),n=b(h).scrollTop(),q=b(g).height(),r=q+n,m=this.s.position,y=c?f.top:m.tbodyTop,z=c?f.left:m.left;e=c?f.top+e:m.tfootTop;var B=c?d.outerWidth():m.tbodyWidth;r=n+q;this.c.header&&(this.s.enable?!m.visible||n+this.c.headerOffset+m.theadHeight<=y?q="in-place":n+this.c.headerOffset+m.theadHeight>y&&n+this.c.headerOffset<e?(q="in",d=b(b(this.s.dt.table().node()).parent()),n+this.c.headerOffset+m.theadHeight>e||this.dom.header.floatingParent===l?a=!0:this.dom.header.floatingParent.css({top:this.c.headerOffset,
position:"fixed"}).append(this.dom.header.floating)):q="below":q="in-place",(a||q!==this.s.headerMode)&&this._modeChange(q,"header",a),this._horizontal("header",p));var w={offset:{top:0,left:0},height:0},x={offset:{top:0,left:0},height:0};this.c.footer&&this.dom.tfoot.length&&(this.s.enable?!m.visible||m.tfootBottom+this.c.footerOffset<=r?m="in-place":e+m.tfootHeight+this.c.footerOffset>r&&y+this.c.footerOffset<r?(m="in",a=!0):m="above":m="in-place",(a||m!==this.s.footerMode)&&this._modeChange(m,
"footer",a),this._horizontal("footer",p),a=function(A){return{offset:A.offset(),height:A.outerHeight()}},w=this.dom.header.floating?a(this.dom.header.floating):a(this.dom.thead),x=this.dom.footer.floating?a(this.dom.footer.floating):a(this.dom.tfoot),c&&x.offset.top>n&&(c=n-f.top,r=r+(c>-w.height?c:0)-(w.offset.top+(c<-w.height?w.height:0)+x.height),0>r&&(r=0),d.outerHeight(r),Math.round(d.outerHeight())>=Math.round(r)?b(this.dom.tfoot.parent()).addClass("fixedHeader-floating"):b(this.dom.tfoot.parent()).removeClass("fixedHeader-floating")));
this.dom.header.floating&&this.dom.header.floatingParent.css("left",z-p);this.dom.footer.floating&&this.dom.footer.floatingParent.css("left",z-p);this.s.dt.settings()[0]._fixedColumns!==l&&(d=function(A,C,u){u===l&&(u=b("div.dtfc-"+A+"-"+C+"-blocker"),u=0===u.length?null:u.clone().appendTo("body").css("z-index",1));null!==u&&u.css({top:"top"===C?w.offset.top:x.offset.top,left:"right"===A?z+B-u.width():z});return u},this.dom.header.rightBlocker=d("right","top",this.dom.header.rightBlocker),this.dom.header.leftBlocker=
d("left","top",this.dom.header.leftBlocker),this.dom.footer.rightBlocker=d("right","bottom",this.dom.footer.rightBlocker),this.dom.footer.leftBlocker=d("left","bottom",this.dom.footer.leftBlocker))},_scrollEnabled:function(){var a=this.s.dt.settings()[0].oScroll;return""!==a.sY||""!==a.sX?!0:!1}});t.version="3.2.0";t.defaults={header:!0,footer:!1,headerOffset:0,footerOffset:0};b.fn.dataTable.FixedHeader=t;b.fn.DataTable.FixedHeader=t;b(h).on("init.dt.dtfh",function(a,c,d){"dt"===a.namespace&&(a=c.oInit.fixedHeader,
d=k.defaults.fixedHeader,!a&&!d||c._fixedHeader||(d=b.extend({},d,a),!1!==a&&new t(c,d)))});k.Api.register("fixedHeader()",function(){});k.Api.register("fixedHeader.adjust()",function(){return this.iterator("table",function(a){(a=a._fixedHeader)&&a.update()})});k.Api.register("fixedHeader.enable()",function(a){return this.iterator("table",function(c){c=c._fixedHeader;a=a!==l?a:!0;c&&a!==c.enabled()&&c.enable(a)})});k.Api.register("fixedHeader.enabled()",function(){if(this.context.length){var a=this.context[0]._fixedHeader;
if(a)return a.enabled()}return!1});k.Api.register("fixedHeader.disable()",function(){return this.iterator("table",function(a){(a=a._fixedHeader)&&a.enabled()&&a.enable(!1)})});b.each(["header","footer"],function(a,c){k.Api.register("fixedHeader."+c+"Offset()",function(d){var f=this.context;return d===l?f.length&&f[0]._fixedHeader?f[0]._fixedHeader[c+"Offset"]():l:this.iterator("table",function(e){if(e=e._fixedHeader)e[c+"Offset"](d)})})});return t});
