// frontend/src/services/categoryService.js
import { api } from "./api.js";

export const categoryService = {
    getCategories: async () => {
        try {
            const response = await api.get("/categories");
            return response.data;
        } catch (error) {
            console.error("Error fetching categories:", error);
            throw error;
        }
    },

    getCategory: async (slug) => {
        try {
            const response = await api.get(`/categories/${slug}`);
            return response.data;
        } catch (error) {
            console.error(`Error fetching category ${slug}:`, error);
            throw error;
        }
    },

    getCategoryProducts: async (slug, params = {}) => {
        try {
            const response = await api.get(`/categories/${slug}/products`, {
                params,
            });
            return response.data;
        } catch (error) {
            console.error(`Error fetching products for ${slug}:`, error);
            throw error;
        }
    },
};


