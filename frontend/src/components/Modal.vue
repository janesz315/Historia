<!-- <template>
    <div class="modal fade show" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true"
        style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Értesítés</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        @click="close"></button>
                </div>
                <div class="modal-body">
                    {{ message }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="close">Rendben</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        message: String,
    },
    methods: {
        close() {
            this.$emit('close'); // Trigger 'close' event
        },
    },
};
</script>

<style scoped>
.modal {
    display: block !important;
}
</style> -->