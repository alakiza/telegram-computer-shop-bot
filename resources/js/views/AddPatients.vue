<template>
    <custom-add-modal  @close="close" @addButtonClick="OnAddButtonClick">
        <template v-slot:header>
            <p>Добавление пациента</p>
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
                <b-row cols=2>
                    <b-col>
                        <b-form-input v-model="new_data.card_number" placeholder="Номер карты"></b-form-input>
                    </b-col>
                    <b-col>
                        <b-form-select v-model="new_data.chamber" :options="chambers_list">
                            <template #first>
                                <b-form-select-option :value="null" disabled>-- Палата --</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <b-form-select v-model="new_data.id_doctors" :options="doctors_list">
                            <template #first>
                                <b-form-select-option :value="null" disabled>-- Лечащий врач --</b-form-select-option>
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
import * as patient_helper from '../helpers/patients.js'

export default {
    components: {
        'custom-add-modal': customAddModal,
    },
    data: function() {
        return {
            chambers_list: [],
            doctors_list: [],

            new_data: {
                surname: null,
                name: null,
                patronymic: null,
                card_number: null,
                chamber: null,
                id_doctors: null,
            },
        }
    },
    props: {
        old_data: Object,
        chambers: Array,
        doctors: Array,
    },
    methods: {
        OnAddButtonClick: function(event) {
            if(this.old_data !== null) {
                patient_helper.updateItems({
                    data: {
                        id: this.old_data.id,
                        new: this.new_data
                    },
                    then: () => {
                        this.$emit('close')
                    }
                })
            } else {
                patient_helper.addItems({
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

        this.chambers_list = []
        this.chambers.forEach((item) => {
            this.chambers_list.push({
                value: item.id,
                text: item.chamber_num
            })
        })

        this.doctors_list = []
        this.doctors.forEach((item) => {
            this.doctors_list.push({
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
