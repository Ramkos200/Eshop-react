import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    withCredentials: true,
});

// Simple API functions
export const apiCalls = {
    // Products
    getProducts: (params) => api.get('/products', { params }),
    getProduct: (slug) => api.get(`/products/${slug}`),
    
    // Categories
    getCategories: () => api.get('/categories'),
    
    // Cart
    getCart: () => api.get('/cart'),
    addToCart: (skuId, quantity) => api.post('/cart/add', { sku_id: skuId, quantity }),
    updateCart: (skuId, quantity) => api.put(`/cart/update/${skuId}`, { quantity }),
    removeFromCart: (skuId) => api.delete(`/cart/remove/${skuId}`),
    clearCart: () => api.post('/cart/clear'),
    
    // Orders
    createOrder: (orderData) => api.post('/orders', orderData),
    getOrder: (orderCode) => api.get(`/orders/${orderCode}`),
};

export default api;