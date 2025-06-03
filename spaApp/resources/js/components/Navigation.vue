<template>
    <div class="container">
        <h1>Navigation</h1><br>

        <router-link :to="{ name: 'home'} ">Home</router-link>
        <router-link v-if="!isLogged" :to="{ name: 'login'} ">Login</router-link>
        <a v-if="isLogged" @click.prevent="logout" href="#">Logout</a>
        <router-link v-if="isLogged" :to="{ name: 'dashboard'} ">Dashboard</router-link>
        <router-view></router-view>
    </div>
</template>

<script>
import Error from "./Error.vue";
export default {
    components: { Error },
    inject: ["eventBus", "auth"],
    data() {
        return { isLogged: null };
    },
    created() {
        this.isLogged = localStorage.getItem("isLogged");
        this.eventBus.$on("isLogged", (status) => {
            console.log(status)
            this.isLogged = status;
        });
    },
    beforeDestroy() {
        this.eventBus.$off("isLogged");
    },
    methods: {
        async logout() {
            try {
                await this.auth.logout();
            } catch (err) { }
        }
    }
}
</script>
