// services/cartService.js
import { api } from "./api.js";

class CartService {
    constructor() {
        this.cartToken = null;
        this.loadCartToken();
    }
    loadCartToken() {
        this.cartToken = localStorage.getItem("cart_token");
    }
    saveCartToken(token) {
        if (token) {
            this.cartToken = token;
            localStorage.setItem("cart_token", token);
            const verified = localStorage.getItem("cart_token");
        }
    }

    getHeaders() {
        const headers = {
            Accept: "application/json",
            "Content-Type": "application/json",
        };

        if (this.cartToken) {
            headers["X-Cart-Token"] = this.cartToken;
        }
        return headers;
    }

    extractCartToken(response) {
        let token = null;

        // Get token from response data
        if (
            response.data &&
            response.data.data &&
            response.data.data.cart_token
        ) {
            token = response.data.data.cart_token;
        } else {
            return null;
        }

        if (token) {
            this.saveCartToken(token);
            return token;
        } else {
            return null;
        }
    }

    async getCart() {
        try {
            const response = await api.get("/cart", {
                headers: this.getHeaders(),
            });
            this.extractCartToken(response);
            return response.data;
        } catch (error) {
            throw error;
        }
    }

    async addToCart(skuId, quantity = 1) {
        try {
            const response = await api.post(
                "/cart/add",
                { sku_id: skuId, quantity },
                { headers: this.getHeaders() }
            );

            this.extractCartToken(response);

            return response.data;
        } catch (error) {
            throw error;
        }
    }

    async updateQuantity(skuId, quantity) {
        try {
            const response = await api.put(
                `/cart/update/${skuId}`,
                { quantity },
                { headers: this.getHeaders() }
            );

            this.extractCartToken(response);

            return response.data;
        } catch (error) {
            throw error;
        }
    }

    async removeFromCart(skuId) {
        try {
            const response = await api.delete(`/cart/remove/${skuId}`, {
                headers: this.getHeaders(),
            });

            this.extractCartToken(response);

            return response.data;
        } catch (error) {
            throw error;
        }
    }

    async clearCart() {
        try {
            const response = await api.post(
                "/cart/clear",
                {},
                {
                    headers: this.getHeaders(),
                }
            );

            this.extractCartToken(response);

            return response.data;
        } catch (error) {
            throw error;
        }
    }
}

export const cartService = new CartService();
