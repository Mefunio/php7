<template>
    <div class="container">
        <h2>Lista studentów</h2>

        <div v-if="students.length > 0" class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Indeks</th>
                    <th>Email</th>
                    <th>Wiek</th>
                    <th>Opis</th>
                    <th>Opłacone</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="student in students" :key="student.id">
                    <td>{{ student.id }}</td>
                    <td>{{ student.first_name }}</td>
                    <td>{{ student.last_name }}</td>
                    <td>{{ student.index }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.age }}</td>
                    <td>{{ student.description || '' }}</td>
                    <td>
                            <span :class="student.paid ? 'badge bg-success' : 'badge bg-danger'">
                                {{ student.paid ? 'Tak' : 'Nie' }}
                            </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button
                                @click="$emit('edit-student', student)"
                                class="btn btn-sm btn-warning"
                                title="Edytuj"
                            >
                                Edytuj
                            </button>
                            <button
                                @click="deleteStudent(student.id)"
                                class="btn btn-sm btn-danger"
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
.table th {
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 2px;
}

.badge {
    font-size: 0.8em;
}
</style>
