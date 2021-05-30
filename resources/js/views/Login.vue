<template>
    <div class = login-form>
        <div class = wrapper>
            <div class = buttons-block>
                <p>Добро пожаловать!</p>
                <p>Авторизуйтесь, чтобы продолжить.</p>
                <div v-if="authError">
                    <p class="error">
                    {{authError}}
                    </p>
                </div>
                <b-form-input v-model="formLogin.email" placeholder="Адрес электронной почты"></b-form-input>
                <b-form-input type="password" v-model="formLogin.password" placeholder="Пароль"></b-form-input>
                
                <b-button type="submit" variant="primary" v-on:click="onSubmit">
                    Войти
                </b-button>
            </div>
        </div>
    </div>
</template>

<script>
import {login} from '../helpers/auth';

export default {
    data: function() {
        return {
            formLogin: {
                email: null,
                password: null,
            }
        }
    },
    methods: {
        onSubmit: function() {
          this.$store.dispatch('login');

          login({
              data: this.formLogin,
              then: res => {
                  this.$store.commit("loginSuccess", res);

                  this.$router.push({path: '/'});
              },
              catch: error => {
                  this.$store.commit("loginFailed", {error});
              },
          })
        },
    },
    computed:{
        authError(){
            return this.$store.getters.authError
        },
        registeredUser(){
            return this.$store.getters.registeredUser
        }
    }
}
</script>

<style>
    .login-form {
        background: linear-gradient(45deg, #ffffff, #3a67f8);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .login-form .wrapper {
        background: #ffffffcc;
        border-radius: 20px;
        position: relative;
        display: flex;
        width:400px;
        height: 40%;
        margin: auto auto;
        flex-direction: column;
        align-items: flex-end;
    }

    @media screen and (max-width: 400px) {
        .login-form .wrapper {
            width: 95%;
        }
    }

    .login-form .wrapper .buttons-block {
        position: relative;
        display: block;
        
        margin: auto auto;
    }

    .login-form .wrapper .buttons-block .form-control{
        margin: 10px 0px
    }

    .login-form .wrapper .buttons-block .error{
        color: red;
    }
</style>