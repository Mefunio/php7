<template>
    <div class="container">
        Spa component
        <navigation/>
    </div>

</template>

<script>
import Navigation from "./components/Navigation.vue";

export default {
    inject: ["auth"],
    created() {
        axios.interceptors.response.use(
            response => {return response},
            error => {
                if (error.response.status === 401) {
                    this.auth.reset();
                }
                return Promise.reject(error);
            }
        );
    },
    components: {Navigation}
}
</script>
