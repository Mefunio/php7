<template>
    <div class="container">
        <h1>Dashboard</h1>
        <div v-if="user">Zalogowany użytkownik: {{ user }}</div>

        <hr>

        <!-- Przyciski nawigacji -->
        <div class="mb-4">
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

        <!-- Dynamiczne ładowanie komponentów -->
        <div class="mt-4">
            <!-- Lista studentów -->
            <student-list
                v-if="selectedComponent === 'list'"
                :students="students"
                @add-student="showAddForm"
                @edit-student="showEditForm"
                @student-deleted="handleStudentDeleted"
            />

            <!-- Formularz studenta -->
            <student-form
                v-if="selectedComponent === 'form'"
                :student="selectedStudent"
                @created="handleStudentCreated"
                @updated="handleStudentUpdated"
                @cancelled="showList"
            />
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
    margin-right: 1px;
}

hr {
    margin: 1.5rem 0;
}
</style>
