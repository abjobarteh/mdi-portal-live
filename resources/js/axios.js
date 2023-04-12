import axios from 'axios';
import Cookies from 'js-cookie';

const instance = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL
});

instance.interceptors.request.use((config) => {
    const authToken = Cookies.get('token');
    if (authToken) {
        config.headers['Authorization'] = `Bearer ${authToken}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

export default instance;

