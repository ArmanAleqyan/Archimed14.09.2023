!function(e){var n={};function t(r){if(n[r])return n[r].exports;var o=n[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,t),o.l=!0,o.exports}t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=1)}([function(e,n){e.exports=function(e,n,t,r,o,s){var a,i=e=e||{},l=typeof e.default;"object"!==l&&"function"!==l||(a=e,i=e.default);var u,d="function"==typeof i?i.options:i;if(n&&(d.render=n.render,d.staticRenderFns=n.staticRenderFns,d._compiled=!0),t&&(d.functional=!0),o&&(d._scopeId=o),s?(u=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),r&&r.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},d._ssrRegister=u):r&&(u=r),u){var c=d.functional,f=c?d.render:d.beforeCreate;c?(d._injectStyles=u,d.render=function(e,n){return u.call(n),f(e,n)}):d.beforeCreate=f?[].concat(f,u):[u]}return{esModule:a,exports:i,options:d}}},function(e,n,t){e.exports=t(2)},function(e,n,t){Nova.booting(function(e,n){e.component("index-nova-grouped-field",t(3)),e.component("detail-nova-grouped-field",t(6))})},function(e,n,t){var r=t(0)(t(4),t(5),!1,null,null,null);e.exports=r.exports},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default={props:["resource","resourceName","resourceId","field"]}},function(e,n){e.exports={render:function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",e._l(e.field.fields,function(n,r){return t("span",{key:r},[n.value&&n.belongsToId?t("router-link",{staticClass:"no-underline font-bold dim text-primary",attrs:{to:{name:"detail",params:{resourceName:n.resourceName,resourceId:n.belongsToId}}}},[e.field.showLabels?t("span",[e._v(e._s(n.name)+": ")]):e._e(),e._v("\n            "+e._s(n.value)+"\n        ")]):t("span",[e.field.showLabels?t("span",[e._v(e._s(n.name)+": ")]):e._e(),e._v("\n            "+e._s(n.value)+"\n        ")]),e._v(" "),r+1!=e.field.fields.length?t("span",{domProps:{innerHTML:e._s(e.field.separator)}}):e._e()],1)}),0)},staticRenderFns:[]}},function(e,n,t){var r=t(0)(t(7),t(8),!1,null,null,null);e.exports=r.exports},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default={props:["resource","resourceName","resourceId","field"],methods:{showNonLinkValue:function(e){return"date"==e.component&&e.format?moment(e.value).format(e.format):e.value}}}},function(e,n){e.exports={render:function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",[t("div",{staticClass:"flex border-b border-40"},[t("div",{staticClass:"w-1/4 py-4"},[t("h4",{staticClass:"font-normal text-80"},[t("p",[e._v(e._s(e.field.name))])])]),e._v(" "),t("div",{staticClass:"w-3/4 py-4"},[t("p",{staticClass:"text-90"},e._l(e.field.fields,function(n,r){return t("span",{key:r},[n.value?t("span",[n.value&&n.belongsToId&&!e.field.removeLinks?t("router-link",{staticClass:"no-underline font-bold dim text-primary",attrs:{to:{name:"detail",params:{resourceName:n.resourceName,resourceId:n.belongsToId}}}},[e.field.showLabels?t("span",[e._v(e._s(n.name)+": ")]):e._e(),e._v("\n                        "+e._s(n.value)+"\n                    ")]):t("span",[e.field.showLabels?t("span",[e._v(e._s(n.name)+": ")]):e._e(),e._v(" "),t("span",{domProps:{innerHTML:e._s(e.showNonLinkValue(n))}})]),e._v(" "),r+1!=e.field.fields.length?t("span",{domProps:{innerHTML:e._s(e.field.separator)}}):e._e()],1):e._e()])}),0)])])])},staticRenderFns:[]}}]);