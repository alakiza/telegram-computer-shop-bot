<template>
    <custom-add-modal  @close="close" @addButtonClick="OnAddButtonClick">
        <template v-slot:header>
            <p>Добавление датчика</p>
        </template>

        <template v-slot:body>
            <b-container fluid>
                <b-row cols=2>
                    <b-col>
                        <b-form-input v-model="new_data.name" placeholder="Название"></b-form-input>
                    </b-col>
                    <b-col>
                        <b-form-input v-model="new_data.ip" placeholder="IP"></b-form-input>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <b-form-select v-model="new_data.id_patients" :options="patients_list">
                            <template #first>
                                <b-form-select-option :value="null" disabled>-- Пациент --</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-col>
                </b-row>
            </b-container>
        </template>
    </custom-add-modal>   
</template>

<script>
import customAddModal from './CustomAddModalWindow'
import * as sensor_helper from '../helpers/sensors.js'

export default {
    components: {
        'custom-add-modal': customAddModal,
    },
    data: function() {
        return {
            patients_list: [],

            new_data: {
                name: null,
                ip: null,
                id_patients: null,
            },
        }
    },
    props: {
        old_data: Object,
        patients: Array,
    },
    methods: {
        OnAddButtonClick: function(event) {
            if(this.old_data !== null) {
                sensor_helper.updateItems({
                    data: {
                        id: this.old_data.id,
                        new: this.new_data
                    },
                    then: () => {
                        this.$emit('close')
                    }
                })
            } else {
                sensor_helper.addItems({
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

        this.patients_list = []
        this.patients.forEach((item) => {
            this.patients_list.push({
                value: item.id,
                text: item.surname + " " + item.name + " " + item.patronymic
            })
        })
    }
}
</script>
<style>
@import url('../../css/ModalWindow.css');

</style>
