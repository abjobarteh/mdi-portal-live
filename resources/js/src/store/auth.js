import Cookies from 'js-cookie';
import router from '@/router';

export default {
    state: {
        user: null,
        userProfileData: [],
        token: Cookies.get('token') || null,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
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

        userProfileMutate(state, data) {
            return (state.userProfileData = data);
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
                } else if (state.user.role_id == 4) {
                    router.push({ name: 'student' });
                } else if (state.user.role_id == 5) {
                    router.push({ name: 'finance-dashboard' });
                } else if (state.user.role_id == 3) {
                    router.push({ name: 'lecturer-dashboard' });
                }


            } catch (error) {
                throw error;
            }
        },

        userProfile(context) {
            axios
                .get("/api/profile")

                .then(response => {
                    console.log(response.data)
                    context.commit("userProfileMutate", response.data); //categories will be run from mutation
                })

                .catch(() => {
                    console.log("Error........");
                });
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
        getUserProfile(state) {
            return state.userProfileData;
        },
    },
};

