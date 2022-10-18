// import router from "../../frontend/src/router";
// import store from "../../frontend/src/store";
// import { i18n } from "../../frontend/src/i18n";
// import Vue from 'vue'
// import axios from "axios";
// import constants from "./constants";
//
// require("quill-emoji/dist/quill-emoji.css");
// // console.log(i18n)
//
// // require('./bootstrap');
//
// window.Vue = require('vue');
// Vue.config.productionTip = false
//
// // let i18n = createI18n();
//
// router.beforeEach((to, from, next) => {
//     // let lang = to.params.lang
//     // let nextParams = {};
//     // if (lang) {
//     //     i18n.locale = lang
//     //     store.commit('auth/SET_LANG', lang);
//     //     axios.defaults.headers.common['Accept-Language'] = lang
//     //     document.querySelector('html').setAttribute('lang', lang)
//     //     // TODO Ignore default locale in URL
//     // } else {
//     //     if (from.params.lang) {
//     //         console.log('from params lang')
//     //         nextParams = {
//     //             name: to.name,
//     //             params: {
//     //                 ...to.params,
//     //                 lang: from.params.lang
//     //             }
//     //         }
//     //     } else {
//     //         console.log('not lang', to)
//     //         let locale = localStorage.getItem('lang') ?? i18n.locale
//     //         nextParams = {
//     //             path: `/${locale}${to.path}`,
//     //             query: {
//     //                 ...to.query
//     //             }
//     //         }
//     //         // next({ path: `/${locale}${to.path}`, query: {
//     //         //         ...to.query,
//     //         //     }});
//     //     }
//     // }
//     // next(nextParams);
//     // if(!lang) {
//     //     console.log('???')
//     //     lang = i18n.locale
//     // }
//
//     let lang = localStorage.getItem('lang') ?? i18n.locale
//     console.log(lang)
//     i18n.locale = lang
//     store.commit('auth/SET_LANG', lang);
//     axios.defaults.headers.common['Accept-Language'] = lang
//     document.querySelector('html').setAttribute('lang', lang)
//     next();
//     // next()
//     // loadLanguageAsync(lang).then(() => next())
// })
//
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
//
// // Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// // Vue.component('app', require('./components/App').default);
//
// const app = new Vue({
//     el: '#app',
//     router,
//     i18n,
//     store
// });
