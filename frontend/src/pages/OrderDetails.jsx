import React, { useState, useEffect } from "react";
import { useParams, Link, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { api } from "../services/api";

const OrderDetails = () => {
    const { orderCode } = useParams();
    const { user } = useAuth();
    const navigate = useNavigate();
    const [order, setOrder] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (!user) {
            navigate("/login");
            return;
        }
        fetchOrderDetails();
    }, [orderCode, user, navigate]);

    const fetchOrderDetails = async () => {
        try {
            setLoading(true);
            setError(null);

            const response = await api.get(`/orders/${orderCode}`);

            if (response.data && response.data.order_code) {
                setOrder(response.data);
            } else if (response.data.success && response.data.data) {
                setOrder(response.data.data);
            } else {
                setError("Order data not found in response");
            }
        } catch (error) {
            console.error("Error fetching order details:", error);

            if (error.response?.status === 404) {
                setError("Order not found - 404 Error");
            } else if (error.response?.status === 401) {
                setError("Authentication required");
            } else {
                setError(`Failed to load order details: ${error.message}`);
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

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
        }).format(amount);
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    if (loading) {
        return (
            <div className="max-w-4xl mx-auto px-4 py-8">
                <div className="flex justify-center items-center h-64">
                    <div className="text-lg">Loading order details...</div>
                </div>
            </div>
        );
    }

    if (error || !order) {
        return (
            <div className="max-w-4xl mx-auto px-4 py-8">
                <div className="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <h3 className="text-lg font-semibold text-red-800 mb-2">
                        {error || "Order not found"}
                    </h3>
                    <p className="text-red-600 mb-4">
                        We couldn't find the order you're looking for.
                    </p>
                    <div className="space-x-4">
                        <button
                            onClick={fetchOrderDetails}
                            className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        >
                            Try Again
                        </button>
                        <Link
                            to="/orders"
                            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                        >
                            Back to Orders
                        </Link>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="max-w-4xl mx-auto px-4 py-8">
            {/* Header */}
            <div className="flex justify-between items-start mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-gray-900">
                        Order #{order.order_code}
                    </h1>
                    <p className="text-gray-600">
                        Placed on {formatDate(order.created_at)}
                    </p>
                </div>
                <span
                    className={`px-4 py-2 rounded-full text-sm font-medium ${getStatusColor(
                        order.status
                    )}`}
                >
                    {order.status.charAt(0).toUpperCase() +
                        order.status.slice(1)}
                </span>
            </div>

            {/* Order Summary */}
            <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 className="text-xl font-semibold mb-4">Order Summary</h2>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 className="font-medium text-gray-900 mb-2">
                            Customer Information
                        </h3>
                        {order.Customer && (
                            <div className="space-y-1 text-sm">
                                <p>
                                    <strong>Name:</strong> {order.Customer.name}
                                </p>
                                <p>
                                    <strong>Email:</strong>{" "}
                                    {order.Customer.email}
                                </p>
                                {order.Customer.phone && (
                                    <p>
                                        <strong>Phone:</strong>{" "}
                                        {order.Customer.phone}
                                    </p>
                                )}
                            </div>
                        )}
                    </div>
                    <div>
                        <h3 className="font-medium text-gray-900 mb-2">
                            Shipping Address
                        </h3>
                        {order.shipping_address && (
                            <div className="space-y-1 text-sm">
                                <p>{order.shipping_address.street_address}</p>
                                <p>
                                    {order.shipping_address.city},{" "}
                                    {order.shipping_address.state}{" "}
                                    {order.shipping_address.zip_code}
                                </p>
                                <p>{order.shipping_address.country}</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Order Items */}
            <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 className="text-xl font-semibold mb-4">Order Items</h2>
                {order.items && order.items.length > 0 ? (
                    <div className="space-y-4">
                        {order.items.map((item, index) => (
                            <div
                                key={index}
                                className="flex justify-between items-center border-b pb-4"
                            >
                                <div className="flex-1">
                                    <h4 className="font-medium text-gray-900">
                                        {item.product_name ||
                                            `SKU: ${item.sku_code}`}
                                    </h4>
                                    <p className="text-sm text-gray-600">
                                        Quantity: {item.quantity} ×{" "}
                                        {formatCurrency(item.price)}
                                    </p>
                                    {item.attributes && (
                                        <p className="text-xs text-gray-500">
                                            {Object.entries(
                                                item.attributes
                                            ).map(([key, value]) => (
                                                <span
                                                    key={key}
                                                    className="mr-2"
                                                >
                                                    {key}: {value}
                                                </span>
                                            ))}
                                        </p>
                                    )}
                                </div>
                                <div className="text-right">
                                    <p className="font-semibold">
                                        {formatCurrency(
                                            item.price * item.quantity
                                        )}
                                    </p>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <p className="text-gray-600">
                        No items found in this order.
                    </p>
                )}
            </div>

            {/* Order Total */}
            <div className="bg-white rounded-lg shadow-md p-6">
                <div className="flex justify-between items-center text-lg font-semibold">
                    <span>Total Amount:</span>
                    <span>{formatCurrency(order.total_amount)}</span>
                </div>
                {order.notes && (
                    <div className="mt-4 pt-4 border-t">
                        <h3 className="font-medium text-gray-900 mb-2">
                            Order Notes
                        </h3>
                        <p className="text-gray-600 text-sm">{order.notes}</p>
                    </div>
                )}
            </div>

            {/* Back Button */}
            <div className="mt-6">
                <Link
                    to="/orders"
                    className="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
                >
                    Back to Orders
                </Link>
            </div>
        </div>
    );
};

export default OrderDetails;
