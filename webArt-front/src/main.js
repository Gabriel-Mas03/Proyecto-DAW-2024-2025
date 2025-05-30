import { createApp } from 'vue'
import { Quasar, Dialog } from 'quasar'
import App from './App.vue'
import quasarUserOptions from './quasar-user-options'

createApp(App)
  .use(Quasar, {
    plugins: {
      Dialog, // ⬅️ Aquí cargas Dialog
    },
    ...quasarUserOptions,
  })
  .mount('#app')
