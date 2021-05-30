import { makeRequest } from './helper_base.js'

export function getItems(params) {
    params.url = '/api/doctors/get'
    params.method = 'get'
    makeRequest(params)
}

export function addItems(params) {
    params.url = '/api/doctors/add'
    params.method = 'post'
    makeRequest(params)
}

export function updateItems(params) {
    params.url = '/api/doctors/update'
    params.method = 'put'
    makeRequest(params)
}

export function deleteItems(params) {
    params.url = '/api/doctors/delete'
    params.method = 'post'
    makeRequest(params)
}
