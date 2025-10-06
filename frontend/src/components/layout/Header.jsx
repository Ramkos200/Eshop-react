import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import CartSidebar from '../cart/CartSidebar';
import { apiCalls } from '../../api';

const Header = () => {
    const [isCartOpen, setIsCartOpen] = useState(false);
    const [cartCount, setCartCount] = useState(0);

    const fetchCartCount = async () => {
        try {
            const response = await apiCalls.getCart();
            setCartCount(response.data.count);
        } catch (error) {
            console.error('Failed to fetch cart:', error);
        }
    };

    useEffect(() => {
        fetchCartCount();
    }, []);

    return (
        <>
            <header className="bg-white shadow-lg sticky top-0 z-40">
                <div className="container mx-auto px-4 py-4">
                    <div className="flex items-center justify-between">
                        {/* Logo */}
                        <Link to="/" className="text-2xl font-bold text-gray-800">
                            💡 QuickBuy
                        </Link>

                        {/* Navigation */}
                        <nav className="hidden md:flex space-x-8">
                            <Link to="/" className="text-gray-600 hover:text-gray-900">
                                Home
                            </Link>
                            <Link to="/products" className="text-gray-600 hover:text-gray-900">
                                Products
                            </Link>
                            <Link to="/cart" className="text-gray-600 hover:text-gray-900">
                                Cart
                            </Link>
                        </nav>

                        {/* Cart Icon */}
                        <button 
                            onClick={() => setIsCartOpen(true)}
                            className="relative p-2 text-gray-600 hover:text-gray-900"
                        >   🛒
                            {cartCount > 0 && (
                                <span className="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {cartCount}
                                </span>
                            )}
                        </button>
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