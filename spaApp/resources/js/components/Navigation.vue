<template>
    <div class="container">
        <h1>Navigation</h1><br>

        <router-link :to="{ name: 'home'} ">Home</router-link>
        <router-link :to="{ name: 'login'} ">Login</router-link>
        <a @click.prevent="logout" href="#">Logout</a>
        <router-link :to="{ name: 'dashboard'} ">Dashboard</router-link>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
    methods: {
        async logout() {
            try {
            await axios.post("/api/logout");
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
