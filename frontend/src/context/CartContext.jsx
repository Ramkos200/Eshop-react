import React, { createContext, useState, useContext, useEffect } from "react";
import { cartService } from "../services/cartService";

const CartContext = createContext();

export const useCart = () => {
    const context = useContext(CartContext);
    if (!context) {
        throw new Error("useCart must be used within a CartProvider");
    }
    return context;
};

export const CartProvider = ({ children }) => {
    const [cart, setCart] = useState({ items: [], total: 0, count: 0 });
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const fetchCart = async () => {
        try {
            setLoading(true);
            setError(null);
            const response = await cartService.getCart();
            if (response.success) {
                setCart(response.data);
            } else {
                setError(response.message || "Failed to load cart");
            }
        } catch (error) {
            console.error("Failed to fetch cart:", error);
            setError("Unable to load cart. Please try again.");
            setCart({ items: [], total: 0, count: 0 });
        } finally {
            setLoading(false);
        }
    };

    const addToCart = async (skuId, quantity = 1) => {
        try {
            setError(null);
            const response = await cartService.addToCart(skuId, quantity);
            if (response.success) {
                setCart(response.data);
            }
            return response;
        } catch (error) {
            console.error("Failed to add to cart:", error);
            setError(
                error.response?.data?.message || "Failed to add item to cart"
            );
            throw error;
        }
    };

    const updateQuantity = async (skuId, quantity) => {
        try {
            setError(null);
            const response = await cartService.updateQuantity(skuId, quantity);
            if (response.success) {
                setCart(response.data);
            }
            return response;
        } catch (error) {
            console.error("Failed to update quantity:", error);
            setError(error.response?.data?.message || "Failed to update cart");
            throw error;
        }
    };

    const removeFromCart = async (skuId) => {
        try {
            setError(null);
            const response = await cartService.removeFromCart(skuId);
            if (response.success) {
                setCart(response.data);
            }
            return response;
        } catch (error) {
            console.error("Failed to remove from cart:", error);
            setError(error.response?.data?.message || "Failed to remove item");
            throw error;
        }
    };

    const clearCart = async () => {
        try {
            setError(null);
            const response = await cartService.clearCart();
            if (response.success) {
                setCart(response.data);
            }
            return response;
        } catch (error) {
            console.error("Failed to clear cart:", error);
            setError(error.response?.data?.message || "Failed to clear cart");
            throw error;
        }
    };

    const clearError = () => {
        setError(null);
    };

    const getCartItemCount = () => {
        return (
            cart.items?.reduce((total, item) => total + item.quantity, 0) || 0
        );
    };

    useEffect(() => {
        fetchCart();
    }, []);

    const value = {
        cart,
        loading,
        error,
        addToCart,
        updateQuantity,
        removeFromCart,
        clearCart,
        refreshCart: fetchCart,
        clearError,
        getCartItemCount,
    };

    return (
        <CartContext.Provider value={value}>{children}</CartContext.Provider>
    );
};
