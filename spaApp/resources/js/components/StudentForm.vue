<template>
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="card-title mb-0">
                {{ isEdit ? 'Edytuj studenta' : 'Dodaj nowego studenta' }}
            </h4>
        </div>

        <div class="card-body">
            <form @submit.prevent="submitForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">Imię</label>
                        <input
                            v-model="form.first_name"
                            type="text"
                            id="first_name"
                            class="form-control"
                            :class="{ 'is-invalid': $v.form.first_name.$error }"
                            placeholder="Wprowadź imię"
                            @input="$v.form.first_name.$touch()"
                        >
                        <div class="invalid-feedback" v-if="$v.form.first_name.$error">
                            <span v-if="!$v.form.first_name.required">To pole jest wymagane</span>
                            <span v-else-if="!$v.form.first_name.minLength">Imię musi mieć co najmniej 2 znaki</span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Nazwisko</label>
                        <input
                            v-model="form.last_name"
                            type="text"
                            id="last_name"
                            class="form-control"
                            :class="{ 'is-invalid': $v.form.last_name.$error }"
                            placeholder="Wprowadź nazwisko"
                            @input="$v.form.last_name.$touch()"
                        >
                        <div class="invalid-feedback" v-if="$v.form.last_name.$error">
                            <span v-if="!$v.form.last_name.required">To pole jest wymagane</span>
                            <span v-else-if="!$v.form.last_name.minLength">Nazwisko musi mieć co najmniej 2 znaki</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="index" class="form-label">Numer indeksu</label>
                        <input
                            v-model="form.index"
                            type="text"
                            id="index"
                            class="form-control"
                            :class="{ 'is-invalid': $v.form.index.$error || serverErrors.index }"
                            placeholder="np. s123"
                            @input="$v.form.index.$touch(); serverErrors.index = null"
                        >
                        <div class="invalid-feedback" v-if="$v.form.index.$error || serverErrors.index">
                            <span v-if="!$v.form.index.required">To pole jest wymagane</span>
                            <span v-else-if="!$v.form.index.alphaNum">Numer indeksu może zawierać tylko litery i cyfry</span>
                            <span v-else-if="!$v.form.index.indexFormat">Format indeksu musi być sXXX (gdzie X to cyfra)</span>
                            <span v-else-if="serverErrors.index">{{ serverErrors.index }}</span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="age" class="form-label">Wiek</label>
                        <input
                            v-model.number="form.age"
                            type="number"
                            id="age"
                            class="form-control"
                            :class="{ 'is-invalid': $v.form.age.$error }"
                            placeholder="Wprowadź wiek"
                            min="18"
                            max="100"
                            @input="$v.form.age.$touch()"
                        >
                        <div class="invalid-feedback" v-if="$v.form.age.$error">
                            <span v-if="!$v.form.age.required">To pole jest wymagane</span>
                            <span v-else-if="!$v.form.age.minValue">Wiek nie może być niższy niż 18 lat</span>
                            <span v-else-if="!$v.form.age.maxValue">Wiek nie może być wyższy niż 100 lat</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        class="form-control"
                        :class="{ 'is-invalid': $v.form.email.$error || serverErrors.email }"
                        placeholder="student@example.com"
                        @input="$v.form.email.$touch(); serverErrors.email = null"
                    >
                    <div class="invalid-feedback" v-if="$v.form.email.$error || serverErrors.email">
                        <span v-if="!$v.form.email.required">To pole jest wymagane</span>
                        <span v-else-if="!$v.form.email.email">Podaj prawidłowy adres email</span>
                        <span v-else-if="serverErrors.email">{{ serverErrors.email }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Opis</label>
                    <textarea
                        v-model="form.description"
                        id="description"
                        class="form-control"
                        rows="3"
                        placeholder="Dodatkowe informacje o studencie (opcjonalne)"
                    ></textarea>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input
                            v-model="form.paid"
                            type="checkbox"
                            id="paid"
                            class="form-check-input"
                        >
                        <label class="form-check-label" for="paid">
                            <strong>Status opłaty</strong>
                            <span class="text-muted d-block small">
                                {{ form.paid ? 'Opłacone' : 'Oczekuje na płatność' }}
                            </span>
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ isEdit ? 'Aktualizuj studenta' : 'Dodaj studenta' }}
                    </button>
                    <button @click="cancel" type="button" class="btn btn-outline-secondary mx-2">
                        Anuluj
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, email, minLength, minValue, maxValue, alphaNum, helpers } from 'vuelidate/lib/validators'

const indexFormat = helpers.regex('indexFormat', /^s[0-9][0-9][0-9]$/);

export default {
    mixins: [validationMixin],
    inject: ["studentsRepository"],
    props: {
        student: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            form: {
                first_name: "",
                last_name: "",
                index: "",
                email: "",
                age: null,
                description: "",
                paid: false
            },
            submitted: false,
            serverErrors: {
                index: null,
                email: null
            }
        };
    },
    validations: {
        form: {
            first_name: { required, minLength: minLength(2) },
            last_name: { required, minLength: minLength(2) },
            index: {
                required,
                alphaNum,
                indexFormat
            },
            email: { required, email },
            age: {
                required,
                minValue: minValue(18),
                maxValue: maxValue(100)
            },
            description: {}
        }
    },
    computed: {
        isEdit() {
            return this.student !== null;
        }
    },
    created() {
        if (this.isEdit) {
            this.form = { ...this.student };
        }
    },
    watch: {
        student(newStudent) {
            if (newStudent) {
                this.form = { ...newStudent };
            } else {
                this.resetForm();
            }
        }
    },
    methods: {
        async submitForm() {
            this.submitted = true;
            this.$v.$touch();

            if (this.$v.$invalid) {
                return;
            }

            try {
                if (this.isEdit) {
                    await this.studentsRepository.update(this.student.id, this.form);
                    this.$emit('updated');
                } else {
                    await this.studentsRepository.create(this.form);
                    this.$emit('created');
                }
                this.resetForm();
                this.submitted = false;
            } catch (error) {
                console.error('Błąd podczas zapisywania studenta:', error);

                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;

                    if (errors.index) {
                        this.serverErrors.index = 'Ten numer indeksu jest już zajęty';
                    }

                    if (errors.email) {
                        this.serverErrors.email = 'Ten adres email jest już zajęty';
                    }
                }
            }
        },
        cancel() {
            this.resetForm();
            this.$emit('cancelled');
        },
        resetForm() {
            this.form = {
                first_name: "",
                last_name: "",
                index: "",
                email: "",
                age: null,
                description: "",
                paid: false
            };
            this.submitted = false;
            this.$v.$reset();
        }
    }
};
</script>

<style scoped>
.form-control,
.form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 12px 15px;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-position: right calc(0.375em + 0.5rem) center;
    background-repeat: no-repeat;
    background-size: calc(0.75em + 0.5rem) calc(0.75em + 0.5rem);
}

.form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.card {
    border: none;
    border-radius: 12px;
}

.card-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem;
}

.btn {
    padding: 10px 20px;
    font-weight: 500;
    border-radius: 8px;
}
</style>
