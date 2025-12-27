import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { productService } from "../services/productService";
import { categoryService } from "../services/categoryService";
const Home = () => {
    const [featuredProducts, setFeaturedProducts] = useState([]);
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchHomeData();
    }, []);

    const fetchHomeData = async () => {
        try {
            setLoading(true);
            const [productsResponse, categoriesResponse] = await Promise.all([
                productService.getProducts({ per_page: 8 }),
                categoryService.getCategories(),
            ]);

            if (productsResponse.success)
                setFeaturedProducts(productsResponse.data);
            if (categoriesResponse.success)
                setCategories(categoriesResponse.data);
        } catch (error) {
            console.error("Failed to fetch home data:", error);
        } finally {
            setLoading(false);
        }
    };

    if (loading) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <div className="flex flex-col items-center space-y-4">
                    <div className="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
                    <p className="text-gray-600">Loading amazing products...</p>
                </div>
            </div>
        );
    }
    const getImageUrl = (category) => {
        const imageData = category.main_image;

        if (!imageData) return null;

        // If URL is already provided by backend
        if (imageData.url) {
            return imageData.url;
        }

        // Construct URL from path/filename
        const API_BASE_URL =
            import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";

        if (imageData.path) {
            return `${API_BASE_URL}/storage/${imageData.path}`;
        }

        if (imageData.filename) {
            return `${API_BASE_URL}/storage/images/${imageData.filename}`;
        }

        return null;
    };

    return (
        <div className="min-h-screen">
            {/* Hero Section */}
            <section className="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white overflow-hidden">
                <div className="absolute inset-0 bg-black/10"></div>
                <div className="relative w-full  px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
                    <div className="text-center">
                        <h1 className="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
                            Welcome to{" "}
                            <span className="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                                QuickBuy
                            </span>
                        </h1>
                        <p className="text-xl md:text-2xl mb-8 max-w-3xl mx-auto leading-relaxed opacity-95">
                            Discover amazing products at unbelievable prices.
                            Quality meets affordability in every purchase.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <Link
                                to="/products"
                                className="group bg-white text-blue-600 px-8 py-4 rounded-2xl font-semibold text-lg shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 transition-all duration-300 flex items-center space-x-2"
                            >
                                <span>Start Shopping</span>
                                <svg
                                    className="w-5 h-5 group-hover:translate-x-1 transition-transform"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                                    />
                                </svg>
                            </Link>
                            <Link
                                to="/categories"
                                className="border-2 border-white/80 text-white px-8 py-4 rounded-2xl font-semibold text-lg backdrop-blur-sm hover:bg-white/10 hover:border-white transform hover:-translate-y-1 transition-all duration-300"
                            >
                                Explore Categories
                            </Link>
                        </div>
                    </div>
                </div>

                {/* Wave Decoration */}
                <div className="absolute bottom-0 left-0 right-0">
                    <svg
                        viewBox="0 0 1200 120"
                        preserveAspectRatio="none"
                        className="w-full h-12"
                    >
                        <path
                            d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                            opacity=".25"
                            className="fill-white"
                        ></path>
                        <path
                            d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                            opacity=".5"
                            className="fill-white"
                        ></path>
                        <path
                            d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                            className="fill-white"
                        ></path>
                    </svg>
                </div>
            </section>

            {/* Featured Categories */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                            Shop by Category
                        </h2>
                        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                            Explore our carefully curated categories to find
                            exactly what you're looking for
                        </p>
                    </div>

                    <div className="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">
                        {categories.slice(0, 8).map((category, index) => (
                            <Link
                                key={category.id}
                                to={`/categories/${category.slug}`}
                                className="group relative bg-gradient-to-br from-white to-gray-50 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-500 border border-gray-100"
                            >
                                <div className="aspect-square relative overflow-hidden">
                                    {category.main_image ? (
                                        <img
                                            src={getImageUrl(category)}
                                            alt={category.name}
                                            className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                        />
                                    ) : (
                                        <div className="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                            <div className="text-center text-gray-500">
                                                <svg
                                                    className="w-12 h-12 mx-auto mb-2"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={1.5}
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                                    />
                                                </svg>
                                                <span className="text-sm font-medium">
                                                    No Image
                                                </span>
                                            </div>
                                        </div>
                                    )}
                                    <div className="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"></div>
                                </div>

                                <div className="p-6 text-center">
                                    <h3 className="font-bold text-gray-900 group-hover:text-blue-600 transition-colors text-lg mb-2">
                                        {category.name}
                                    </h3>
                                    <div className="w-12 h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mx-auto group-hover:w-16 transition-all duration-300"></div>
                                </div>
                            </Link>
                        ))}
                    </div>

                    {categories.length > 8 && (
                        <div className="text-center mt-12">
                            <Link
                                to="/categories"
                                className="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                            >
                                <span>View All Categories</span>
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
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"
                                    />
                                </svg>
                            </Link>
                        </div>
                    )}
                </div>
            </section>

            {/* Featured Products */}
            <section className="py-20 bg-gradient-to-br from-gray-50 to-blue-50/30">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                            Featured Products
                        </h2>
                        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                            Handpicked selection of our most popular and
                            trending items
                        </p>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                        {featuredProducts.map((product) => (
                            <div
                                key={product.id}
                                className="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-500 border border-gray-100"
                            >
                                <Link to={`/products/${product.slug}`}>
                                    <div className="aspect-square relative overflow-hidden">
                                        {product.main_image ? (
                                            <img
                                                src={getImageUrl(product)}
                                                alt={product.name}
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                            />
                                        ) : (
                                            <div className="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                <span className="text-gray-500 text-sm font-medium">
                                                    No Image
                                                </span>
                                            </div>
                                        )}
                                        <div className="absolute top-4 right-4">
                                            <span className="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                Featured
                                            </span>
                                        </div>
                                    </div>
                                </Link>

                                <div className="p-6">
                                    <Link to={`/products/${product.slug}`}>
                                        <h3 className="font-bold text-gray-900 hover:text-blue-600 transition-colors text-lg mb-3 line-clamp-2 leading-tight">
                                            {product.name}
                                        </h3>
                                    </Link>

                                    <div className="space-y-2">
                                        {product.skus
                                            ?.slice(0, 2)
                                            .map((sku) => (
                                                <div
                                                    key={sku.id}
                                                    className="flex justify-between items-center bg-gray-50 rounded-xl px-3 py-2"
                                                >
                                                    <div className="flex items-center space-x-2">
                                                        {sku.attributes
                                                            ?.color && (
                                                            <span className="text-xs text-gray-600 capitalize">
                                                                {
                                                                    sku
                                                                        .attributes
                                                                        .color
                                                                }
                                                            </span>
                                                        )}
                                                        {sku.attributes
                                                            ?.size && (
                                                            <span className="text-xs text-gray-500 border border-gray-300 px-1 rounded">
                                                                {
                                                                    sku
                                                                        .attributes
                                                                        .size
                                                                }
                                                            </span>
                                                        )}
                                                    </div>
                                                    <span className="font-bold text-green-600 text-lg">
                                                        ${sku.price}
                                                    </span>
                                                </div>
                                            ))}
                                    </div>

                                    {product.skus &&
                                        product.skus.length > 2 && (
                                            <div className="mt-3 text-center">
                                                <span className="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                    +{product.skus.length - 2}{" "}
                                                    more variants
                                                </span>
                                            </div>
                                        )}
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="text-center mt-12">
                        <Link
                            to="/products"
                            className="inline-flex items-center space-x-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                        >
                            <span>Explore All Products</span>
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
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                />
                            </svg>
                        </Link>
                    </div>
                </div>
            </section>

            {/* Features Section */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                        {[
                            {
                                icon: (
                                    <svg
                                        className="w-8 h-8"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                        />
                                    </svg>
                                ),
                                title: "Free Shipping",
                                description:
                                    "Free shipping on all orders over $50. Fast delivery to your doorstep.",
                                color: "from-blue-500 to-blue-600",
                            },
                            {
                                icon: (
                                    <svg
                                        className="w-8 h-8"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        />
                                    </svg>
                                ),
                                title: "Secure Payment",
                                description:
                                    "Your payment information is protected with bank-level security.",
                                color: "from-green-500 to-green-600",
                            },
                            {
                                icon: (
                                    <svg
                                        className="w-8 h-8"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                ),
                                title: "Quality Guarantee",
                                description:
                                    "30-day money back guarantee on all our premium products.",
                                color: "from-purple-500 to-purple-600",
                            },
                        ].map((feature, index) => (
                            <div key={index} className="text-center group">
                                <div
                                    className={`w-20 h-20 bg-gradient-to-r ${feature.color} rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:shadow-xl transform group-hover:scale-110 transition-all duration-300`}
                                >
                                    <div className="text-white">
                                        {feature.icon}
                                    </div>
                                </div>
                                <h3 className="text-2xl font-bold text-gray-900 mb-4">
                                    {feature.title}
                                </h3>
                                <p className="text-gray-600 leading-relaxed">
                                    {feature.description}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </div>
    );
};

export default Home;
