<template>
    <b-container fluid>
        <b-row>
            <b-col>
                <b-button variant="light" id="show-modal" @click="ShowAddDialog">➕</b-button>
            </b-col>

            <add-dialog v-if="showModal" 
                v-bind:old_data="old_data"
                v-bind:chambers="chambers"
                v-bind:doctors="doctors"
                @close="CloseAddDialog">
            </add-dialog>
        </b-row>
        <b-table :items="patients_table.items" :fields="patients_table.fields">
            <template #table-colgroup="scope">
                <col
                v-for="field in scope.fields"
                :key="field.key"
                :style="{ width: field.key === 'actions' ? '10%' : '25%' }"
                >
            </template>

            <template #cell(doctor)="row">
                <p>{{doctorsByID[row.item.id_doctors].surname}} {{doctorsByID[row.item.id_doctors].name}} {{doctorsByID[row.item.id_doctors].patronymic}}</p>
            </template>

            <template #cell(chamber)="row">
                <p>{{chambersById[row.item.chamber].chamber_num}}</p>
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

import AddPatients from './AddPatients.vue'
import * as data_helper from '../helpers/data_helper.js'
import * as patient_helper from '../helpers/patients.js'
import * as chamber_helper from '../helpers/chambers.js'
import * as doctor_helper from '../helpers/doctors.js'

export default {
    components: {
        'add-dialog': AddPatients
    },
    data: function() {
        return { 
            showModal: false,
            old_data: null,
            chambers: [],
            chambersById: [],

            doctors: [],
            doctorsByID: [],

            patients_table: {
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
                            'key': 'card_number',
                            'label': '№ карты'
                        }, 
                        {
                            'key': 'chamber',
                            'label': 'Палата'
                        }, 
                        {
                            'key': 'doctor',
                            'label': 'Врач'
                        },
                        {
                            'key': 'receipt_date',
                            'label': 'Прибыл'
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
            this.GetPatients()

            this.showModal = false
        },
        ShowAddDialog: function () {
            this.old_data = null

            this.showModal = true
        },
        GetPatients: function() {
            this.patients_table.items = []

            patient_helper.getItems({
                then: (response) => {
                    this.patients_table.items = response.data
                }
            })
        },
        GetChambers: function() {
            chamber_helper.getItems({
                then: (response) => {
                    this.chambers = response.data

                    this.chambersById = data_helper.arrayToDictionaryByID(this.chambers)
                    console.log(this.chambersById)
                }
            })
        },
        GetDoctors: function() {
            doctor_helper.getItems({
                then: (response) => {
                    this.doctors = response.data

                    this.doctorsByID = data_helper.arrayToDictionaryByID(this.doctors);
                }
            })
        },
        DeleteItem: function(row) {
            patient_helper.deleteItems({
                data: {
                    id: row.item.id
                },
                then: () => {
                    this.GetPatients()
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
        this.GetChambers()
        this.GetPatients()
    }
}
</script>