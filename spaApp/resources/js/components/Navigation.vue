<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <h4 class="navbar-brand mb-0">Student Manager</h4>

            <div class="navbar-nav ms-auto">
                <router-link
                    :to="{ name: 'home'}"
                    class="nav-link"
                    active-class="active"
                    exact
                >
                    Home
                </router-link>

                <router-link
                    v-if="!isLogged"
                    :to="{ name: 'login'}"
                    class="nav-link"
                    active-class="active"
                >
                    Login
                </router-link>

                <router-link
                    v-if="isLogged"
                    :to="{ name: 'dashboard'}"
                    class="nav-link"
                    active-class="active"
                >
                    Dashboard
                </router-link>

                <a
                    v-if="isLogged"
                    @click.prevent="logout"
                    href="#"
                    class="nav-link logout-link"
                >
                    Logout
                </a>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    inject: ["eventBus", "auth"],
    data() {
        return { isLogged: null };
    },
    created() {
        this.isLogged = localStorage.getItem("isLogged");
        this.eventBus.$on("isLogged", (status) => {
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

<style scoped>
.navbar-brand {
    font-weight: 600;
}

.nav-link {
    color: rgba(255, 255, 255, 0.8) !important;
    font-weight: 500;
    padding: 0.5rem 1rem !important;
    border-radius: 6px;
    margin: 0 2px;
    transition: all 0.3s ease;
}

.nav-link:hover,
.nav-link.active {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.1);
}

.logout-link:hover {
    color: white !important;
    background-color: #ff6b6b;
    border-color: #ff6b6b !important;
}
</style>
