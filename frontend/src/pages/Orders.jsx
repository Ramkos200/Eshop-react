// frontend/src/pages/Orders.jsx
import React, { useState, useEffect } from "react";
import { useAuth } from "../context/AuthContext";
import { useNavigate, Link } from "react-router-dom";
import { api } from "../services/api";

const Orders = () => {
    const { user } = useAuth();
    const navigate = useNavigate();
    const [orders, setOrders] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (!user) {
            return;
        }

        fetchUserOrders();
    }, [user, navigate]);

    const fetchUserOrders = async () => {
        try {
            setError(null);
            const response = await api.get("/orders/user-orders");

            //  API response
            if (response.data.success) {
                setOrders(response.data.data || []); // Use data array or empty array
            } else {
                setError(response.data.message || "Failed to fetch orders");
            }
        } catch (error) {
            console.error("Error fetching orders:", error);
            if (error.response?.status === 404) {
                setError(
                    "Orders feature is not available yet. Please check back later."
                );
            } else {
                setError("Unable to load your orders. Please try again.");
            }
        } finally {
            setLoading(false);
        }
    };

    const getStatusColor = (status) => {
        switch (status) {
            case "delivered":
                return "bg-green-100 text-green-800";
            case "processing":
                return "bg-blue-100 text-blue-800";
            case "pending":
                return "bg-yellow-100 text-yellow-800";
            case "cancelled":
                return "bg-red-100 text-red-800";
            case "shipped":
                return "bg-purple-100 text-purple-800";
            default:
                return "bg-gray-100 text-gray-800";
        }
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    };

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
        }).format(amount);
    };

    if (!user) {
        return null;
    }

    if (loading) {
        return (
            <div className="max-w-4xl mx-auto px-4 py-8">
                <div className="flex justify-center items-center h-64">
                    <div className="text-lg">Loading your orders...</div>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="max-w-4xl mx-auto px-4 py-8">
                <div className="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <h3 className="text-lg font-semibold text-red-800 mb-2">
                        {error.includes("not available")
                            ? "Feature Coming Soon"
                            : "Error Loading Orders"}
                    </h3>
                    <p className="text-red-600 mb-4">{error}</p>
                    <div className="flex gap-4 justify-center">
                        <button
                            onClick={fetchUserOrders}
                            className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        >
                            Try Again
                        </button>
                        <Link
                            to="/products"
                            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                        >
                            Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="max-w-4xl mx-auto px-4 py-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

            {orders.length === 0 ? (
                <div className="bg-white shadow-md rounded-lg p-8 text-center">
                    <div className="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg
                            className="w-12 h-12 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                            />
                        </svg>
                    </div>
                    <h2 className="text-2xl font-bold text-gray-900 mb-4">
                        No orders yet
                    </h2>
                    <p className="text-gray-600 mb-8">
                        Start shopping to see your orders here
                    </p>
                    <Link
                        to="/products"
                        className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition"
                    >
                        Start Shopping
                    </Link>
                </div>
            ) : (
                <div className="space-y-6">
                    {orders.map((order) => (
                        <div
                            key={order.id}
                            className="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow"
                        >
                            <div className="flex justify-between items-start mb-4">
                                <div>
                                    <h3 className="text-lg font-semibold text-gray-900">
                                        Order #{order.order_code}
                                    </h3>
                                    <p className="text-gray-600 text-sm">
                                        Placed on {formatDate(order.created_at)}
                                    </p>
                                    {order.Customer && (
                                        <p className="text-gray-500 text-sm mt-1">
                                            Ship to: {order.Customer.name}
                                        </p>
                                    )}
                                </div>
                                <div className="flex flex-col items-end space-y-2">
                                    <span
                                        className={`px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(
                                            order.status
                                        )}`}
                                    >
                                        {order.status.charAt(0).toUpperCase() +
                                            order.status.slice(1)}
                                    </span>
                                    <span className="text-lg font-semibold">
                                        {formatCurrency(order.total_amount)}
                                    </span>
                                </div>
                            </div>

                            <div className="border-t pt-4">
                                <div className="flex justify-between items-center">
                                    <span className="text-gray-600">
                                        {order.items?.length || 0} items
                                    </span>
                                    <Link
                                        to={`/orders/${order.order_code}`}
                                        className="text-blue-600 hover:text-blue-700 font-medium"
                                    >
                                        View Details
                                    </Link>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default Orders;
