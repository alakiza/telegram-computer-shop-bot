<template>
    <customModal @close="close">
        <template v-slot:header>
            <slot name="header">

            </slot>
        </template>

        <template v-slot:body>
            <slot name="body">
                
            </slot>
        </template>

        <template v-slot:footer>
           <b-container fluid>
                <b-button v-on:click="addButtonClick">
                    ОК
                </b-button>
            </b-container>
        </template>

    </customModal>
</template>

<script>
import customModal from './CustomModalWindow'

export default {
    extends: customModal,
    components: {
        'customModal': customModal
    },
    methods: {
        close() {
            this.$emit('close')
        },
        addButtonClick() {
            this.$emit('addButtonClick')
        },
    },
}

</script>
