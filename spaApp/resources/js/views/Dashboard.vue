<template>
    <div class="container">
        Dashboard
        <div v-if="user">Logged user: {{user}}</div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: null,
        };
    },
    created() {
        this.fetchUser();
    },
    methods: {
        async fetchUser() {
            try {
                const response = await axios.get("/api/user");
                this.user = response.data.name;
            } catch (error) {
                console.error("Failed to fetch user:", error);
            }
        },
    },
}
</script>
