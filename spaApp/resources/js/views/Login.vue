<template>
    <div class="container">
        <h1>Login Component</h1>
        <form>
            <div class="mb-3">
                <label for="email">Email:</label>
                <input v-model="form.email" type="email" id="email" placeholder="Enter email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Hasło:</label>
                <input v-model="form.password" type="password" id="password" placeholder="Enter password">
            </div>
            <button type="submit" @click.prevent="login">Login</button>
        </form>
    </div>
</template>

<script>
export default {
    inject: ["auth"],
    data() {
        return {
            form: {
                email: "",
                password: "",
            }
        };
    },
    methods: {
        async login() {
            try {
                await this.auth.login(this.form)

            } catch (err) {
                console.log(err);
                this.form.email = "";
                this.form.password = "";
            }
        }
    },
}
</script>
