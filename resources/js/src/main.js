import '@/plugins/vue-composition-api'
import '@resources/sass/styles/styles.scss'
import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import store from './store'
import Swal from 'sweetalert2'
import VuejsDialog from 'vuejs-dialog';


/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import { fas } from '@fortawesome/free-solid-svg-icons'


/* add icons to the library */
library.add(fas)

Vue.component('fas', FontAwesomeIcon)


// import axios from 'axios';
import axios from '../../js/axios';
import 'vuejs-dialog/dist/vuejs-dialog.min.css';


window.axios = axios;
window.swal = Swal;
// window.axios.defaults.withCredentials = true
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// axios.defaults.baseURL = "http://localhost:8000"

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.config.productionTip = false
Vue.use(VueSweetalert2);
Vue.use(VuejsDialog);



new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
}).$mount('#app')
