import { store } from './store.js'

export const authInterceptor = (config) => {
    const token = localStorage.getItem('token')
    console.log(token);

    if (token) {
        config.headers.Authorization = 'Bearer ' + token
    }

    return config
}

export const errorInterceptor = async error => {
    if (!error.response) {
        console.error('**Network/Server error')
        console.log(error.response)
        return Promise.reject(error)
    }

    switch (error.response.status) {
        case 400:
            console.error(error.response.status, error.message)
            break

        case 401:
            store.commit("logout", error.response);
            break

        case 403:
            console.error(error.response.status, error.message)
            store.commit("logout", error.response);
            
            break
    }
    return Promise.reject(error)
}