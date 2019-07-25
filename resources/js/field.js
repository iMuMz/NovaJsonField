Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-json-field', require('./components/IndexField'))
    Vue.component('detail-nova-json-field', require('./components/DetailField'))
    Vue.component('form-nova-json-field', require('./components/FormField'))
})
