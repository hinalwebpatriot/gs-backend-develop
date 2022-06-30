Nova.booting((Vue, router) => {
    Vue.component('index-has-many-single-page', require('./components/IndexField'));
    Vue.component('detail-has-many-single-page', require('./components/DetailField'));
    Vue.component('form-has-many-single-page', require('./components/FormField'));
})
