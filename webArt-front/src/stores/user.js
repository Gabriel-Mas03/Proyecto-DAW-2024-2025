import { defineStore } from 'pinia'
import { api } from 'boot/axios' // ← este es el axios con configuración de token

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    isLoggedIn: false,
  }),
  actions: {
    async fetchUser() {
      if (this.user) return // ⛔ No volver a pedir si ya está

      try {
        const res = await api.get('me')
        this.user = res.data
        this.isLoggedIn = true
      } catch {
        this.user = null
        this.isLoggedIn = false
      }
    },

    async logout() {
      await api.post('logout')
      this.user = null
      this.isLoggedIn = false
    },
  },
})
