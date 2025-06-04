<template>
    <div class="container">
        Spa component
        <navigation/>
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
