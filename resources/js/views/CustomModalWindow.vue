<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">

                <div class="modal-header" >
                    <b-container fluid>
                        <b-row>
                            <b-col cols=10>
                                <slot name='header'>
                                    <p>Заголовок</p>
                                </slot>
                            </b-col>
                            <b-col cols=2>
                                <b-button-close class="modal-default-button" @click="close">
                            
                                </b-button-close>
                            </b-col>
                        </b-row>
                    </b-container>
                </div>

                <div class="modal-body">
                    <slot name='body'>
                        <b-container fluid>
                            <b-row>
                                <b-col cols=8>
                                    <p>Тело</p>
                                </b-col>
                            </b-row>
                        </b-container>
                    </slot>
                </div>

                <div class="modal-footer">
                    <slot name='footer'>
                        <p>footer</p>
                    </slot>
                </div>
            </div>
        </div>
    </div>    
</template>

<script>
  export default {
    name: 'modal',
    methods: {
      close() {
        this.$emit('close');
      },
    },
  };
</script>

<style>
@import url('../../css/ModalWindow.css');
</style>
