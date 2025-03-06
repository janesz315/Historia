import { defineStore } from 'pinia'
 
export const useAuthStore = defineStore('auth', {
  //Ezek a változók
  state: () => ({
    id: Number(localStorage.getItem('id')) || null,
    user: localStorage.getItem('user') || null,
    token: localStorage.getItem('currentToken') || null
  }),
  //valamilyen formában visszaadja
  getters: {},
  //csinál vele valamit
  actions: {
    setId(id) {
      localStorage.setItem("id", id)
      this.id=id
    },
    setUser(user) {
      localStorage.setItem('user', user)
      this.user = user
  },
  setToken(token) {
      localStorage.setItem('currentToken', token)
      this.token = token
  },
  clearStoredData() {
      localStorage.removeItem('currentToken')
      localStorage.removeItem('user')
      localStorage.removeItem('id')
      this.token = null
      this.user = null
      this.id = null
  },
  // setErrors(errors) {
  //     this.errors = errors
  // },
  // clearErrors() {
  //     this.errors = null
  // }
  }
});
