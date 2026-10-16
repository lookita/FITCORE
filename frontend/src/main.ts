import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index.ts'
import 'bootstrap-icons/font/bootstrap-icons.css'


createApp(App).use(router).mount('#app')
