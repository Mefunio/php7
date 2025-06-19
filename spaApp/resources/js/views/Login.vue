<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Logowanie</h2>

                        <form @submit.prevent="login">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    id="email"
                                    class="form-control"
                                    placeholder="Wprowadź email"
                                    required
                                >
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Hasło:</label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    id="password"
                                    class="form-control"
                                    placeholder="Wprowadź hasło"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Zaloguj się
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
                await this.auth.login(this.form);
            } catch (err) {
                console.log(err);
                this.form.email = "";
                this.form.password = "";
            }
        }
    },
}
</script>

<style scoped>
.card {
    border: none;
    border-radius: 12px;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 12px 15px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
}

.btn-primary {
    padding: 12px;
    font-weight: 500;
    border-radius: 8px;
}
</style>
