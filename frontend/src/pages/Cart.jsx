import React from "react";
import { Link, useNavigate } from "react-router-dom";
import { useCart } from "../context/CartContext";
import { useAuth } from "../context/AuthContext";

const Cart = () => {
    const { cart, updateQuantity, removeFromCart, clearCart } = useCart();
    const { user } = useAuth();
    const navigate = useNavigate();

    const handleQuantityChange = async (skuId, newQuantity) => {
        if (newQuantity === 0) {
            await removeFromCart(skuId);
        } else {
            await updateQuantity(skuId, newQuantity);
        }
    };

    const handleCheckout = () => {
        if (cart.items.length === 0) return;

        if (user) {
            navigate("/checkout");
        } else {
            // navigate("/login", { state: { returnTo: "/checkout" } }); //force guest to register or log in
             navigate("/checkout"); //guest will be directed to checkout
        }
    };

    if (cart.items.length === 0) {
        return (
            <div className="max-w-4xl mx-auto px-4 py-16 text-center">
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
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5.5M7 13l2.5 5.5m0 0L17 21"
                        />
                    </svg>
                </div>
                <h2 className="text-2xl font-bold text-gray-900 mb-4">
                    Your cart is empty
                </h2>
                <p className="text-gray-600 mb-8">
                    Start shopping to add items to your cart
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
        <div className="max-w-4xl mx-auto px-4 py-8">
            <div className="flex justify-between items-center mb-8">
                <h1 className="text-3xl font-bold text-gray-900">
                    Shopping Cart
                </h1>
                <button
                    onClick={clearCart}
                    className="text-red-600 hover:text-red-700 transition"
                >
                    Clear Cart
                </button>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Cart Items */}
                <div className="lg:col-span-2 space-y-4">
                    {cart.items.map((item) => (
                        <div
                            key={item.sku_id}
                            className="bg-white rounded-lg shadow-md p-6 flex items-center space-x-4"
                        >
                            {/* Sku Image */}
                            <div className="w-20 h-20 bg-gray-200 rounded flex-shrink-0 overflow-hidden">
                                {item.image ? (
                                    <img
                                        src={item.image}
                                        alt={item.product_name}
                                        className="w-full h-full object-cover"
                                        onError={(e) => {
                                            e.target.style.display = "none";
                                            e.target.nextSibling.style.display =
                                                "flex";
                                        }}
                                    />
                                ) : null}
                                <div
                                    className={`w-full h-full bg-gray-300 rounded flex items-center justify-center ${
                                        item.product_image ? "hidden" : "flex" // Changed here too
                                    }`}
                                >
                                    <span className="text-gray-500 text-xs">
                                        No Image
                                    </span>
                                </div>
                            </div>

                            {/* Product Info */}
                            <div className="flex-1">
                                <Link
                                    to={`/products/${item.product_slug}`}
                                    className="text-lg font-semibold text-gray-900 hover:text-blue-600 transition"
                                >
                                    {item.product_name}
                                </Link>
                                <p className="text-gray-600 text-sm">
                                    {item.attributes?.color} -{" "}
                                    {item.attributes?.size} -{" "}
                                    {item.attributes?.material}
                                </p>
                                <p className="text-sm text-gray-500">
                                    SKU: {item.sku_code}
                                </p>
                            </div>

                            {/* Quantity Controls */}
                            <div className="flex items-center space-x-2">
                                <button
                                    onClick={() =>
                                        handleQuantityChange(
                                            item.sku_id,
                                            item.quantity - 1
                                        )
                                    }
                                    className="w-8 h-8 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100"
                                >
                                    -
                                </button>
                                <span className="w-12 text-center">
                                    {item.quantity}
                                </span>
                                <button
                                    onClick={() =>
                                        handleQuantityChange(
                                            item.sku_id,
                                            item.quantity + 1
                                        )
                                    }
                                    disabled={item.quantity >= item.inventory}
                                    className="w-8 h-8 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100 disabled:opacity-50"
                                >
                                    +
                                </button>
                            </div>

                            {/* Price */}
                            <div className="text-right">
                                <div className="text-lg font-semibold">
                                    ${item.subtotal.toFixed(2)}
                                </div>
                                <div className="text-sm text-gray-500">
                                    ${item.price} each
                                </div>
                            </div>

                            {/* Remove Button */}
                            <button
                                onClick={() => removeFromCart(item.sku_id)}
                                className="text-red-600 hover:text-red-700 p-2"
                            >
                                <svg
                                    className="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    ))}
                </div>

                {/* Order Summary */}
                <div className="bg-white rounded-lg shadow-md p-6 h-fit">
                    <h3 className="text-xl font-semibold mb-4">
                        Order Summary
                    </h3>

                    <div className="space-y-3 mb-6">
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
                        <div className="border-t pt-3 flex justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span>${cart.total.toFixed(2)}</span>
                        </div>
                    </div>

                    <button
                        onClick={handleCheckout}
                        className="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition font-semibold mb-4"
                    >
                        Proceed to Checkout
                    </button>

                    <Link
                        to="/products"
                        className="w-full border border-gray-300 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-50 transition font-semibold text-center block"
                    >
                        Continue Shopping
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default Cart;
