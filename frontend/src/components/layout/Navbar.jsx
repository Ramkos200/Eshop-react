// frontend/src/components/Layout/Navbar.jsx
import React from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../../context/AuthContext";
import { useCart } from "../../context/CartContext";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faLightbulb,
    faShoppingCart,
    faUser,
    faHistory,
    faChevronDown,
} from "@fortawesome/free-solid-svg-icons";

const Navbar = () => {
    const { user, logout } = useAuth();
    const { cart } = useCart();
    const navigate = useNavigate();
    const [showDropdown, setShowDropdown] = React.useState(false);

    const handleLogout = async () => {
        await logout();
        navigate("/");
        setShowDropdown(false);
    };

    return (
        <nav className="bg-black text-white shadow-lg sticky top-0 z-50">
            <div className="w-full mx-auto px-5 py-5">
                <div className="flex justify-between items-center h-16">
                    {/* Logo */}
                    <Link to="/" className="flex items-center space-x-2">
                        <FontAwesomeIcon
                            icon={faLightbulb}
                            className="text-amber-500 text-3xl mr-2"
                        />
                        <span className="text-4xl font-semibold text-white font-['Cormorant_Garamond']">
                            QuickBuy
                        </span>
                    </Link>

                    {/* Navigation Links */}
                    <div className="hidden md:flex items-center justify-between w-full max-w-md">
                        <Link
                            to="/"
                            className="text-white hover:text-blue-600 transition"
                        >
                            Home
                        </Link>
                        <Link
                            to="/categories"
                            className="text-white hover:text-blue-600 transition"
                        >
                            Categories
                        </Link>
                        <Link
                            to="/products"
                            className="text-white hover:text-blue-600 transition"
                        >
                            Products
                        </Link>
                    </div>

                    {/* Right Side - Cart & Auth */}
                    <div className="flex items-center space-x-4">
                        {/* Cart */}
                        <Link
                            to="/cart"
                            className="relative p-2 text-gray-600 hover:text-blue-600 transition"
                        >
                            <FontAwesomeIcon
                                icon={faShoppingCart}
                                className="text-amber-500 text-xl mr-2"
                            />
                            {cart.count > 0 && (
                                <span className="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {cart.count}
                                </span>
                            )}
                        </Link>

                        {/* Auth Section */}
                        {user ? (
                            <div className="relative">
                                {/* User Dropdown */}
                                <button
                                    onClick={() =>
                                        setShowDropdown(!showDropdown)
                                    }
                                    className="flex items-center space-x-2 bg-blue-700 px-4 py-2 rounded hover:bg-blue-900 transition"
                                >
                                    <FontAwesomeIcon
                                        icon={faUser}
                                        className="text-sm"
                                    />
                                    <span className="text-white">
                                        Hello, {user.name}
                                    </span>
                                    <FontAwesomeIcon
                                        icon={faChevronDown}
                                        className={`text-sm transition-transform ${
                                            showDropdown ? "rotate-180" : ""
                                        }`}
                                    />
                                </button>

                                {/* Dropdown Menu */}
                                {showDropdown && (
                                    <div className="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <Link
                                            to="/profile"
                                            onClick={() =>
                                                setShowDropdown(false)
                                            }
                                            className="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            <FontAwesomeIcon
                                                icon={faUser}
                                                className="mr-2"
                                            />
                                            My Profile
                                        </Link>
                                        <Link
                                            to="/orders"
                                            onClick={() =>
                                                setShowDropdown(false)
                                            }
                                            className="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            <FontAwesomeIcon
                                                icon={faHistory}
                                                className="mr-2"
                                            />
                                            My Orders
                                        </Link>
                                        <div className="border-t border-gray-100">
                                            <button
                                                onClick={handleLogout}
                                                className="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                            >
                                                Logout
                                            </button>
                                        </div>
                                    </div>
                                )}
                            </div>
                        ) : (
                            <div className="flex space-x-4">
                                <Link
                                    to="/login"
                                    className="text-white hover:text-blue-600 m-2 p-2 transition"
                                >
                                    Login
                                </Link>
                                <Link
                                    to="/register"
                                    className="bg-blue-600 text-white m-2 p-2 rounded hover:bg-blue-700 transition"
                                >
                                    Sign Up
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </nav>
    );
};

export default Navbar;
