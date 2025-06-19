import { HttpRequest } from './HttpRequest';

export class StudentsRepository {
    constructor(eventBus) {
        this.httpRequest = new HttpRequest(eventBus);
    }

    async getAll() {
        const response = await this.httpRequest.send("GET", "/api/students");
        return response.data;
    }

    async getById(id) {
        const response = await this.httpRequest.send("GET", `/api/students/${id}`);
        return response.data;
    }

    async create(studentData) {
        const response = await this.httpRequest.send("POST", "/api/students", studentData);
        return response.data;
    }

    async update(id, studentData) {
        const response = await this.httpRequest.send("PUT", `/api/students/${id}`, studentData);
        return response.data;
    }

    async delete(id) {
        const response = await this.httpRequest.send("DELETE", `/api/students/${id}`);
        return response.data;
    }
}
