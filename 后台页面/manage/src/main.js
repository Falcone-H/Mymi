import {
    createApp
} from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import installElementPlus from './plugins/element'
import Axios from 'axios'
import Global from './Global'
import './assets/css/icon.css'
const app = createApp(App)


app.config.globalProperties.$axios = Axios;

installElementPlus(app)
app
    .use(store)
    .use(router)
    .use(Global)
    .mount('#app')