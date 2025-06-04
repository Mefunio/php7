<template>
    <div v-if="isError" class="modal">
        <div class="modal-content">
            <h3>Error</h3>
            <h6 class="text-justify">
                {{ errorMessage }}
            </h6>
            <div class="d-flex mt-3">
                <button @click.prevent="hide" class="btn btn-light mr-3">Close</button>
                <a href="/" class="btn btn-light">Home</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    inject: ["eventBus"],
    created() {
        this.eventBus.$on("error", this.handleError);
    },
    beforeDestroy() {
        this.eventBus.$off("error");
    },
    data() {
        return {
            isError: false,
            errorMessage: "",
        };
    },
    methods: {
        hide() {
            this.isError = false;
        },
        handleError(error) {
            this.isError = true;
            if (error.response) {
                this.errorMessage = `${error.response.statusText} - ${error.response.status}`;
            } else {
                this.errorMessage = "HTTP Error!";
            }
            console.error("Error:", error);
        }
    },
    watch: {
        $route(to, from) {
            this.hide()
        }
    }
};
</script>

<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 90%;
}

.modal-content h3 {
    color: #e74c3c;
    margin-bottom: 1rem;
}

.modal-content h6 {
    margin-bottom: 1rem;
    color: #333;
}

.btn {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    background: #f8f9fa;
    text-decoration: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn:hover {
    background: #e9ecef;
}
</style>
