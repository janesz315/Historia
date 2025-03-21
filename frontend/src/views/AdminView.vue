<template>
  <div class="my-container">
    <div class="admin-container">
      <h2 class="title">Felhasználók kezelése</h2>
      <table class="table user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Felhasználónév</th>
            <th>Szerepkör</th>
            <th>Művelet</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>
              <select v-model="user.roleId" class="form-select role-select">
                <option v-for="role in roles" :key="role.id" :value="role.id">
                  {{ role.role }}
                </option>
              </select>
            </td>
            <td>
              <button class="btn save-btn" @click="updateRole(user)">Mentés</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: [],
      roles: [
        { id: 1, role: 'admin' },
        { id: 2, role: 'user' }
      ]
    };
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/users');
        this.users = response.data;
      } catch (error) {
        console.error('Hiba a felhasználók lekérésekor:', error);
      }
    },
    async updateRole(user) {
      try {
        await axios.put(`http://127.0.0.1:8000/api/users/${user.id}/role`, {
          roleId: user.roleId
        });
        alert('Szerepkör sikeresen frissítve!');
      } catch (error) {
        console.error('Hiba a szerepkör frissítésekor:', error);
      }
    }
  },
  mounted() {
    this.fetchUsers();
  }
};
</script>

<style scoped>
.my-container {
  background-image: url("/images/parchment-texture.jpg");
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  min-height: 90vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.admin-container {
  max-width: 900px;
  background: rgba(255, 248, 220, 0.9);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border: 2px solid #8b5a2b;
}

.title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  color: #5a3e1b;
  text-align: center;
}

.user-table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255, 248, 220, 0.9);
}

.user-table th,
.user-table td {
  padding: 10px;
  border: 2px solid #8b5a2b;
  text-align: center;
  color: #5a3e1b;
}

.user-table th {
  background-color: #8b5a2b;
  color: white;
}

.role-select {
  background: #fff8dc;
  border: 1px solid #8b5a2b;
  color: #5a3e1b;
  padding: 5px;
  border-radius: 5px;
}

.save-btn {
  background: #8b5a2b;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  transition: 0.3s;
}

.save-btn:hover {
  background: #5a3e1b;
}
</style>
