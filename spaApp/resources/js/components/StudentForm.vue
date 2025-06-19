<template>
    <div class="container">
        <h2>{{ isEdit ? 'Edytuj studenta' : 'Dodaj nowego studenta' }}</h2>

        <form @submit.prevent="submitForm">
            <div class="mb-3">
                <label for="first_name" class="form-label">Imię:</label>
                <input
                    v-model="form.first_name"
                    type="text"
                    id="first_name"
                    class="form-control"
                    placeholder="Wprowadź imię"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Nazwisko:</label>
                <input
                    v-model="form.last_name"
                    type="text"
                    id="last_name"
                    class="form-control"
                    placeholder="Wprowadź nazwisko"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="index" class="form-label">Numer indeksu:</label>
                <input
                    v-model="form.index"
                    type="text"
                    id="index"
                    class="form-control"
                    placeholder="Wprowadź numer indeksu"
                    required
                >
            </div>

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

            <div class="mb-3">
                <label for="age" class="form-label">Wiek:</label>
                <input
                    v-model.number="form.age"
                    type="number"
                    id="age"
                    class="form-control"
                    placeholder="Wprowadź wiek"
                    min="18"
                    max="100"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Opis:</label>
                <textarea
                    v-model="form.description"
                    id="description"
                    class="form-control"
                    rows="3"
                    placeholder="Wprowadź opis (opcjonalne)"
                ></textarea>
            </div>

            <div class="mb-3 form-check">
                <input
                    v-model="form.paid"
                    type="checkbox"
                    id="paid"
                    class="form-check-input"
                >
                <label class="form-check-label" for="paid">
                    Opłacone
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ isEdit ? 'Aktualizuj' : 'Dodaj' }}
                </button>
                <button @click="cancel" type="button" class="btn btn-secondary">
                    Anuluj
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
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
            }
        };
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
            try {
                if (this.isEdit) {
                    await this.studentsRepository.update(this.student.id, this.form);
                    this.$emit('updated');
                } else {
                    await this.studentsRepository.create(this.form);
                    this.$emit('created');
                }
                this.resetForm();
            } catch (error) {
                console.error('Błąd podczas zapisywania studenta:', error);
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
        }
    }
};
</script>
