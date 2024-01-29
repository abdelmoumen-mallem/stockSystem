import './bootstrap'

import '../css/app.css'

import { createApp } from 'vue'

import App from './App.vue'

import router from './plugins/router'

import vuetify from './plugins/vuetify'

createApp(App)
  .use(router)
  .use(vuetify)
  .mount('#app')

