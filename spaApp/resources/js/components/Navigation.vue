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
    export default {
        data() {
            return { isLogged: null };
        },
        created() {
            this.isLogged = localStorage.getItem("isLogged");
            this.$root.$on("isLogged", (status) => {
                this.isLogged = status;
            });
        },
        beforeDestroy() {
            this.$root.$off("isLogged");
        },
    methods: {
        async logout() {
            try {
            await axios.post("/api/logout");
            localStorage.removeItem("isLogged");
            this.isLogged = null;
            if (this.$route.name !== "home") {
                await this.$router.push({name: "home"});
            }
            } catch (err) {
                console.error("Logout failed:", err);
            }
        }
        }
    }
</script>
