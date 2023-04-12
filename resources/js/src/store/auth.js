import Cookies from 'js-cookie';
import router from '@/router';

export default {
    state: {
        user: null,
        token: Cookies.get('token') || null,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setToken(state, token) {
            state.token = token;
            Cookies.set('token', token); // set the token in a cookie
        },
        removeToken(state) {
            state.token = null;
            Cookies.remove('token'); // remove the token from the cookie
        },
    },
    actions: {
        async login({ commit, dispatch, state }, credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                commit('setToken', response.data);
                await dispatch('fetchUser');

                // redirect based on the user role
                if (state.user.role_id == 1) {
                    router.push({ name: 'admin-dashboard' });
                } else if (state.user.role_id == 2) {
                    router.push({ name: 'registrar-dashboard' });
                }

            } catch (error) {
                throw error;
            }
        },
        async logout({ commit, state }) {
            try {
                await axios.post('/api/logout', null, {
                    headers: {
                        Authorization: `Bearer ${state.token}`,
                    },
                });
                commit('removeToken');
                commit('setUser', null);
            } catch (error) {
                throw error;
            }
        },
        async fetchUser({ commit, state }) {
            try {
                const response = await axios.get('/api/user', {
                    headers: {
                        Authorization: `Bearer ${state.token}`,
                    },
                });
                commit('setUser', response.data);
            } catch (error) {
                throw error;
            }
        },
        async isLoggedIn({ state }) {
            if (state.token) {
                try {
                    await axios.get('/api/user', {
                        headers: {
                            Authorization: `Bearer ${state.token}`,
                        },
                    });
                    return true;
                } catch (error) {
                    console.error(error);
                    return false;
                }
            } else {
                return false;
            }
        },
    },
    getters: {
        currentUser: state => state.user,
    },
};