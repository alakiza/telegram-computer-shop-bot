<template>
    <custom-add-modal  @close="close" @addButtonClick="OnAddButtonClick">
        <template v-slot:header>
            <p>Добавление врача</p>
        </template>

        <template v-slot:body>
            <b-container fluid>
                <b-row cols=3>
                    <b-col>
                        <b-form-input v-model="new_data.surname" placeholder="Фамилия"></b-form-input>
                    </b-col>
                    <b-col>
                        <b-form-input v-model="new_data.name" placeholder="Имя"></b-form-input>
                    </b-col>
                    <b-col>
                        <b-form-input v-model="new_data.patronymic" placeholder="Отчество"></b-form-input>
                    </b-col>
                </b-row>    
                <b-row cols=1>
                    <b-col>
                        <b-form-input v-model="new_data.telegram_user" placeholder="chat_id Телеграмм"></b-form-input>
                    </b-col>
                </b-row>
                <b-row cols=1>
                    <b-col>
                        <b-form-textarea
                        v-model="new_data.spetialization"
                        placeholder="Специализация"
                        rows="3"
                        max-rows="9"
                        ></b-form-textarea>
                    </b-col>
                </b-row>
            </b-container>
        </template>
    </custom-add-modal>   
</template>

<script>
import customAddModal from './CustomAddModalWindow'
import * as doctor_helper from '../helpers/doctors.js'

export default {
    components: {
        'custom-add-modal': customAddModal,
    },
    data: function() {
        return {
            new_data: {
                surname: null,
                name: null,
                patronymic: null,
                telegram_user: null,
                spetialization: null,
            },
        }
    },
    props: {
        old_data: Object,
    },
    methods: {
        OnAddButtonClick: function(event) {
            if(this.old_data !== null) {
                doctor_helper.updateItems({
                    data: {
                        id: this.old_data.id,
                        new: this.new_data
                    },
                    then: () => {
                        this.$emit('close')
                    }
                })
            } else {
                doctor_helper.addItems({
                    data: this.new_data,
                    then: () => {
                        this.$emit('close')
                    }
                })
            }
        },
        close: function() {
            this.$emit('close')
        },
    },
    mounted() {
        if(this.old_data !== null) {
            this.new_data = this.old_data
        }
    }
}
</script>
<style>
@import url('../../css/ModalWindow.css');

</style>
