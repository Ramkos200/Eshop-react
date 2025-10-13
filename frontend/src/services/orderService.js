// services/orderService.js
import { api } from "./api";

export const orderService = {
    createOrder: async (orderData) => {
        try {
            const cartToken = localStorage.getItem("cart_token");
            const authToken = localStorage.getItem("auth_token");

            const headers = {
                "Content-Type": "application/json",
            };
            if (cartToken) {
                headers["X-Cart-Token"] = cartToken;
            }

            if (authToken) {
                headers["Authorization"] = `Bearer ${authToken}`;
            }

            const response = await api.post("/orders", orderData, {
                headers,
            });

            if (!authToken && cartToken) {
                localStorage.removeItem("cart_token");
            }

            return response.data;
        } catch (error) {
            console.error("Order creation failed:", error.response?.data);

            // Remove or modify the 401 check that forces login
            // Guest users shouldn't get 401 errors if your backend supports guest checkout
            throw error;
        }
    },
};
