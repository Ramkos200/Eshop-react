import axios from "axios";

const API_BASE_URL = "http://localhost:8000/api";

// Create axios instance
export const api = axios.create({
    baseURL: API_BASE_URL,
    timeout: 1000,
    headers: {
        "Content-Type": "application/json",
    },
});

// Add cart token interceptor
api.interceptors.request.use((config) => {
    const cartToken = localStorage.getItem("cart_token");
    if (cartToken) {
        config.headers["X-Cart-Token"] = cartToken;
    }

    const token = localStorage.getItem("auth_token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

// Response interceptor for error handling
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem("auth_token");
            window.location.href = "/login";
        }
        return Promise.reject(error);
    }
);

// Cart token utility
export const getCartToken = () => {
    let cartToken = localStorage.getItem("cart_token");
    if (!cartToken) {
        cartToken = generateUUID();
        localStorage.setItem("cart_token", cartToken);
    }
    return cartToken;
};

const generateUUID = () => {
    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
        /[xy]/g,
        function (c) {
            const r = (Math.random() * 16) | 0;
            const v = c == "x" ? r : (r & 0x3) | 0x8;
            return v.toString(16);
        }
    );
};
