import { makeRequest } from './helper_base.js'

export function getItems(params) {
    params.url = '/api/patients/get'
    params.method = 'get'
    makeRequest(params)
}

export function addItems(params) {
    params.url = '/api/patients/add'
    params.method = 'post'
    makeRequest(params)
}

export function updateItems(params) {
    params.url = '/api/patients/update'
    params.method = 'put'
    makeRequest(params)
}

export function deleteItems(params) {
    params.url = '/api/patients/delete'
    params.method = 'post'
    makeRequest(params)
}
