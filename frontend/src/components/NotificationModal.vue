<template>
    <div v-if="isOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal">
            <h2>{{ title }}</h2>
            <p>{{ message }}</p>
            <button @click="closeModal" class="btn btn-primary">OK</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isOpen: Boolean, // Modal láthatóságának vezérlése
        title: String, // Cím (pl. "Sikeres módosítás")
        message: String // Üzenet szövege
    },
    methods: {
        closeModal() {
            this.$emit("close"); // Bezárás esemény küldése a szülőnek
        }
    }
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: 300px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

button {
    background-color: #5a3e1b;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #422a14;
}
</style>