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
            if (typeof error === 'string') {
                this.errorMessage = error;
                this.errorMessage = `${error.response.statusText} - ${error.response.status}`;
            } else {
                this.errorMessage = "HTTP Error";
            }
            console.error("Error:", this.errorMessage);
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

</style>
