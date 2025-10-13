// frontend/src/pages/Checkout.jsx
import React, { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { useCart } from "../context/CartContext";
import { useAuth } from "../context/AuthContext";
import { orderService } from "../services/orderService";
import { cartService } from "../services/cartService";

const Checkout = () => {
    const { cart, clearCart } = useCart();
    const { user } = useAuth();
    const navigate = useNavigate();

    const [formData, setFormData] = useState({
        // Customer Information
        customer_name: user?.name || "",
        customer_email: user?.email || "",
        customer_phone: user?.phone || "",

        // Shipping Address
        shipping_street: "",
        shipping_city: "",
        shipping_state: "",
        shipping_zip: "",
        shipping_country: "",

        // Payment Method
        payment: "credit_card", // Default payment method

        // Order Notes
        notes: "",
    });

    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");

    // Payment methods configuration
    const paymentMethods = [
        {
            id: "credit_card",
            name: "Credit Card",
            description: "Pay with Visa, Mastercard, or American Express",
            icon: "💳",
        },
        {
            id: "paypal",
            name: "PayPal",
            description: "Pay securely with your PayPal account",
            icon: "🔵",
        },
        {
            id: "bank_transfer",
            name: "Bank Transfer",
            description: "Direct bank transfer",
            icon: "🏦",
        },
        {
            id: "cash_on_delivery",
            name: "Cash on Delivery",
            description: "Pay when you receive your order",
            icon: "💰",
        },
    ];

    // Pre-fill user data if logged in
    React.useEffect(() => {
        if (user) {
            setFormData((prev) => ({
                ...prev,
                customer_name: user.name,
                customer_email: user.email,
                customer_phone: user.phone || "",
            }));
        }
    }, [user]);

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
        setError("");
    };

    const handlePaymentMethodChange = (methodId) => {
        setFormData({
            ...formData,
            payment: methodId,
        });
    };
    const validateForm = () => {
        const requiredFields = [
            "customer_name",
            "customer_email",
            "shipping_street",
            "shipping_city",
            "shipping_state",
            "shipping_zip",
            "shipping_country",
        ];

        for (let field of requiredFields) {
            if (!formData[field]?.trim()) {
                setError(`Please fill in the ${field.replace("_", " ")}`);
                return false;
            }
        }

        if (!/\S+@\S+\.\S+/.test(formData.customer_email)) {
            setError("Please enter a valid email address");
            return false;
        }

        return true;
    };
    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError("");

        if (cart.items.length === 0) {
            setError(
                "Your cart is empty. Please add items before placing an order."
            );
            setLoading(false);
            return;
        }

        try {
            const response = await orderService.createOrder(formData);

            if (response.success) {
                await clearCart();

                if (!user) {
                    navigate("/order-success", {
                        state: {
                            order: response.order,
                            orderCode: response.order_code,
                            paymentMethod: formData.payment,
                            isGuest: true,
                        },
                    });
                } else {
                    navigate("/order-success", {
                        state: {
                            order: response.order,
                            orderCode: response.order_code,
                            paymentMethod: formData.payment,
                            isGuest: false,
                        },
                    });
                }
            }
        } catch (error) {
            const errorMessage =
                error.response?.data?.error ||
                error.message ||
                "Failed to create order";

            setError(errorMessage);
        }
    };

    if (cart.items.length === 0) {
        return (
            <div className="max-w-4xl mx-auto px-4 py-16 text-center">
                <h2 className="text-2xl font-bold text-gray-900 mb-4">
                    Your cart is empty
                </h2>
                <p className="text-gray-600 mb-8">
                    Add some products to your cart before checkout
                </p>
                <Link
                    to="/products"
                    className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition"
                >
                    Continue Shopping
                </Link>
            </div>
        );
    }

    return (
        <div className="max-w-7xl mx-auto px-4 py-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* Checkout Form */}
                <div className="space-y-8">
                    <form onSubmit={handleSubmit} className="space-y-6">
                        {error && (
                            <div className="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                                {error}
                            </div>
                        )}

                        {/* Customer Information */}
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h2 className="text-xl font-semibold mb-4">
                                Customer Information
                            </h2>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        htmlFor="customer_name"
                                        className="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Full Name *
                                    </label>
                                    <input
                                        type="text"
                                        id="customer_name"
                                        name="customer_name"
                                        required
                                        value={formData.customer_name}
                                        onChange={handleChange}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>

                                <div>
                                    <label
                                        htmlFor="customer_email"
                                        className="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Email *
                                    </label>
                                    <input
                                        type="email"
                                        id="customer_email"
                                        name="customer_email"
                                        required
                                        value={formData.customer_email}
                                        onChange={handleChange}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>

                                <div>
                                    <label
                                        htmlFor="customer_phone"
                                        className="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Phone Number
                                    </label>
                                    <input
                                        type="tel"
                                        id="customer_phone"
                                        name="customer_phone"
                                        value={formData.customer_phone}
                                        onChange={handleChange}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                            </div>
                        </div>

                        {/* Shipping Address */}
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h2 className="text-xl font-semibold mb-4">
                                Shipping Address
                            </h2>

                            <div className="space-y-4">
                                <div>
                                    <label
                                        htmlFor="shipping_street"
                                        className="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Street Address *
                                    </label>
                                    <input
                                        type="text"
                                        id="shipping_street"
                                        name="shipping_street"
                                        required
                                        value={formData.shipping_street}
                                        onChange={handleChange}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            htmlFor="shipping_city"
                                            className="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            City *
                                        </label>
                                        <input
                                            type="text"
                                            id="shipping_city"
                                            name="shipping_city"
                                            required
                                            value={formData.shipping_city}
                                            onChange={handleChange}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>

                                    <div>
                                        <label
                                            htmlFor="shipping_state"
                                            className="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            State *
                                        </label>
                                        <input
                                            type="text"
                                            id="shipping_state"
                                            name="shipping_state"
                                            required
                                            value={formData.shipping_state}
                                            onChange={handleChange}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            htmlFor="shipping_zip"
                                            className="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            ZIP Code *
                                        </label>
                                        <input
                                            type="text"
                                            id="shipping_zip"
                                            name="shipping_zip"
                                            required
                                            value={formData.shipping_zip}
                                            onChange={handleChange}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>

                                    <div>
                                        <label
                                            htmlFor="shipping_country"
                                            className="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Country *
                                        </label>
                                        <input
                                            type="text"
                                            id="shipping_country"
                                            name="shipping_country"
                                            required
                                            value={formData.shipping_country}
                                            onChange={handleChange}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Payment Method */}
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h2 className="text-xl font-semibold mb-4">
                                Payment Method
                            </h2>

                            <div className="space-y-3">
                                {paymentMethods.map((method) => (
                                    <div
                                        key={method.id}
                                        className={`border rounded-lg p-4 cursor-pointer transition-all ${
                                            formData.payment === method.id
                                                ? "border-blue-500 bg-blue-50"
                                                : "border-gray-300 hover:border-gray-400"
                                        }`}
                                        onClick={() =>
                                            handlePaymentMethodChange(method.id)
                                        }
                                    >
                                        <div className="flex items-center space-x-3">
                                            <div
                                                className={`w-5 h-5 rounded-full border-2 flex items-center justify-center ${
                                                    formData.payment ===
                                                    method.id
                                                        ? "border-blue-500 bg-blue-500"
                                                        : "border-gray-400"
                                                }`}
                                            >
                                                {formData.payment ===
                                                    method.id && (
                                                    <div className="w-2 h-2 bg-white rounded-full"></div>
                                                )}
                                            </div>
                                            <div className="flex items-center space-x-2">
                                                <span className="text-lg">
                                                    {method.icon}
                                                </span>
                                                <span className="font-medium">
                                                    {method.name}
                                                </span>
                                            </div>
                                        </div>
                                        <p className="text-sm text-gray-600 mt-1 ml-8">
                                            {method.description}
                                        </p>
                                    </div>
                                ))}
                            </div>

                            {/* Payment Method Specific Fields */}
                            {formData.payment === "credit_card" && (
                                <div className="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <h3 className="font-medium mb-3">
                                        Credit Card Details
                                    </h3>
                                    <div className="space-y-3">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                Card Number
                                            </label>
                                            <input
                                                type="text"
                                                placeholder="1234 5678 9012 3456"
                                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            />
                                        </div>
                                        <div className="grid grid-cols-2 gap-3">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    Expiry Date
                                                </label>
                                                <input
                                                    type="text"
                                                    placeholder="MM/YY"
                                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                />
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                                    CVV
                                                </label>
                                                <input
                                                    type="text"
                                                    placeholder="123"
                                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {formData.payment === "paypal" && (
                                <div className="mt-4 p-4 bg-yellow-50 rounded-lg">
                                    <p className="text-sm text-yellow-700">
                                        You will be redirected to PayPal to
                                        complete your payment after placing the
                                        order.
                                    </p>
                                </div>
                            )}

                            {formData.payment === "bank_transfer" && (
                                <div className="mt-4 p-4 bg-green-50 rounded-lg">
                                    <h3 className="font-medium mb-2">
                                        Bank Transfer Instructions
                                    </h3>
                                    <p className="text-sm text-green-700">
                                        After placing your order, you will
                                        receive our bank account details via
                                        email. Please complete the transfer
                                        within 24 hours.
                                    </p>
                                </div>
                            )}

                            {formData.payment === "cash_on_delivery" && (
                                <div className="mt-4 p-4 bg-orange-50 rounded-lg">
                                    <p className="text-sm text-orange-700">
                                        Please have the exact amount ready for
                                        the delivery person. Cash only.
                                    </p>
                                </div>
                            )}
                        </div>

                        {/* Order Notes */}
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h2 className="text-xl font-semibold mb-4">
                                Additional Information
                            </h2>
                            <label
                                htmlFor="notes"
                                className="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Order Notes
                            </label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                value={formData.notes}
                                onChange={handleChange}
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Any special instructions for your order..."
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full bg-blue-600 text-white py-4 px-6 rounded-lg hover:bg-blue-700 transition font-semibold text-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? (
                                <div className="flex items-center justify-center">
                                    <div className="animate-spin rounded-full h-6 w-6 border-b-2 border-white mr-2"></div>
                                    Processing Order...
                                </div>
                            ) : (
                                `Place Order - $${cart.total.toFixed(2)}`
                            )}
                        </button>
                    </form>
                </div>

                {/* Order Summary */}
                <div className="space-y-6">
                    <div className="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h2 className="text-xl font-semibold mb-4">
                            Order Summary
                        </h2>

                        {/* Cart Items */}
                        <div className="space-y-4 mb-6">
                            {cart.items.map((item) => (
                                <div
                                    key={item.sku_id}
                                    className="flex items-center space-x-3"
                                >
                                    <div className="w-12 h-12 bg-gray-200 rounded flex-shrink-0">
                                        {item.image && (
                                            <img
                                                src={item.image}
                                                alt={item.product_name}
                                                className="w-full h-full object-cover rounded"
                                            />
                                        )}
                                    </div>
                                    <div className="flex-1">
                                        <p className="font-medium text-sm">
                                            {item.product_name}
                                        </p>
                                        <p className="text-gray-500 text-xs">
                                            {item.attributes?.color} -{" "}
                                            {item.attributes?.size}
                                        </p>
                                        <p className="text-gray-500 text-xs">
                                            Qty: {item.quantity}
                                        </p>
                                    </div>
                                    <div className="text-sm font-semibold">
                                        ${item.subtotal.toFixed(2)}
                                    </div>
                                </div>
                            ))}
                        </div>

                        {/* Order Totals */}
                        <div className="border-t pt-4 space-y-2">
                            <div className="flex justify-between">
                                <span>Subtotal ({cart.count} items)</span>
                                <span>${cart.total.toFixed(2)}</span>
                            </div>
                            <div className="flex justify-between">
                                <span>Shipping</span>
                                <span className="text-green-600">Free</span>
                            </div>
                            <div className="flex justify-between">
                                <span>Tax</span>
                                <span>Calculated at checkout</span>
                            </div>
                            <div className="border-t pt-2 flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>${cart.total.toFixed(2)}</span>
                            </div>
                        </div>
                    </div>

                    {/* Selected Payment Method */}
                    <div className="bg-white rounded-lg shadow-md p-6">
                        <h3 className="text-lg font-semibold mb-3">
                            Selected Payment
                        </h3>
                        <div className="flex items-center space-x-2 text-gray-700">
                            <span className="text-lg">
                                {
                                    paymentMethods.find(
                                        (m) => m.id === formData.payment
                                    )?.icon
                                }
                            </span>
                            <span>
                                {
                                    paymentMethods.find(
                                        (m) => m.id === formData.payment
                                    )?.name
                                }
                            </span>
                        </div>
                    </div>

                    {/* Security Notice */}
                    <div className="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div className="flex items-center space-x-2 text-green-700">
                            <svg
                                className="w-5 h-5"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fillRule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clipRule="evenodd"
                                />
                            </svg>
                            <span className="text-sm font-medium">
                                Secure checkout
                            </span>
                        </div>
                        <p className="text-green-600 text-sm mt-1">
                            Your personal and payment information is encrypted
                            and secure.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Checkout;
