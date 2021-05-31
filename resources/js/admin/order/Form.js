import AppForm from '../app-components/Form/AppForm';

Vue.component('order-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                product_id:  '' ,
                user_id:  '' ,
                
            }
        }
    }

});