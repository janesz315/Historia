<template>
  <div>
    <h1>Kezdőlap</h1>
    
    <!-- Ha nincs bejelentkezve, megjelenik egy üzenet -->
    <div v-if="!user">
      <p>Üdvözlünk! Kérlek, jelentkezz be, vagy regisztrálj!</p>
      <router-link to="/bejelentkezes">Bejelentkezés</router-link>
      <router-link to="/regisztracio">Regisztráció</router-link>
    </div>

    <!-- Ha felhasználó van bejelentkezve -->
    <div v-else-if="roleId === 2">
      <p>Üdvözlünk, {{ user }}! Itt találhatod a személyes információidat.</p>
      <router-link to="/temakorok">Témakörök</router-link>
    </div>

    <!-- Ha admin van bejelentkezve -->
    <div v-else-if="roleId === 1">
      <p>Üdvözlünk, admin! Itt kezelheted a felhasználókat és a tartalmakat.</p>
      <router-link to="/temakorokadmin">Admin Témakörök</router-link>
      <router-link to="/tesztekadmin">Admin Tesztek</router-link>
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