import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { AuthProvider } from "./context/AuthContext";
import { CartProvider } from "./context/CartContext";
import Navbar from "./components/layout/Navbar";
import Home from "./pages/Home";
import Products from "./pages/Products";
import Categories from "./pages/Categories";
import ProductDetail from "./pages/ProductDetail";
import Cart from "./pages/Cart";
import Login from "./pages/Login";
import Register from "./pages/Register";
import Checkout from "./pages/Checkout";
import OrderSuccess from "./pages/OrderSuccess";
import CategoryProducts from "./pages/CategoryProducts";
import Category from "./pages/Category";
import Profile from "./pages/Profile";
import Orders from "./pages/Orders";
import OrderDetails from "./pages/OrderDetails";
import Footer from "./components/layout/Footer";

function App() {
    return (
        <Router>
            <AuthProvider>
                <CartProvider>
                    <div className="min-h-screen bg-gray-50">
                        <Navbar />
                        <main>
                            <Routes>
                                <Route path="/" element={<Home />} />
                                <Route
                                    path="/products"
                                    element={<Products />}
                                />
                                <Route
                                    path="/categories"
                                    element={<Categories />} //done
                                />
                                <Route
                                    path="/products/:slug"
                                    element={<ProductDetail />}
                                />
                                <Route
                                    path="/categories/:slug"
                                    element={<Category />} //done
                                />
                                <Route path="/cart" element={<Cart />} />
                                <Route path="/login" element={<Login />} />
                                <Route
                                    path="/register"
                                    element={<Register />}
                                />
                                <Route
                                    path="/checkout"
                                    element={<Checkout />}
                                />
                                <Route
                                    path="/order-success"
                                    element={<OrderSuccess />}
                                />
                                <Route path="/profile" element={<Profile />} />
                                <Route path="/orders" element={<Orders />} />
                                <Route
                                    path="/orders/:orderCode"
                                    element={<OrderDetails />}
                                />
                            </Routes>
                        </main>
                        <Footer />
                    </div>
                </CartProvider>
            </AuthProvider>
        </Router>
    );
}

export default App;
