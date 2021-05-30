<template>
    <b-container fluid>
        <b-row>
            <b-col>
                <b-button variant="light" id="show-modal" @click="ShowAddDialog">➕</b-button>
            </b-col>

            <add-dialog v-if="showModal" 
                v-bind:old_data="old_data"
                @close="CloseAddDialog">
            </add-dialog>
        </b-row>
        <b-table :items="doctors_table.items" :fields="doctors_table.fields">
            <template #table-colgroup="scope">
                <col
                v-for="field in scope.fields"
                :key="field.key"
                :style="{ width: field.key === 'actions' ? '10%' : '25%' }"
                >
            </template>

            <template #cell(actions)="row">
                <div class="close-button-wrapper">
                    <b-button class="close" @click="UpdateItem(row)">🖉</b-button>
                    <b-button-close @click="DeleteItem(row)"></b-button-close>
                </div>
            </template>
        </b-table>

    </b-container>
</template>

<script>

import AddDoctors from './AddDoctors.vue'
import * as data_helper from '../helpers/data_helper.js'
import * as doctor_helper from '../helpers/doctors.js'

export default {
    components: {
        'add-dialog': AddDoctors
    },
    data: function() {
        return { 
            showModal: false,
            old_data: null,

            doctors: [],
            doctorsByID: [],

            doctors_table: {
                fields: [{
                            'key': 'surname',
                            'label': 'Фамилия'
                        },
                        {
                            'key': 'name',
                            'label': 'Имя'
                        },
                        {
                            'key': 'patronymic',
                            'label': 'Отчество'
                        }, 
                        {
                            'key': 'telegram_user',
                            'label': 'Chat_id'
                        }, 
                        {   
                            'key': 'actions',
                            'label': 'Действия'
                        }],
                items: []

            },
        }
    },
    methods: {
        CloseAddDialog: function () {
            this.GetDoctors()

            this.showModal = false
        },
        ShowAddDialog: function () {
            this.old_data = null

            this.showModal = true
        },
        GetDoctors: function() {
            doctor_helper.getItems({
                then: (response) => {
                    this.doctors_table.items = []
                    this.doctors = response.data

                    this.doctorsByID = data_helper.arrayToDictionaryByID(this.doctors);

                    this.doctors_table.items = this.doctors;
                }
            })
        },
        DeleteItem: function(row) {
            doctor_helper.deleteItems({
                data: {
                    id: row.item.id
                },
                then: () => {
                    this.GetDoctors()
                }
            })
        },
        UpdateItem: function(row) {
            this.old_data = row.item

            this.showModal = true
        },
    },
    mounted() {
        this.GetDoctors()
    }
}
</script>