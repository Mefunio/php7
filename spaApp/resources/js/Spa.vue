<template>
    <div class="spa-container">
        <navigation/>
        <main class="main-content">
            <router-view/>
        </main>
        <error/>
    </div>
</template>

<script>
import Navigation from "./components/Navigation.vue";
import Error from "./components/Error.vue";

export default {
    inject: ["auth"],
    created() {
        axios.interceptors.response.use(
            response => {return response},
            error => {
                if (error.response.status === 401 || error.response.status === 419) {
                    this.auth.reset();
                }
                return Promise.reject(error);
            }
        );
    },
    components: {Error, Navigation}
}
</script>

<style scoped>
.spa-container {
    min-height: 100vh;
    background-color: #f8f9fa;
}

.main-content {
    padding: 20px 0;
}
</style>
