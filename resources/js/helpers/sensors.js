import { makeRequest } from './helper_base.js'

export function getItems(params) {
    params.url = '/api/sensors/get'
    params.method = 'get'
    makeRequest(params)
}

export function addItems(params) {
    params.url = '/api/sensors/add'
    params.method = 'post'
    makeRequest(params)
}

export function updateItems(params) {
    params.url = '/api/sensors/update'
    params.method = 'put'
    makeRequest(params)
}

export function deleteItems(params) {
    params.url = '/api/sensors/delete'
    params.method = 'post'
    makeRequest(params)
}
