import { makeRequest } from './helper_base.js'

export function login(params) {
    params.url = '/auth/login'
    params.method = 'post'
    makeRequest(params)
}

export function logout(params) {
    params.url = '/auth/logout'
    params.method = 'post'
    makeRequest(params)
}

export function register(params) {
    params.url = '/auth/register'
    params.method = 'post'
    makeRequest(params)
}