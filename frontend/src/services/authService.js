// frontend/src/services/authService.js
import { api } from "./api";

export const authService = {
    login: async (email, password) => {
        const response = await api.post("/login", { email, password });
        if (response.data.token) {
            localStorage.setItem("auth_token", response.data.token);
        }
        return response.data;
    },

    register: async (userData) => {
        const response = await api.post("/register", userData);
        if (response.data.token) {
            localStorage.setItem("auth_token", response.data.token);
        }
        return response.data;
    },

    logout: async () => {
        await api.post("/logout");
        localStorage.removeItem("auth_token");
    },

    getProfile: async () => {
        const response = await api.get("/user");
        return response.data;
    },
    updateProfile: async (profileData) => {
        const response = await api.put("/profile", profileData);
        return response.data;
    },

    changePassword: async (passwordData) => {
        const response = await api.put("/profile/password", passwordData);
        return response.data;
    },
};
