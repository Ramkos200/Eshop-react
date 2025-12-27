import React, { createContext, useState, useContext, useEffect } from "react";
import { authService } from "../services/authService";

const AuthContext = createContext();

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error("useAuth must be used within an AuthProvider");
    }
    return context;
};
const updateUser = (userData) => {
    setUser((prev) => ({ ...prev, ...userData }));
};
export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [authError, setAuthError] = useState(null);

    useEffect(() => {
        checkAuth();
    }, []);

    const checkAuth = async () => {
        try {
            const token = localStorage.getItem("auth_token");
            if (token) {
                const userData = await authService.getProfile();
                setUser(userData);
                setAuthError(null);
            }
        } catch (error) {
            console.error("Auth check failed:", error);
            localStorage.removeItem("auth_token");
            setAuthError("Session expired. Please login again.");
        } finally {
            setLoading(false);
        }
    };

    const login = async (email, password) => {
        try {
            setAuthError(null);
            const data = await authService.login(email, password);
            setUser(data.user);
            return data;
        } catch (error) {
            setAuthError(error.response?.data?.message || "Login failed");
            throw error;
        }
    };

    const register = async (userData) => {
        try {
            setAuthError(null);
            const data = await authService.register(userData);
            setUser(data.user);
            return data;
        } catch (error) {
            setAuthError(
                error.response?.data?.message || "Registration failed"
            );
            throw error;
        }
    };

    const logout = async () => {
        try {
            await authService.logout();
            setUser(null);
            setAuthError(null);
        } catch (error) {
            console.error("Logout error:", error);
        }
    };

    const updateUser = (userData) => {
        setUser((prev) => ({ ...prev, ...userData }));
    };

    const clearError = () => {
        setAuthError(null);
    };

    const value = {
        user,
        login,
        register,
        logout,
        loading,
        authError,
        updateUser,
        clearError,
    };

    return (
        <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
    );
};
