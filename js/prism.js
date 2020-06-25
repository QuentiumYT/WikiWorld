var _self="undefined"!=typeof window?window:"undefined"!=typeof WorkerGlobalScope&&self instanceof WorkerGlobalScope?self:{},Prism=function(e){var t=/\blang(?:uage)?-([\w-]+)\b/i,n=0,r={manual:e.Prism&&e.Prism.manual,disableWorkerMessageHandler:e.Prism&&e.Prism.disableWorkerMessageHandler,util:{encode:function(e){return e instanceof i?new i(e.type,r.util.encode(e.content),e.alias):Array.isArray(e)?e.map(r.util.encode):e.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/\u00a0/g," ")},type:function(e){return Object.prototype.toString.call(e).slice(8,-1)},objId:function(e){return e.__id||Object.defineProperty(e,"__id",{value:++n}),e.__id},clone:function e(t,n){var i,a,o=r.util.type(t);switch(n=n||{},o){case"Object":if(a=r.util.objId(t),n[a])return n[a];for(var s in i={},n[a]=i,t)t.hasOwnProperty(s)&&(i[s]=e(t[s],n));return i;case"Array":return a=r.util.objId(t),n[a]?n[a]:(i=[],n[a]=i,t.forEach(function(t,r){i[r]=e(t,n)}),i);default:return t}}},languages:{extend:function(e,t){var n=r.util.clone(r.languages[e]);for(var i in t)n[i]=t[i];return n},insertBefore:function(e,t,n,i){var a=(i=i||r.languages)[e],o={};for(var s in a)if(a.hasOwnProperty(s)){if(s==t)for(var l in n)n.hasOwnProperty(l)&&(o[l]=n[l]);n.hasOwnProperty(s)||(o[s]=a[s])}var u=i[e];return i[e]=o,r.languages.DFS(r.languages,function(t,n){n===u&&t!=e&&(this[t]=o)}),o},DFS:function e(t,n,i,a){a=a||{};var o=r.util.objId;for(var s in t)if(t.hasOwnProperty(s)){n.call(t,s,t[s],i||s);var l=t[s],u=r.util.type(l);"Object"!==u||a[o(l)]?"Array"!==u||a[o(l)]||(a[o(l)]=!0,e(l,n,s,a)):(a[o(l)]=!0,e(l,n,null,a))}}},plugins:{},highlightAll:function(e,t){r.highlightAllUnder(document,e,t)},highlightAllUnder:function(e,t,n){var i={callback:n,selector:'code[class*="language-"], [class*="language-"] code, code[class*="lang-"], [class*="lang-"] code'};r.hooks.run("before-highlightall",i);for(var a,o=e.querySelectorAll(i.selector),s=0;a=o[s++];)r.highlightElement(a,!0===t,i.callback)},highlightElement:function(n,i,a){for(var o,s="none",l=n;l&&!t.test(l.className);)l=l.parentNode;l&&(s=(l.className.match(t)||[,"none"])[1].toLowerCase(),o=r.languages[s]),n.className=n.className.replace(t,"").replace(/\s+/g," ")+" language-"+s,n.parentNode&&(l=n.parentNode,/pre/i.test(l.nodeName)&&(l.className=l.className.replace(t,"").replace(/\s+/g," ")+" language-"+s));var u={element:n,language:s,grammar:o,code:n.textContent},c=function(e){u.highlightedCode=e,r.hooks.run("before-insert",u),u.element.innerHTML=u.highlightedCode,r.hooks.run("after-highlight",u),r.hooks.run("complete",u),a&&a.call(u.element)};if(r.hooks.run("before-sanity-check",u),u.code)if(r.hooks.run("before-highlight",u),u.grammar)if(i&&e.Worker){var d=new Worker(r.filename);d.onmessage=function(e){c(e.data)},d.postMessage(JSON.stringify({language:u.language,code:u.code,immediateClose:!0}))}else c(r.highlight(u.code,u.grammar,u.language));else c(r.util.encode(u.code));else r.hooks.run("complete",u)},highlight:function(e,t,n){var a={code:e,grammar:t,language:n};return r.hooks.run("before-tokenize",a),a.tokens=r.tokenize(a.code,a.grammar),r.hooks.run("after-tokenize",a),i.stringify(r.util.encode(a.tokens),a.language)},matchGrammar:function(e,t,n,a,o,s,l){for(var u in n)if(n.hasOwnProperty(u)&&n[u]){if(u==l)return;var c=n[u];c="Array"===r.util.type(c)?c:[c];for(var d=0;d<c.length;++d){var g=c[d],p=g.inside,f=!!g.lookbehind,m=!!g.greedy,h=0,y=g.alias;if(m&&!g.pattern.global){var b=g.pattern.toString().match(/[imuy]*$/)[0];g.pattern=RegExp(g.pattern.source,b+"g")}g=g.pattern||g;for(var v=a,w=o;v<t.length;w+=t[v].length,++v){var k=t[v];if(t.length>e.length)return;if(!(k instanceof i)){if(m&&v!=t.length-1){if(g.lastIndex=w,!(C=g.exec(e)))break;for(var N=C.index+(f?C[1].length:0),A=C.index+C[0].length,P=v,x=w,S=t.length;P<S&&(x<A||!t[P].type&&!t[P-1].greedy);++P)(x+=t[P].length)<=N&&(++v,w=x);if(t[v]instanceof i)continue;E=P-v,k=e.slice(w,x),C.index-=w}else{g.lastIndex=0;var C=g.exec(k),E=1}if(C){f&&(h=C[1]?C[1].length:0),A=(N=C.index+h)+(C=C[0].slice(h)).length;var z=k.slice(0,N),j=k.slice(A),L=[v,E];z&&(++v,w+=z.length,L.push(z));var T=new i(u,p?r.tokenize(C,p):C,y,C,m);if(L.push(T),j&&L.push(j),Array.prototype.splice.apply(t,L),1!=E&&r.matchGrammar(e,t,n,v,w,!0,u),s)break}else if(s)break}}}}},tokenize:function(e,t){var n=[e],i=t.rest;if(i){for(var a in i)t[a]=i[a];delete t.rest}return r.matchGrammar(e,n,t,0,0,!1),n},hooks:{all:{},add:function(e,t){var n=r.hooks.all;n[e]=n[e]||[],n[e].push(t)},run:function(e,t){var n=r.hooks.all[e];if(n&&n.length)for(var i,a=0;i=n[a++];)i(t)}},Token:i};function i(e,t,n,r,i){this.type=e,this.content=t,this.alias=n,this.length=0|(r||"").length,this.greedy=!!i}if(e.Prism=r,i.stringify=function(e,t){if("string"==typeof e)return e;if(Array.isArray(e))return e.map(function(e){return i.stringify(e,t)}).join("");var n={type:e.type,content:i.stringify(e.content,t),tag:"span",classes:["token",e.type],attributes:{},language:t};if(e.alias){var a=Array.isArray(e.alias)?e.alias:[e.alias];Array.prototype.push.apply(n.classes,a)}r.hooks.run("wrap",n);var o=Object.keys(n.attributes).map(function(e){return e+'="'+(n.attributes[e]||"").replace(/"/g,"&quot;")+'"'}).join(" ");return"<"+n.tag+' class="'+n.classes.join(" ")+'"'+(o?" "+o:"")+">"+n.content+"</"+n.tag+">"},!e.document)return e.addEventListener&&(r.disableWorkerMessageHandler||e.addEventListener("message",function(t){var n=JSON.parse(t.data),i=n.language,a=n.code,o=n.immediateClose;e.postMessage(r.highlight(a,r.languages[i],i)),o&&e.close()},!1)),r;var a=document.currentScript||[].slice.call(document.getElementsByTagName("script")).pop();return a&&(r.filename=a.src,r.manual||a.hasAttribute("data-manual")||("loading"!==document.readyState?window.requestAnimationFrame?window.requestAnimationFrame(r.highlightAll):window.setTimeout(r.highlightAll,16):document.addEventListener("DOMContentLoaded",r.highlightAll))),r}(_self);"undefined"!=typeof module&&module.exports&&(module.exports=Prism),"undefined"!=typeof global&&(global.Prism=Prism),Prism.languages.python={comment:{pattern:/(^|[^\\])#.*/,lookbehind:!0},"string-interpolation":{pattern:/(?:f|rf|fr)(?:("""|''')[\s\S]+?\1|("|')(?:\\.|(?!\2)[^\\\r\n])*\2)/i,greedy:!0,inside:{interpolation:{pattern:/((?:^|[^{])(?:{{)*){(?!{)(?:[^{}]|{(?!{)(?:[^{}]|{(?!{)(?:[^{}])+})+})+}/,lookbehind:!0,inside:{"format-spec":{pattern:/(:)[^:(){}]+(?=}$)/,lookbehind:!0},"conversion-option":{pattern:/![sra](?=[:}]$)/,alias:"punctuation"},rest:null}},string:/[\s\S]+/}},"triple-quoted-string":{pattern:/(?:[rub]|rb|br)?("""|''')[\s\S]+?\1/i,greedy:!0,alias:"string"},string:{pattern:/(?:[rub]|rb|br)?("|')(?:\\.|(?!\1)[^\\\r\n])*\1/i,greedy:!0},function:{pattern:/((?:^|\s)def[ \t]+)[a-zA-Z_]\w*(?=\s*\()/g,lookbehind:!0},"class-name":{pattern:/(\bclass\s+)\w+/i,lookbehind:!0},decorator:{pattern:/(^\s*)@\w+(?:\.\w+)*/i,lookbehind:!0,alias:["annotation","punctuation"],inside:{punctuation:/\./}},keyword:/\b(?:and|as|assert|async|await|break|class|continue|def|del|elif|else|except|exec|finally|for|from|global|if|import|in|is|lambda|nonlocal|not|or|pass|print|raise|return|try|while|with|yield)\b/,builtin:/\b(?:__import__|abs|all|any|apply|ascii|basestring|bin|bool|buffer|bytearray|bytes|callable|chr|classmethod|cmp|coerce|compile|complex|delattr|dict|dir|divmod|enumerate|eval|execfile|file|filter|float|format|frozenset|getattr|globals|hasattr|hash|help|hex|id|input|int|intern|isinstance|issubclass|iter|len|list|locals|long|map|max|memoryview|min|next|object|oct|open|ord|pow|property|range|raw_input|reduce|reload|repr|reversed|round|set|setattr|slice|sorted|staticmethod|str|sum|super|tuple|type|unichr|unicode|vars|xrange|zip)\b/,boolean:/\b(?:True|False|None)\b/,number:/(?:\b(?=\d)|\B(?=\.))(?:0[bo])?(?:(?:\d|0x[\da-f])[\da-f]*\.?\d*|\.\d+)(?:e[+-]?\d+)?j?\b/i,operator:/[-+%=]=?|!=|\*\*?=?|\/\/?=?|<[<=>]?|>[=>]?|[&|^~]/,punctuation:/[{}[\];(),.:]/},Prism.languages.python["string-interpolation"].inside.interpolation.inside.rest=Prism.languages.python,Prism.languages.py=Prism.languages.python,function(){if("undefined"!=typeof self&&self.Prism&&self.document&&document.querySelector){var e,t=function(){if(void 0===e){var t=document.createElement("div");t.style.fontSize="13px",t.style.lineHeight="1.5",t.style.padding=0,t.style.border=0,t.innerHTML="&nbsp;<br />&nbsp;",document.body.appendChild(t),e=38===t.offsetHeight,document.body.removeChild(t)}return e},n=0;Prism.hooks.add("before-sanity-check",function(e){var t=e.element.parentNode,n=t&&t.getAttribute("data-line");if(t&&n&&/pre/i.test(t.nodeName)){var i=0;r(".line-highlight",t).forEach(function(e){i+=e.textContent.length,e.parentNode.removeChild(e)}),i&&/^( \n)+$/.test(e.code.slice(-i))&&(e.code=e.code.slice(0,-i))}}),Prism.hooks.add("complete",function e(t){var r=t.element.parentNode,a=r&&r.getAttribute("data-line");if(r&&a&&/pre/i.test(r.nodeName)){clearTimeout(n);var l=Prism.plugins.lineNumbers,u=t.plugins&&t.plugins.lineNumbers;i(r,"line-numbers")&&l&&!u?Prism.hooks.add("line-numbers",e):(o(r,a)(),n=setTimeout(s,1))}}),window.addEventListener("hashchange",s),window.addEventListener("resize",function(){var e=[];r("pre[data-line]").forEach(function(t){e.push(o(t))}),e.forEach(a)})}function r(e,t){return Array.prototype.slice.call((t||document).querySelectorAll(e))}function i(e,t){return t=" "+t+" ",-1<(" "+e.className+" ").replace(/[\n\t]/g," ").indexOf(t)}function a(e){e()}function o(e,n,r){var o=(n="string"==typeof n?n:e.getAttribute("data-line")).replace(/\s+/g,"").split(","),s=+e.getAttribute("data-line-offset")||0,l=(t()?parseInt:parseFloat)(getComputedStyle(e).lineHeight),u=i(e,"line-numbers"),c=u?e:e.querySelector("code")||e,d=[];return o.forEach(function(t){var n=t.split("-"),i=+n[0],a=+n[1]||i,o=e.querySelector('.line-highlight[data-range="'+t+'"]')||document.createElement("div");if(d.push(function(){o.setAttribute("aria-hidden","true"),o.setAttribute("data-range",t),o.className=(r||"")+" line-highlight"}),u&&Prism.plugins.lineNumbers){var g=Prism.plugins.lineNumbers.getLine(e,i),p=Prism.plugins.lineNumbers.getLine(e,a);if(g){var f=g.offsetTop+"px";d.push(function(){o.style.top=f})}if(p){var m=p.offsetTop-g.offsetTop+p.offsetHeight+"px";d.push(function(){o.style.height=m})}}else d.push(function(){o.setAttribute("data-start",i),i<a&&o.setAttribute("data-end",a),o.style.top=(i-s-1)*l+"px",o.textContent=new Array(a-i+2).join(" \n")});d.push(function(){c.appendChild(o)})}),function(){d.forEach(a)}}function s(){var e=location.hash.slice(1);r(".temporary.line-highlight").forEach(function(e){e.parentNode.removeChild(e)});var t=(e.match(/\.([\d,-]+)$/)||[,""])[1];if(t&&!document.getElementById(e)){var n=e.slice(0,e.lastIndexOf(".")),i=document.getElementById(n);i&&(i.hasAttribute("data-line")||i.setAttribute("data-line",""),o(i,t,"temporary ")(),document.querySelector(".temporary.line-highlight").scrollIntoView())}}}(),function(){if("undefined"!=typeof self&&self.Prism&&self.document){var e="line-numbers",t=/\n(?!$)/g,n=function(e){var n=r(e)["white-space"];if("pre-wrap"===n||"pre-line"===n){var i=e.querySelector("code"),a=e.querySelector(".line-numbers-rows"),o=e.querySelector(".line-numbers-sizer"),s=i.textContent.split(t);o||((o=document.createElement("span")).className="line-numbers-sizer",i.appendChild(o)),o.style.display="block",s.forEach(function(e,t){o.textContent=e||"\n";var n=o.getBoundingClientRect().height;a.children[t].style.height=n+"px"}),o.textContent="",o.style.display="none"}},r=function(e){return e?window.getComputedStyle?getComputedStyle(e):e.currentStyle||null:null};window.addEventListener("resize",function(){Array.prototype.forEach.call(document.querySelectorAll("pre."+e),n)}),Prism.hooks.add("complete",function(e){if(e.code){var r=e.element,i=r.parentNode;if(i&&/pre/i.test(i.nodeName)&&!r.querySelector(".line-numbers-rows")){for(var a=!1,o=/(?:^|\s)line-numbers(?:\s|$)/,s=r;s;s=s.parentNode)if(o.test(s.className)){a=!0;break}if(a){r.className=r.className.replace(o," "),o.test(i.className)||(i.className+=" line-numbers");var l,u=e.code.match(t),c=u?u.length+1:1,d=new Array(c+1).join("<span></span>");(l=document.createElement("span")).setAttribute("aria-hidden","true"),l.className="line-numbers-rows",l.innerHTML=d,i.hasAttribute("data-start")&&(i.style.counterReset="linenumber "+(parseInt(i.getAttribute("data-start"),10)-1)),e.element.appendChild(l),n(i),Prism.hooks.run("line-numbers",e)}}}}),Prism.hooks.add("line-numbers",function(e){e.plugins=e.plugins||{},e.plugins.lineNumbers=!0}),Prism.plugins.lineNumbers={getLine:function(t,n){if("PRE"===t.tagName&&t.classList.contains(e)){var r=t.querySelector(".line-numbers-rows"),i=parseInt(t.getAttribute("data-start"),10)||1,a=i+(r.children.length-1);n<i&&(n=i),a<n&&(n=a);var o=n-i;return r.children[o]}}}}}(),function(){var e=Object.assign||function(e,t){for(var n in t)t.hasOwnProperty(n)&&(e[n]=t[n]);return e};function t(t){this.defaults=e({},t)}function n(e){for(var t=0,n=0;n<e.length;++n)e.charCodeAt(n)=="\t".charCodeAt(0)&&(t+=3);return e.length+t}t.prototype={setDefaults:function(t){this.defaults=e(this.defaults,t)},normalize:function(t,n){for(var r in n=e(this.defaults,n)){var i=r.replace(/-(\w)/g,function(e,t){return t.toUpperCase()});"normalize"!==r&&"setDefaults"!==i&&n[r]&&this[i]&&(t=this[i].call(this,t,n[r]))}return t},leftTrim:function(e){return e.replace(/^\s+/,"")},rightTrim:function(e){return e.replace(/\s+$/,"")},tabsToSpaces:function(e,t){return t=0|t||4,e.replace(/\t/g,new Array(++t).join(" "))},spacesToTabs:function(e,t){return t=0|t||4,e.replace(RegExp(" {"+t+"}","g"),"\t")},removeTrailing:function(e){return e.replace(/\s*?$/gm,"")},removeInitialLineFeed:function(e){return e.replace(/^(?:\r?\n|\r)/,"")},removeIndent:function(e){var t=e.match(/^[^\S\n\r]*(?=\S)/gm);return t&&t[0].length?(t.sort(function(e,t){return e.length-t.length}),t[0].length?e.replace(RegExp("^"+t[0],"gm"),""):e):e},indent:function(e,t){return e.replace(/^[^\S\n\r]*(?=\S)/gm,new Array(++t).join("\t")+"$&")},breakLines:function(e,t){t=!0===t?80:0|t||80;for(var r=e.split("\n"),i=0;i<r.length;++i)if(!(n(r[i])<=t)){for(var a=r[i].split(/(\s+)/g),o=0,s=0;s<a.length;++s){var l=n(a[s]);t<(o+=l)&&(a[s]="\n"+a[s],o=l)}r[i]=a.join("")}return r.join("\n")}},"undefined"!=typeof module&&module.exports&&(module.exports=t),void 0!==Prism&&(Prism.plugins.NormalizeWhitespace=new t({"remove-trailing":!0,"remove-indent":!0,"left-trim":!0,"right-trim":!0}),Prism.hooks.add("before-sanity-check",function(e){var t=Prism.plugins.NormalizeWhitespace;if(!e.settings||!1!==e.settings["whitespace-normalization"])if(e.element&&e.element.parentNode||!e.code){var n=e.element.parentNode,r=/(?:^|\s)no-whitespace-normalization(?:\s|$)/;if(e.code&&n&&"pre"===n.nodeName.toLowerCase()&&!r.test(n.className)&&!r.test(e.element.className)){for(var i=n.childNodes,a="",o="",s=!1,l=0;l<i.length;++l){var u=i[l];u==e.element?s=!0:"#text"===u.nodeName&&(s?o+=u.nodeValue:a+=u.nodeValue,n.removeChild(u),--l)}if(e.element.children.length&&Prism.plugins.KeepMarkup){var c=a+e.element.innerHTML+o;e.element.innerHTML=t.normalize(c,e.settings),e.code=e.element.textContent}else e.code=a+e.code+o,e.code=t.normalize(e.code,e.settings)}}else e.code=t.normalize(e.code,e.settings)}))}();