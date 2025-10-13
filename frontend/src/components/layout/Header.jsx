import React, { useState, useEffect } from "react";
import { Link, useLocation } from "react-router-dom";
import CartSidebar from "../cart/CartSidebar";
import { apiCalls } from "../../api";

const Header = () => {
    const [isCartOpen, setIsCartOpen] = useState(false);
    const [cartCount, setCartCount] = useState(0);
    const [isScrolled, setIsScrolled] = useState(false);
    const location = useLocation();

    const fetchCartCount = async () => {
        try {
            const response = await apiCalls.getCart();
            setCartCount(response.data.count);
        } catch (error) {
            console.error("Failed to fetch cart:", error);
        }
    };

    useEffect(() => {
        fetchCartCount();
    }, []);

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 10);
        };
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    const isActiveRoute = (path) => {
        return location.pathname === path;
    };

    return (
        <>
            <header
                className={`sticky top-0 z-50 transition-all duration-300 ${
                    isScrolled
                        ? "bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100"
                        : "bg-white border-b border-gray-100"
                }`}
            >
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        {/* Logo */}
                        <Link
                            to="/"
                            className="flex items-center space-x-3 group"
                        >
                            <div className="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-blue-200 transition-all duration-300">
                                <span className="text-white font-bold text-lg">
                                    QB
                                </span>
                            </div>
                            <span className="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                                QuickBuy
                            </span>
                        </Link>

                        {/* Navigation */}
                        <nav className="hidden md:flex items-center justify-between space-x-8">
                            {[
                                { path: "/", label: "Home" },
                                { path: "/products", label: "Products" },
                                { path: "/categories", label: "Categories" },
                            ].map((item) => (
                                <Link
                                    key={item.path}
                                    to={item.path}
                                    className={`relative px-3 py-2 text-sm font-medium transition-all duration-200 ${
                                        isActiveRoute(item.path)
                                            ? "text-blue-600"
                                            : "text-gray-600 hover:text-gray-900"
                                    }`}
                                >
                                    {item.label}
                                    {isActiveRoute(item.path) && (
                                        <span className="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 rounded-full"></span>
                                    )}
                                </Link>
                            ))}
                        </nav>

                        {/* Cart Icon */}
                        <div className="flex items-center space-x-4">
                            <button
                                onClick={() => setIsCartOpen(true)}
                                className="relative p-3 rounded-2xl bg-gradient-to-br from-gray-50 to-white hover:from-blue-50 hover:to-blue-100 border border-gray-200 hover:border-blue-200 shadow-sm hover:shadow-md transition-all duration-300 group"
                            >
                                <svg
                                    className="w-5 h-5 text-gray-600 group-hover:text-blue-600 transition-colors"
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
                                {cartCount > 0 && (
                                    <span className="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                        {cartCount}
                                    </span>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <CartSidebar
                isOpen={isCartOpen}
                onClose={() => setIsCartOpen(false)}
                onCartUpdate={fetchCartCount}
            />
        </>
    );
};

export default Header;
