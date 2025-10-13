// frontend/src/services/categoryService.js
import { api } from "./api";
const API_BASE_URL =
    import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";
export const categoryService = {
    getCategories: async () => {
        const response = await api.get("/categories");
        return response.data;
    },

    getCategory: async (slug) => {
        const response = await api.get(`/categories/${slug}`);
        return response.data;
    },

    getCategoryProducts: async (slug, params = {}) => {
        const response = await api.get(`/categories/${slug}/products`, {
            params,
        });
        return response.data;
    },
};
