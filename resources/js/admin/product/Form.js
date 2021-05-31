import AppForm from '../app-components/Form/AppForm';

Vue.component('product-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                count:  '' ,
                price:  '' ,
                category_id:  '' ,
                
            }
        }
    }

});