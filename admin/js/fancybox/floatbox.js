/***************************************************************************
* Floatbox v3.51
* April 23, 2009
*
* Copyright (c) 2008-2009 Byron McGregor
* Website: http://randomous.com/floatbox
* License: Attribution-Noncommercial-No Derivative Works 3.0 Unported
*          http://creativecommons.org/licenses/by-nc-nd/3.0/
* Use on any commercial site requires purchase and registration.
* See http://randomous.com/floatbox/license for details.
* This comment block must be retained in all deployments and distributions.
***************************************************************************/

function Floatbox() {
this.defaultOptions = {

/***** BEGIN OPTIONS CONFIGURATION *****/
// See docs/options.html for detailed descriptions.
// All options can be overridden with rev/data-fb-options tag or page options (see docs/instructions.html).

/*** <General Options> ***/
theme:            'auto'    ,// 'auto'|'black'|'white'|'blue'|'yellow'|'red'|'custom'
padding:           24       ,// pixels
panelPadding:      8        ,// pixels
overlayOpacity:    55       ,// 0-100
shadowType:       'drop'    ,// 'drop'|'halo'|'none'
shadowSize:        12       ,// 8|12|16|24
roundCorners:     'all'     ,// 'all'|'top'|'none'
cornerRadius:      12       ,// 8|12|20
roundBorder:       1        ,// 0|1
outerBorder:       4        ,// pixels
innerBorder:       1        ,// pixels
autoFitImages:     true     ,// true|false
resizeImages:      true     ,// true|false
autoFitOther:      false    ,// true|false
resizeOther:       false    ,// true|false
resizeTool:       'cursor'  ,// 'cursor'|'topleft'|'both'
infoPos:          'bl'      ,// 'tl'|'tc'|'tr'|'bl'|'bc'|'br'
controlPos:       'br'      ,// 'tl'|'tr'|'bl'|'br'
centerNav:         false    ,// true|false
boxLeft:          'auto'    ,// 'auto'|pixels|'[-]xx%'
boxTop:           'auto'    ,// 'auto'|pixels|'[-]xx%'
enableDragMove:    false    ,// true|false
stickyDragMove:    true     ,// true|false
enableDragResize:  false    ,// true|false
stickyDragResize:  true     ,// true|false
draggerLocation:  'frame'   ,// 'frame'|'content'
minContentWidth:   140      ,// pixels
minContentHeight:  100      ,// pixels
centerOnResize:    true     ,// true|false
showCaption:       true     ,// true|false
showItemNumber:    true     ,// true|false
showClose:         true     ,// true|false
hideFlash:         true     ,// true|false
hideJava:          true     ,// true|false
disableScroll:     false    ,// true|false
randomOrder:       false    ,// true|false
preloadAll:        true     ,// true|false
autoGallery:       false    ,// true|false
autoTitle:        ''        ,// common caption string to use with autoGallery
printCSS:         ''        ,// path to css file or inline css string to apply to print pages (see showPrint)
language:         'auto'    ,// 'auto'|'en'|... (see the languages folder)
graphicsType:     'auto'    ,// 'auto'|'international'|'english'
/*** </General Options> ***/

/*** <Animation Options> ***/
doAnimations:         true   ,// true|false
resizeDuration:       3.5    ,// 0-10
imageFadeDuration:    3      ,// 0-10
overlayFadeDuration:  4      ,// 0-10
startAtClick:         true   ,// true|false
zoomImageStart:       true   ,// true|false
liveImageResize:      true   ,// true|false
splitResize:         'no'    ,// 'no'|'auto'|'wh'|'hw'
/*** </Animation Options> ***/

/*** <Navigation Options> ***/
navType:            'both'    ,// 'overlay'|'button'|'both'|'none'
navOverlayWidth:     35       ,// 0-50
navOverlayPos:       30       ,// 0-100
showNavOverlay:     'never'   ,// 'always'|'once'|'never'
showHints:          'once'    ,// 'always'|'once'|'never'
enableWrap:          true     ,// true|false
enableKeyboardNav:   true     ,// true|false
outsideClickCloses:  true     ,// true|false
imageClickCloses:    false    ,// true|false
numIndexLinks:       0        ,// number, -1 = no limit
indexLinksPanel:    'control' ,// 'info'|'control'
showIndexThumbs:     true     ,// true|false
/*** </Navigation Options> ***/

/*** <Slideshow Options> ***/
doSlideshow:    false  ,// true|false
slideInterval:  4.5    ,// seconds
endTask:       'exit'  ,// 'stop'|'exit'|'loop'
showPlayPause:  true   ,// true|false
startPaused:    false  ,// true|false
pauseOnResize:  true   ,// true|false
pauseOnPrev:    true   ,// true|false
pauseOnNext:    false   // true|false
/*** </Slideshow Options> ***/
};

/*** <New Child Window Options> ***/
// Will inherit from the primary floatbox options unless overridden here.
// Add any you like.
this.childOptions = {
padding:             16,
overlayOpacity:      45,
resizeDuration:       3,
imageFadeDuration:    3,
overlayFadeDuration:  0
};
/*** </New Child Window Options> ***/

/*** <Custom Paths> ***/
// Normally leave these blank.
// Floatbox will auto-find folders based on the location of floatbox.js and background-images.
// If you have a custom odd-ball configuration, fill in the details here.
// (Trailing slashes please)
this.customPaths = {
	jsModules: ''   ,// default: <floatbox.js>/modules/
	cssModules: ''  ,// default: <floatbox.js>/modules/
	languages: ''   ,// default: <floatbox.js>/languages/
	graphics: ''     // default: background-image:url(<parsed folder>);
};
/*** </Custom Paths> ***/

/***** END OPTIONS CONFIGURATION *****/
this.init();}
Floatbox.prototype={magicClass:"floatbox",panelGap:20,infoLinkGap:16,draggerSize:12,controlOpacity:60,showHintsTime:1600,zoomPopBorder:1,controlSpacing:8,minInfoWidth:80,minIndexWidth:120,ctrlJump:5,slowLoadDelay:750,autoFitSpace:5,maxInitialSize:120,minInitialSize:70,defaultWidth:"85%",defaultHeight:"82%",init:function(){var H=this;H.doc=document;H.docEl=H.doc.documentElement;H.head=H.doc.getElementsByTagName("head")[0];H.bod=H.doc.getElementsByTagName("body")[0];H.getGlobalOptions();H.currentSet=[];H.nodes=[];H.hiddenEls=[];H.timeouts={};H.pos={};var E=navigator.userAgent,G=navigator.appVersion;H.mac=G.indexOf("Macintosh")!==-1;if(window.opera){H.opera=true;H.operaOld=parseFloat(G)<9.5}else{if(document.all){H.ie=true;H.ieOld=parseInt(G.substr(G.indexOf("MSIE")+5),10)<7;H.ieXP=parseInt(G.substr(G.indexOf("Windows NT")+11),10)<6}else{if(E.indexOf("Firefox")!==-1){H.ff=true;H.ffOld=parseInt(E.substr(E.indexOf("Firefox")+8),10)<3;H.ffNew=!H.ffOld;H.ffMac=H.mac}else{if(G.indexOf("WebKit")!==-1){H.webkit=true;H.webkitMac=H.mac}else{if(E.indexOf("SeaMonkey")!==-1){H.seaMonkey=true}}}}}H.browserLanguage=(navigator.language||navigator.userLanguage||navigator.systemLanguage||navigator.browserLanguage||"en").substring(0,2);H.isChild=!!self.fb;if(!H.isChild){H.parent=H.lastChild=H;H.anchors=[];H.children=[];H.popups=[];H.preloads={};var B=function(K){return K},A=function(K){return K&&H.doAnimations},J=function(K){return A(K)&&H.resizeDuration};H.modules={enableKeyboardNav:{files:["keydownHandler.js"],test:B},enableDragMove:{files:["mousedownHandler.js"],test:B},enableDragResize:{files:["mousedownHandler.js"],test:B},centerOnResize:{files:["resizeHandler.js"],test:B},showPrint:{files:["printContents.js"],test:B},imageFadeDuration:{files:["setOpacity.js"],test:A},overlayFadeDuration:{files:["setOpacity.js"],test:A},resizeDuration:{files:["setSize.js"],test:A},startAtClick:{files:["getLeftTop.js"],test:J},zoomImageStart:{files:["getLeftTop.js","zoomInOut.js"],test:J},loaded:{}};H.jsModulesPath=H.customPaths.jsModules;H.cssModulesPath=H.customPaths.cssModules;H.languagesPath=H.customPaths.languages;if(!(H.jsModulesPath&&H.cssModulesPath&&H.languagesPath)){var C=H.getPath("script","src",/(.*)f(?:loat|rame)box.js(?:\?|$)/i)||"/floatbox/";if(!H.jsModulesPath){H.jsModulesPath=C+"modules/"}if(!H.cssModulesPath){H.cssModulesPath=C+"modules/"}if(!H.languagesPath){H.languagesPath=C+"languages/"}}H.graphicsPath=H.customPaths.graphics;if(!H.graphicsPath){var F,D=H.doc.createElement("div");D.id="fbPathChecker";H.bod.appendChild(D);if((F=/(?:url\()?["']?(.*)blank.gif["']?\)?$/i.exec(H.getStyle(D,"background-image")))){H.graphicsPath=F[1]}H.bod.removeChild(D);delete D;if(!H.graphicsPath){H.graphicsPath=(H.getPath("link","href",/(.*)floatbox.css(?:\?|$)/i)||"/floatbox/")+"graphics/"}}H.rtl=H.getStyle(H.bod,"direction")==="rtl"||H.getStyle(H.docEl,"direction")==="rtl"}else{H.parent=fb.lastChild;fb.lastChild=H;fb.children.push(H);H.anchors=fb.anchors;H.popups=fb.popups;H.preloads=fb.preloads;H.modules=fb.modules;H.jsModulesPath=fb.jsModulesPath;H.cssModulesPath=fb.cssModulesPath;H.languagesPath=fb.languagesPath;H.graphicsPath=fb.graphicsPath;H.strings=fb.strings;H.rtl=fb.rtl;if(H.parent.isSlideshow){H.parent.setPause(true)}}var I=H.graphicsPath;H.resizeUpCursor=I+"magnify_plus.cur";H.resizeDownCursor=I+"magnify_minus.cur";H.notFoundImg=I+"404.jpg";H.blank=I+"blank.gif";H.zIndex={base:90000+(H.isChild?12*fb.children.length:0),fbOverlay:1,fbBox:2,fbCanvas:3,fbContent:4,fbMainLoader:5,fbLeftNav:6,fbRightNav:6,fbOverlayPrev:7,fbOverlayNext:7,fbResizer:8,fbInfoPanel:9,fbControlPanel:9,fbDragger:10,fbZoomDiv:11};var F=/\bautoStart=(.+?)(?:&|$)/i.exec(location.search);H.autoHref=F?F[1]:false},tagAnchors:function(C){var B=this;function A(F){var G=C.getElementsByTagName(F);for(var E=0,D=G.length;E<D;E++){B.tagOneAnchor(G[E])}}A("a");A("area");B.getModule("core.js");B.getModules(B.defaultOptions,true);if(B.popups.length){B.getModule("getLeftTop.js");B.getModule("setOpacity.js");B.getModule("tagPopup.js");if(B.tagPopup){while(B.popups.length){B.tagPopup(B.popups.pop())}}}if(B.ieOld){B.getModule("ieOld.js")}},tagOneAnchor:function(F,I){var L=this,A=!!F.getAttribute,H;if(A){var J={href:F.getAttribute("href")||"",rev:F.getAttribute("data-fb-options")||F.getAttribute("rev")||"",rel:F.getAttribute("rel")||"",title:F.getAttribute("title")||"",className:F.className||"",ownerDoc:F.ownerDocument,anchor:F,thumb:(F.getElementsByTagName("img")||[])[0]}}else{var J=F;J.anchor=J.thumb=J.ownerDoc=false}if((H=new RegExp("(?:^|\\s)"+L.magicClass+"(\\S*)","i").exec(J.className))){J.tagged=true;if(H[1]){J.group=H[1]}}if(L.autoGallery&&!J.tagged&&L.fileType(J.href)==="img"&&J.rel.toLowerCase()!=="nofloatbox"&&J.className.toLowerCase().indexOf("nofloatbox")===-1){J.tagged=true;J.group=".autoGallery";if(L.autoTitle&&!J.title){J.title=L.autoTitle}}if(!J.tagged){if((H=/^(?:floatbox|gallery|iframe|slideshow|lytebox|lyteshow|lyteframe|lightbox)(.*)/i.exec(J.rel))){J.tagged=true;J.group=H[1];if(/^(slide|lyte)show/i.test(J.rel)){J.rev+=" doSlideshow:true"}else{if(/^(i|lyte)frame/i.test(J.rel)){J.rev+=" type:iframe"}}}}if(J.thumb&&((H=/(?:^|\s)fbPop(up|down)(?:\s|$)/i.exec(F.className)))){J.popup=true;J.popupType=H[1];L.popups.push(J)}if(I){J.tagged=true}if(J.tagged){J.options=L.parseOptionString(J.rev);J.href=J.options.href||J.href;J.group=J.options.group||J.group||"";if(!J.href&&J.options.showThis!==false){return false}J.level=fb.children.length+(fb.lastChild.fbBox&&!J.options.sameBox?1:0);var E=L.anchors.length;while(E--){var G=L.anchors[E];if(G.href===J.href&&G.rev===J.rev&&G.rel===J.rel&&G.title===J.title&&G.level===J.level&&(G.anchor===J.anchor||(J.ownerDoc&&J.ownerDoc!==L.doc))){G.anchor=J.anchor;G.thumb=J.thumb;break}}if(E===-1){J.type=J.options.type||L.fileType(J.href);if(J.type==="html"){J.type="iframe";var H=/#(\w+)/.exec(J.href);if(H){var K=document;if(J.anchor){K=J.ownerDoc||K}if(K===document&&L.itemToShow&&L.itemToShow.anchor){K=L.itemToShow.ownerDoc||K}var C=K.getElementById(H[1]);if(C){J.type="inline";J.sourceEl=C}}}L.anchors.push(J);L.getModules(J.options,false);if(J.type.indexOf("media")===0){L.getModule("mediaHTML.js")}if(L.autoHref){if(J.options.showThis!==false&&L.autoHref===J.href.substr(J.href.length-L.autoHref.length)){L.autoStart=J}}else{if(J.options.autoStart===true){L.autoStart=J}else{if(J.options.autoStart==="once"){var H=/fbAutoShown=(.+?)(?:;|$)/.exec(document.cookie),D=H?H[1]:"",B=escape(J.href);if(D.indexOf(B)===-1){L.autoStart=J;document.cookie="fbAutoShown="+D+B+"; path=/"}}}}if(L.ieOld&&J.anchor){J.anchor.hideFocus="true"}}if(A){F.onclick=function(N){if(!N){var M=this.ownerDocument;N=M&&M.parentWindow&&M.parentWindow.event}if(!(N&&(N.ctrlKey||N.metaKey||N.shiftKey||N.altKey))||J.options.showThis===false||!/img|iframe/.test(J.type)){if(L.start){L.start(F)}return L.stopEvent(N)}}}}return J},fileType:function(A){var C=this,D=(A||"").toLowerCase(),B=D.indexOf("?");if(B!==-1){D=D.substr(0,B)}D=D.substr(D.lastIndexOf(".")+1);if(/^(jpe?g|png|gif|bmp)$/.test(D)){return"img"}if(D==="swf"||/^(http:)?\/\/(www.)?(youtube|dailymotion).com\/(v|swf)\//i.test(A)){return"media:flash"}if(/^(mov|mpe?g|movie)$/.test(D)){return"media:quicktime"}if(D==="xap"){return"media:silverlight"}return"html"},getGlobalOptions:function(){var C=this;if(!C.isChild){C.setOptions(C.defaultOptions);if(typeof setFloatboxOptions==="function"){setFloatboxOptions()}C.pageOptions=typeof fbPageOptions==="object"?fbPageOptions:{}}else{for(var B in C.defaultOptions){if(C.defaultOptions.hasOwnProperty(B)){C[B]=C.parent[B]}}C.setOptions(C.childOptions);C.pageOptions={};for(var B in C.parent.pageOptions){if(C.parent.pageOptions.hasOwnProperty(B)){C.pageOptions[B]=C.parent.pageOptions[B]}}if(typeof fbChildOptions==="object"){for(var B in fbChildOptions){if(fbChildOptions.hasOwnProperty(B)){C.pageOptions[B]=fbChildOptions[B]}}}}C.setOptions(C.pageOptions);if(C.pageOptions.enableCookies){var A=/fbOptions=(.+?)(;|$)/.exec(document.cookie);if(A){C.setOptions(C.parseOptionString(A[1]))}}C.setOptions(C.parseOptionString(location.search.substring(1)))},parseOptionString:function(H){var K=this;if(!H){return{}}var G=[],E,C=/`([^`]*?)`/g;C.lastIndex=0;while((E=C.exec(H))){G.push(E[1])}if(G.length){H=H.replace(C,"``")}H=H.replace(/\s*[:=]\s*/g,":");H=H.replace(/\s*[;&]\s*/g," ");H=H.replace(/^\s+|\s+$/g,"");H=H.replace(/(:\d+)px\b/gi,function(L,M){return M});var B={},F=H.split(" "),D=F.length;while(D--){var J=F[D].split(":"),A=J[0],I=J[1];if(typeof I==="string"){if(!isNaN(I)){I=+I}else{if(I==="true"){I=true}else{if(I==="false"){I=false}}}}if(I==="``"){I=G.pop()||""}B[A]=I}return B},setOptions:function(C){var B=this;for(var A in C){if(B.defaultOptions.hasOwnProperty(A)){B[A]=C[A]}}},getModule:function(E){var D=this;if(D.modules.loaded[E]){return }if(E.slice(-3)===".js"){var B="script",A={type:"text/javascript",src:D.jsModulesPath+E}}else{var B="link",A={rel:"stylesheet",type:"text/css",href:D.cssModulesPath+E}}var F=D.doc.createElement(B);for(var C in A){if(A.hasOwnProperty(C)){F.setAttribute(C,A[C])}}D.head.appendChild(F);D.modules.loaded[E]=true},getModules:function(C,G){var F=this;for(var B in C){if(F.modules.hasOwnProperty(B)){var E=F.modules[B],H=G?F[B]:C[B],A=0,D=E.files.length;while(D--){if(E.test(H)){F.getModule(E.files[D]);A++}}if(A===E.files.length){delete F.modules[B]}}}},getStyle:function(A,C){if(!(A&&C)){return""}if(A.currentStyle){return A.currentStyle[C.replace(/-(\w)/g,function(D,E){return E.toUpperCase()})]||""}else{var B=A.ownerDocument.defaultView||A.ownerDocument.parentWindow;return(B.getComputedStyle&&B.getComputedStyle(A,"").getPropertyValue(C))||""}},getPath:function(B,A,F){var C,E=document.getElementsByTagName(B),D=E.length;while(D--){if((C=F.exec(E[D][A]))){return C[1]}}return""},addEvent:function(B,C,A){if(B.addEventListener){B.addEventListener(C,A,false)}else{if(B.attachEvent){B.attachEvent("on"+C,A)}else{B["prior"+C]=B["on"+C];B["on"+C]=A}}},removeEvent:function(B,C,A){if(B.removeEventListener){B.removeEventListener(C,A,false)}else{if(B.detachEvent){B.detachEvent("on"+C,A)}else{B["on"+C]=B["prior"+C];delete B["prior"+C]}}},stopEvent:function(A){if(A){if(A.stopPropagation){A.stopPropagation()}if(A.preventDefault){A.preventDefault()}A.cancelBubble=true;A.returnValue=false}return false},preloadImages:function(A,C){var B=this;setTimeout(function(){B.preloadImages(A,C)},100)}};var fb;function initfb(){if(arguments.callee.done){return }var A="self";if(self!==parent){try{if(self.location.host===parent.location.host&&self.location.protocol===parent.location.protocol){A="parent"}}catch(B){}if(A==="parent"&&!parent.fb){return setTimeout(initfb,50)}}arguments.callee.done=true;if(document.compatMode==="BackCompat"){alert("Floatbox does not support quirks mode.\nPage needs to have a valid doctype declaration.");return }fb=(A==="self"?new Floatbox():parent.fb);fb.tagAnchors(self.document.getElementsByTagName("body")[0])}if(document.addEventListener){document.addEventListener("DOMContentLoaded",initfb,false)};
(function(){/*@cc_on if(document.body){try{document.createElement('div').doScroll('left');return initfb();}catch(e){}}/*@if (false) @*/if(/loaded|complete/.test(document.readyState))return initfb();/*@end @*/if(!initfb.done)setTimeout(arguments.callee,30);})();fb_prevOnload=window.onload;window.onload=function(){if(arguments.callee.done){return }arguments.callee.done=true;if(typeof fb_prevOnload==="function"){fb_prevOnload()}initfb();(function(){if(!(self.fb&&self.fb.start)){return setTimeout(arguments.callee,50)}if(fb.autoStart&&fb.autoStart.ownerDoc){if(fb.autoStart.ownerDoc===self.document){setTimeout(function(){fb.start(fb.autoStart)},100)}}else{setTimeout(function(){if(typeof fb.preloads.count==="undefined"){fb.preloadImages("",true)}},200)}})()};
