// frontend/src/services/productService.js
import { api } from "./api";

export const productService = {
    getProducts: async (params = {}) => {
        const response = await api.get("/products", { params });
        return response.data;
    },

    getProduct: async (slug) => {
        const response = await api.get(`/products/${slug}`);
        return response.data;
    },

    searchProducts: async (query) => {
        const response = await api.get(`/products/search/${query}`);
        return response.data;
    },

    getCategories: async () => {
        const response = await api.get("/categories");
        return response.data;
    },

    getCategory: async (slug) => {
        const response = await api.get(`/categories/${slug}`);
        return response.data;
    },
};
