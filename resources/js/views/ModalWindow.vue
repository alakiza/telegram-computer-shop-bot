<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">

                <div class="modal-header">
                    <b-container fluid>
                        <b-row>
                            <b-col cols=10>
                                <slot name="header">
                                    default header
                                </slot>
                            </b-col>
                            <b-col cols=2>
                                <b-button-close class="modal-default-button" @click="$emit('close')">
                            
                                </b-button-close>
                            </b-col>
                        </b-row>
                    </b-container>
                </div>

                <div class="modal-body">
                <slot name="body">
                    default body
                </slot>
                </div>

                <div class="modal-footer">
                <slot name="footer">
                    default footer
                </slot>
                </div>
            </div>
        </div>
    </div>    
</template>
<script>
export default {

}
</script>
<style>
@import url('../../css/ModalWindow.css');
</style>