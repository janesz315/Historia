import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    id: Number(localStorage.getItem("id")) || null,
    user: localStorage.getItem("user") || null,
    token: localStorage.getItem("currentToken") || null,
    roleId: Number(localStorage.getItem("roleId")) || null, // roleId hozzáadása
  }),
  actions: {
    setId(id) {
      localStorage.setItem("id", id);
      this.id = id;
    },
    setUser(user) {
      localStorage.setItem("user", user);
      this.user = user;
    },
    setToken(token) {
      localStorage.setItem("currentToken", token);
      this.token = token;
    },
    setRoleId(roleId) {
      // Új metódus a roleId beállításához
      localStorage.setItem("roleId", roleId);
      this.roleId = roleId;
    },
    clearStoredData() {
      localStorage.removeItem("currentToken");
      localStorage.removeItem("user");
      localStorage.removeItem("id");
      localStorage.removeItem("roleId"); // roleId törlése is
      this.token = null;
      this.user = null;
      this.id = null;
      this.roleId = null;
      this.store.clearStoredData();
      console.log("Cleared token from localStorage:", localStorage.getItem("token"));
    },
  },
});
