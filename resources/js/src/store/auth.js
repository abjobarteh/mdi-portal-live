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
        // setToken(state, token) {
        //     const expirationTime = new Date(Date.now() + 60000); // 1 minute from now
        //     state.token = token;
        //     Cookies.set('token', token, { expires: expirationTime }); // set the token in a cookie that expires after 1 minute (60 seconds)

        //     // set a timer to redirect to the login page when the token expires
        //     const expirationMs = expirationTime.getTime() - Date.now();
        //     setTimeout(() => {
        //         localStorage.removeItem('vuex');
        //         Cookies.remove('token');
        //         window.location.href = '/login'; // replace '/login' with the URL of your login page
        //     }, expirationMs);
        // },

        setToken(state, token) {
            state.token = token;
            Cookies.set('token', token); // set the token in a cookie without an expiry date

            // set a timer to remove the token and vuex state from storage after 30 seconds of inactivity
            const inactivityTime = 120000; // 30 seconds in milliseconds
            let timer = setTimeout(() => {
                Cookies.remove('token');
                localStorage.removeItem('vuex');
                window.location.href = '/login'; // redirect to login page after removing token and vuex state
            }, inactivityTime);

            // reset the timer on user activity (e.g. click, keypress)
            const resetTimer = () => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    Cookies.remove('token');
                    localStorage.removeItem('vuex');
                    window.location.href = '/login'; // redirect to login page after removing token and vuex state
                }, inactivityTime);
            };
            window.addEventListener('click', resetTimer);
            window.addEventListener('keypress', resetTimer);
        },

        removeToken(state) {
            state.token = null;
            Cookies.remove('token'); // remove the token from the cookie
            localStorage.removeItem('vuex'); // clear the local storage
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

