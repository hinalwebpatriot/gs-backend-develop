Nova.booting((Vue, router) => {
    Vue.component('index-has-many-select-field', require('./components/IndexField'));
    Vue.component('detail-has-many-select-field', require('./components/DetailField'));
    Vue.component('form-has-many-select-field', require('./components/FormField'));
})
