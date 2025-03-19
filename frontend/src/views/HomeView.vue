<template>
  <div class="container">
    <h1 class="title">Kezdőlap</h1>

    <div v-if="!user" class="card">
      <p>Üdvözlünk! Kérlek, jelentkezz be, vagy regisztrálj!</p>
      <div class="btn-group">
        <router-link to="/bejelentkezes" class="btn">Bejelentkezés</router-link>
        <router-link to="/regisztracio" class="btn">Regisztráció</router-link>
      </div>
    </div>

    <div v-else-if="roleId === 2" class="card">
      <p>Üdvözlünk, <strong>{{ user }}</strong>! Itt találhatod a személyes információidat.</p>
      <router-link to="/temakorok" class="btn">Témakörök</router-link>
    </div>

    <div v-else-if="roleId === 1" class="card">
      <p>Üdvözlünk, <strong>admin</strong>! Itt kezelheted a felhasználókat és a tartalmakat.</p>
      <div class="btn-group">
        <router-link to="/temakorokadmin" class="btn">Admin Témakörök</router-link>
        <router-link to="/tesztekadmin" class="btn">Admin Tesztek</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from "@/stores/useAuthStore.js";

export default {
  setup() {
    const store = useAuthStore();
    return {
      user: store.user,
      roleId: store.roleId,
    };
  },
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 80vh;
  padding: 20px;
}

.title {
  font-size: 2rem;
  margin-bottom: 20px;
}

.card {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
  max-width: 400px;
  width: 100%;
}

.btn-group {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 15px;
}

.btn {
  background: #007bff;
  color: white;
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
  transition: background 0.3s;
}

.btn:hover {
  background: #0056b3;
}
</style>
