import axios from 'axios';
import Cookies from 'js-cookie';
import apiBaseURL from './api-config'; // Import the base URL from api-config.js


const instance = axios.create({
    baseURL: apiBaseURL,
});

instance.interceptors.request.use((config) => {
    const authToken = Cookies.get('token');
    if (authToken) {
        console.log("env ", process.env.VUE_APP_API_BASE_URL)
        config.headers['Authorization'] = `Bearer ${authToken}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

export default instance;

