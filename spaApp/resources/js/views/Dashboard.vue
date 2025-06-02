<template>
    <div class="container">
        Dashboard
        <div v-if="user">Logged user: {{user}}</div>
    </div>
</template>

<script>
export default {
    inject: ["auth"],
    data() {
        return {
            user: null,
        };
    },
    created() {
        this.getAuthUser();
    },
    methods: {
        async getAuthUser() {
            let user = await this.auth.getAuthUser();
            this.user = user.data.name;
        },
    },
}
</script>
