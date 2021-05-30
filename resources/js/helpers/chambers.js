import { makeRequest } from './helper_base.js'

export function getItems(params) {
    params.url = '/api/chambers/get'
    params.method = 'get'
    makeRequest(params)
}

export function updateItems(params) {
    params.url = '/api/chambers/update'
    params.method = 'put'
    makeRequest(params)
}

export function deleteItems(params) {
    params.url = '/api/chambers/delete'
    params.method = 'post'
    makeRequest(params)
}
