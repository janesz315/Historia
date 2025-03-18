import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    id: Number(localStorage.getItem('id')) || null,
    user: localStorage.getItem('user') || null,
    token: localStorage.getItem('currentToken') || null,
    roleId: Number(localStorage.getItem('roleId')) || null,  // roleId hozzáadása
  }),
  actions: {
    setId(id) {
      localStorage.setItem('id', id);
      this.id = id;
    },
    setUser(user) {
      localStorage.setItem('user', user);
      this.user = user;
    },
    setToken(token) {
      localStorage.setItem('currentToken', token);
      this.token = token;
    },
    setRoleId(roleId) {  // Új metódus a roleId beállításához
      localStorage.setItem('roleId', roleId);
      this.roleId = roleId;
    },
    clearStoredData() {
      localStorage.removeItem('currentToken');
      localStorage.removeItem('user');
      localStorage.removeItem('id');
      localStorage.removeItem('roleId');  // roleId törlése is
      this.token = null;
      this.user = null;
      this.id = null;
      this.roleId = null;
    },
    // async updateUserData(updatedData) {
    //   try {
    //     const response = await axios.patch(`${BASE_URL}/users/${this.id}`, updatedData, {
    //       headers: {
    //         Authorization: `Bearer ${this.token}`,
    //       },
    //     });
    
    //     if (response.data && response.data.rows) {
    //       this.setUser(response.data.rows.name);
    //       localStorage.setItem("user", response.data.rows.name);
    
    //       if (updatedData.email) {
    //         // Az e-mail frissítése esetén fontos, hogy újra bejelentkezzen
    //         alert("Az e-mail címed megváltozott, kérlek jelentkezz be újra!");
    //         this.clearStoredData();
    //         window.location.reload();
    //       }
    //     }
    //   } catch (error) {
    //     console.error("Hiba a felhasználó adatainak frissítésében:", error);
    //   }
    // }
    // async updateUserData(updatedData) {
    //   try {
    //     const response = await axios.patch(`${BASE_URL}/users/${this.id}`, updatedData, {
    //       headers: {
    //         Authorization: `Bearer ${this.token}`,
    //       },
    //     });
    
    //     console.log("Backend válasz:", response.data);
    
    //     if (response.data.row) {
    //       this.setUser(response.data.row.name);
    //       localStorage.setItem("user", response.data.row.name);
    
    //       if (response.data.token) {
    //         this.setToken(response.data.token);
    //         localStorage.setItem("currentToken", response.data.token);
    //       }
    
    //       if (updatedData.email) {
    //         alert("Az e-mail címed megváltozott, kérlek jelentkezz be újra!");
    //         this.clearStoredData();
    //         window.location.reload();
    //       }
    //     }
    //   } catch (error) {
    //     console.error("Hiba a felhasználó adatainak frissítésében:", error);
    //     console.log("Részletes hiba:", error.response); // 🔍 Itt lesz a pontos hibaüzenet
    //   }
    // }
    
  }
});

 
