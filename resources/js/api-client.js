class ApiClient {
    constructor(baseUrl = "/reports") {
        this.baseUrl = baseUrl;
        this.token = null;
    }

    setToken(token) {
        this.token = token;
    }

    getHeaders() {
        const headers = {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        };

        if (this.token) {
            headers["Authorization"] = `Bearer ${this.token}`;
        }

        return headers;
    }

    async request(method, endpoint, data = null) {
        const url = `${this.baseUrl}${endpoint}`;
        const options = {
            method,
            headers: this.getHeaders(),
        };

        if (data) {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, options);
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || `HTTP ${response.status}`);
            }

            return result;
        } catch (error) {
            console.error("API Error:", error);
            throw error;
        }
    }

    async get(endpoint) {
        return this.request("GET", endpoint);
    }

    async post(endpoint, data) {
        return this.request("POST", endpoint, data);
    }

    async put(endpoint, data) {
        return this.request("PUT", endpoint, data);
    }

    async delete(endpoint) {
        return this.request("DELETE", endpoint);
    }

    async login(fullName, nim) {
        return this.post("/auth/login", {
            full_name: fullName,
            nim: nim,
        });
    }

    async logout() {
        return this.post("/auth/logout", {});
    }

    async getCurrentUser() {
        return this.get("/auth/user");
    }

    async getReports(status = null) {
        let endpoint = "";
        if (status) {
            endpoint += `?status=${status}`;
        }
        return this.get(endpoint);
    }

    async getReport(id) {
        return this.get(`/reports/${id}`);
    }

    async createReport(data) {
        return this.post("/reports", data);
    }

    async updateReport(id, data) {
        return this.put(`/reports/${id}`, data);
    }

    async deleteReport(id) {
        return this.delete(`/reports/${id}`);
    }

    async getReportEditData(id) {
        return this.get(`/reports/${id}/edit`);
    }

    async getBuildings() {
        return this.get("/buildings");
    }

    async getRooms(buildingId) {
        return this.get(`/buildings/${buildingId}/rooms`);
    }

    async getFacilities() {
        return this.get("/facilities");
    }
}

const api = new ApiClient();

if (typeof module !== "undefined" && module.exports) {
    module.exports = ApiClient;
}
