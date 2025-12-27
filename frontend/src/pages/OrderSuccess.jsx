import React from "react";
import { Link, useLocation } from "react-router-dom";

const OrderSuccess = () => {
    const location = useLocation();
    const { order, orderCode, isGuest } = location.state || {};

    if (!order) {
        return (
            <div className="max-w-4xl mx-auto px-4 py-16 text-center">
                <h2 className="text-2xl font-bold text-gray-900 mb-4">
                    Order Not Found
                </h2>
                <Link
                    to="/"
                    className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition"
                >
                    Back to Home
                </Link>
            </div>
        );
    }

    return (
        <div className="max-w-2xl mx-auto px-4 py-16 text-center">
            {/* Success Icon */}
            <div className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg
                    className="w-10 h-10 text-green-600"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fillRule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clipRule="evenodd"
                    />
                </svg>
            </div>

            {/* Success Message */}
            <h1 className="text-3xl font-bold text-gray-900 mb-4">
                Order Confirmed!
            </h1>
            <p className="text-lg text-gray-600 mb-2">
                Thank you for your purchase. Your order has been received.
            </p>
            <p className="text-gray-500 mb-8">
                Order #:{" "}
                <span className="font-mono font-semibold">{orderCode}</span>
            </p>

            {/* Registration Suggestion for Guests */}
            {isGuest && (
                <div className="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left">
                    <div className="flex items-start">
                        <div className="flex-shrink-0">
                            <svg
                                className="w-6 h-6 text-blue-600 mt-0.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div className="ml-3">
                            <h3 className="text-lg font-semibold text-blue-800 mb-2">
                                Create an Account for Better Experience
                            </h3>
                            <p className="text-blue-700 mb-4">
                                Track your orders, save your shipping
                                preferences, and get exclusive offers by
                                creating an account.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-3">
                                <Link
                                    to="/register"
                                    state={{
                                        email: order.Customer?.email,
                                        name: order.Customer?.name,
                                        returnTo: "/orders",
                                    }}
                                    className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold text-center"
                                >
                                    Create Account
                                </Link>
                                <Link
                                    to="/products"
                                    className="border border-blue-600 text-blue-600 px-6 py-2 rounded-lg hover:bg-blue-50 transition font-semibold"
                                >
                                    Maybe Later
                                </Link>
                                
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Order Details */}
            <div className="bg-white rounded-lg shadow-md p-6 mb-8 text-left">
                <h2 className="text-xl font-semibold mb-4">Order Details</h2>
                <div className="space-y-2">
                    <div className="flex justify-between">
                        <span>Order Total:</span>
                        <span className="font-semibold">
                            ${order.total_amount}
                        </span>
                    </div>
                    <div className="flex justify-between">
                        <span>Status:</span>
                        <span className="capitalize text-blue-600">
                            {order.status}
                        </span>
                    </div>
                    <div className="flex justify-between">
                        <span>Email:</span>
                        <span>{order.Customer?.email}</span>
                    </div>
                </div>
            </div>

            {/* Next Steps */}
            <div className="space-y-4">
                <p className="text-gray-600">
                    {isGuest
                        ? "You will receive an email confirmation with your order details."
                        : "You will receive an email confirmation shortly with your order details."}
                </p>

                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        to="/products"
                        className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                    >
                        Continue Shopping
                    </Link>
                    <Link
                        to="/"
                        className="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition font-semibold"
                    >
                        Back to Home
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default OrderSuccess;
