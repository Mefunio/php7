<template>
    <div class="container">
        <h1>Navigation</h1><br>

        <router-link :to="{ name: 'home'} ">Home</router-link>
        <router-link :to="{ name: 'login'} ">Login</router-link>
        <router-link :to="{ name: 'dashboard'} ">Dashboard</router-link>
        <router-view></router-view>
    </div>
</template>

<script>
    export default { }
</script>
