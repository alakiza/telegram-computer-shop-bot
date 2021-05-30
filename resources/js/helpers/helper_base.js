import axios from 'axios';
import { authInterceptor, errorInterceptor } from '../interceptors.js'

axios.interceptors.request.use(authInterceptor)
axios.interceptors.response.use(undefined, errorInterceptor)

export function makeRequest(params) {
    axios({
        method: params.method,
        url: 'https://tele.doct.org.ua:8443' + params.url,
        data: params.data,
        params: params.params
    })
    .then(params.then)
    .catch(params.catch)
}
