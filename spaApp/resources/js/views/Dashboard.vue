<template>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1">Dashboard</h1>
                        <p class="text-muted mb-0" v-if="user">
                            Witaj, <strong>{{ user }}</strong>
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="btn-toolbar justify-content-between" role="toolbar">
                            <div class="btn-group" role="group">
                                <button
                                    @click="selectedComponent = 'list'"
                                    :class="['btn', selectedComponent === 'list' ? 'btn-primary' : 'btn-outline-primary']"
                                >
                                    Lista studentów
                                </button>
                                <button
                                    @click="showAddForm"
                                    :class="['btn', selectedComponent === 'form' ? 'btn-success' : 'btn-outline-success']"
                                >
                                    Dodaj studenta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <student-list
                    v-if="selectedComponent === 'list'"
                    :students="students"
                    @add-student="showAddForm"
                    @edit-student="showEditForm"
                    @student-deleted="handleStudentDeleted"
                />

                <student-form
                    v-if="selectedComponent === 'form'"
                    :student="selectedStudent"
                    @created="handleStudentCreated"
                    @updated="handleStudentUpdated"
                    @cancelled="showList"
                />
            </div>
        </div>
    </div>
</template>

<script>
import StudentList from '../components/StudentList.vue';
import StudentForm from '../components/StudentForm.vue';

export default {
    inject: ["auth", "studentsRepository"],
    components: {
        StudentList,
        StudentForm
    },
    data() {
        return {
            user: null,
            students: [],
            selectedComponent: 'list',
            selectedStudent: null
        };
    },
    async created() {
        await this.getAuthUser();
        await this.loadStudents();
    },
    methods: {
        async getAuthUser() {
            try {
                const user = await this.auth.getAuthUser();
                this.user = user.data.name;
            } catch (error) {
                console.error('Błąd podczas pobierania danych użytkownika:', error);
            }
        },

        async loadStudents() {
            try {
                const response = await this.studentsRepository.getAll();
                this.students = Array.isArray(response) ? response : response.data;
            } catch (error) {
                console.error('Błąd podczas ładowania studentów:', error);
            }
        },

        showList() {
            this.selectedComponent = 'list';
            this.selectedStudent = null;
        },

        showAddForm() {
            this.selectedComponent = 'form';
            this.selectedStudent = null;
        },

        showEditForm(student) {
            this.selectedComponent = 'form';
            this.selectedStudent = student;
        },

        async handleStudentCreated() {
            await this.loadStudents();
            this.showList();
        },

        async handleStudentUpdated() {
            await this.loadStudents();
            this.showList();
        },

        handleStudentDeleted(studentId) {
            this.students = this.students.filter(student => student.id !== studentId);
        }
    }
};
</script>

<style scoped>
.btn-group .btn {
    font-weight: 500;
    padding: 10px 16px;
}

.card {
    border: none;
    border-radius: 12px;
}
</style>
