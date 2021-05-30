<template>
    <b-container fluid>
        <b-row>
            <b-col>
                <b-button variant="light" id="show-modal" @click="ShowAddDialog">➕</b-button>
            </b-col>

            <add-dialog v-if="showModal" 
                v-bind:old_data="old_data"
                v-bind:patients="patients"
                @close="CloseAddDialog">
            </add-dialog>
        </b-row>
        <b-table :items="sensors_table.items" :fields="sensors_table.fields">
            <template #table-colgroup="scope">
                <col
                v-for="field in scope.fields"
                :key="field.key"
                :style="{ width: field.key === 'actions' ? '10%' : '25%' }"
                >
            </template>

            <template #cell(patient)="row">
                <p>{{patientsByID[row.item.id_patients].surname}} {{patientsByID[row.item.id_patients].name}} {{patientsByID[row.item.id_patients].patronymic}}</p>
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

import AddSensors from './AddSensors.vue'
import * as data_helper from '../helpers/data_helper.js'
import * as sensor_helper from '../helpers/sensors.js'
import * as patient_helper from '../helpers/patients.js'

export default {
    components: {
        'add-dialog': AddSensors
    },
    data: function() {
        return { 
            showModal: false,
            old_data: null,

            patients: [],
            patientsByID: [],

            sensors_table: {
                fields: [{
                            'key': 'name',
                            'label': 'Название'
                        },
                        {
                            'key': 'ip',
                            'label': 'ip'
                        },
                        {
                            'key': 'patient',
                            'label': 'Пациент'
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
            this.GetSensors()

            this.showModal = false
        },
        ShowAddDialog: function () {
            this.old_data = null

            this.showModal = true
        },
        GetPatients: function() {
            patient_helper.getItems({
                then: (response) => {
                    this.patients = response.data

                    this.patientsByID = data_helper.arrayToDictionaryByID(this.patients);
                }
            })
        },
        GetSensors: function() {
            this.sensors_table.items = []

            sensor_helper.getItems({
                then: (response) => {
                    this.sensors_table.items = []
                    this.sensors = response.data

                    this.sensors_table.items = this.sensors;
                }
            })
        },
        DeleteItem: function(row) {
            sensor_helper.deleteItems({
                data: {
                    id: row.item.id
                },
                then: () => {
                    this.GetSensors()
                }
            })
        },
        UpdateItem: function(row) {
            this.old_data = row.item

            this.showModal = true
        },
    },
    mounted() {
        this.GetPatients()
        this.GetSensors()
    }
}
</script>