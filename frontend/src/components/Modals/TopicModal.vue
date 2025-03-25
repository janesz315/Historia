<template>
  <div  v-if="showModal" class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>
          {{ editingTopic ? "Témakör Szerkesztése" : "Új Témakör Létrehozása" }}
        </h3>
        <button @click="closeModal" class="close-btn">&times;</button>
      </div>

      <div class="modal-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-group">
            <label for="topicName">Témakör neve:</label>
            <input
              type="text"
              id="topicName"
              v-model="topic.category"
              required
              class="form-control"
              placeholder="Pl. Analízis, Programozás alapjai"
            />
          </div>

          <div class="form-group">
            <label for="topicLevel">Szint:</label>
            <select
              id="topicLevel"
              v-model="topic.level"
              required
              class="form-control"
            >
              <option value="">Válassz szintet...</option>
              <option value="közép">Közép</option>
              <option value="emelt">Emelt</option>
            </select>
          </div>

          <div class="form-group">
            <label for="topicDescription">Leírás (opcionális):</label>
            <textarea
              id="topicDescription"
              v-model="topic.text"
              class="form-control"
              rows="3"
              placeholder="Rövid leírás a témakörről (nem kötelező)"
            ></textarea>
          </div>

          <div class="form-actions">
            <button type="button" @click="closeModal" class="btn btn-secondary">
              Mégse
            </button>
            <button type="submit" class="btn btn-primary">
              {{ editingTopic ? "Mentés" : "Létrehozás" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Alap level érték:", this.topic.level);
  },
  data() {
    return {
      topic: {
        category: "",
        level: "közép", // Alapértelmezett érték
        text: "",
      },
    };
  },

  methods: {
    handleSubmit() {
      // Részletes validáció
      if (!this.topic.category?.trim()) {
        this.$emit("validation-error", "A témakör neve kötelező!");
        return;
      }

      if (!this.topic.level) {
        this.$emit("validation-error", "A szint kiválasztása kötelező!");
        return;
      }

      // Ellenőrzés, hogy a level érvényes érték-e
      const validLevels = ["közép", "emelt"];
      if (!validLevels.includes(this.topic.level)) {
        this.$emit("validation-error", "Érvénytelen szint!");
        return;
      }

      this.$emit("submit", {
        category: this.topic.category.trim(),
        level: this.topic.level, // Itt már biztos, hogy van érték
        text: this.topic.text.trim(),
      });
    },
  },
  watch: {
    "topic.level"(newVal) {
      console.log("Szint változás:", newVal);
    },
  },
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1050;
}

.modal-content {
  background-color: white;
  padding: 25px;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  animation: modalFadeIn 0.3s ease-out;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eee;
}

.modal-header h3 {
  margin: 0;
  color: #2c3e50;
  font-size: 1.4rem;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6c757d;
  transition: color 0.2s;
}

.close-btn:hover {
  color: #2c3e50;
}

.form-group {
  margin-bottom: 1.2rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #495057;
}

.form-control {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.15s;
}

.form-control:focus {
  border-color: #80bdff;
  outline: 0;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

textarea.form-control {
  min-height: 100px;
  resize: vertical;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid #eee;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
  border: none;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

.btn-primary {
  background-color: #28a745;
  color: white;
  border: none;
}

.btn-primary:hover {
  background-color: #218838;
}

@keyframes modalFadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>