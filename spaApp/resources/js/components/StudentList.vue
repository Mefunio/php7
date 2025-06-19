<template>
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="card-title mb-0">Lista studentów</h4>

            <div class="mt-3">
                <div class="input-group w-50">
                    <input
                        type="text"
                        v-model="searchFilter"
                        class="form-control"
                        placeholder="Wyszukaj po imieniu, nazwisku lub numerze indeksu..."
                    >
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div v-if="filteredStudents.length > 0" class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="border-0 fw-semibold">ID</th>
                        <th class="border-0 fw-semibold">Imię</th>
                        <th class="border-0 fw-semibold">Nazwisko</th>
                        <th class="border-0 fw-semibold">Indeks</th>
                        <th class="border-0 fw-semibold">Email</th>
                        <th class="border-0 fw-semibold">Wiek</th>
                        <th class="border-0 fw-semibold">Opis</th>
                        <th class="border-0 fw-semibold">Status</th>
                        <th class="border-0 fw-semibold text-center">Akcje</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="student in filteredStudents" :key="student.id" class="align-middle">
                        <td class="text-muted">{{ student.id }}</td>
                        <td class="fw-medium">{{ student.first_name }}</td>
                        <td class="fw-medium">{{ student.last_name }}</td>
                        <td>
                            <code class="text-primary">{{ student.index }}</code>
                        </td>
                        <td>
                            <a :href="'mailto:' + student.email" class="text-decoration-none">
                                {{ student.email }}
                            </a>
                        </td>
                        <td>{{ student.age }} lat</td>
                        <td>
                            <span v-if="student.description" class="text-muted">
                                {{ student.description.substring(0, 10) }}
                                <span v-if="student.description.length > 10">...</span>
                            </span>
                            <span v-else class="text-muted fst-italic">Brak opisu</span>
                        </td>
                        <td>
                            <span :class="student.paid ? 'badge bg-success' : 'badge bg-warning text-dark'">
                                {{ student.paid ? '✓ Opłacono' : '⏳ Oczekuje na płatność' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button
                                    @click="$emit('edit-student', student)"
                                    class="btn btn-outline-primary"
                                    title="Edytuj"
                                >
                                    Edytuj
                                </button>
                                <button
                                    @click="deleteStudent(student.id)"
                                    class="btn btn-outline-danger"
                                    title="Usuń"
                                >
                                    Usuń
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-else class="text-center py-5">
                <div class="text-muted">
                    <h5>Brak studentów</h5>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    inject: ["studentsRepository"],
    props: {
        students: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            searchFilter: ''
        }
    },
    computed: {
        filteredStudents() {
            if (!this.searchFilter) {
                return this.students;
            }

            const searchTerm = this.searchFilter.toLowerCase().trim();

            return this.students.filter(student => {
                return student.first_name.toLowerCase().includes(searchTerm) ||
                       student.last_name.toLowerCase().includes(searchTerm) ||
                       student.index.toString().includes(searchTerm);
            });
        },
        isFiltering() {
            return this.searchFilter.length > 0;
        }
    },
    methods: {
        async deleteStudent(studentId) {
            if (confirm('Czy na pewno chcesz usunąć tego studenta?')) {
                try {
                    await this.studentsRepository.delete(studentId);
                    this.$emit('student-deleted', studentId);
                } catch (error) {
                    console.error('Błąd podczas usuwania studenta:', error);
                }
            }
        }
    }
};
</script>

<style scoped>
.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table th {
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-group-sm .btn {
    padding: 0.375rem 0.75rem;
}

.card {
    border: none;
    border-radius: 12px;
}

.card-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

code {
    padding: 0.25rem 0.5rem;
    background-color: rgba(0, 123, 255, 0.1);
    border-radius: 4px;
}
</style>
