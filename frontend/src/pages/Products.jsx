import React, { useState, useEffect } from "react";
import {
    useParams,
    Link,
    useSearchParams,
    useNavigate,
} from "react-router-dom";
import { productService } from "../services/productService";
import { categoryService } from "../services/categoryService";
import { useCart } from "../context/CartContext";
import CategorySidebar from "../components/category/CategorySidebar.jsx";
const Products = () => {
    const { slug } = useParams();
    const [searchParams, setSearchParams] = useSearchParams();
    const navigate = useNavigate();
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedCategory, setSelectedCategory] = useState(null);
    const [searchTerm, setSearchTerm] = useState(
        searchParams.get("search") || ""
    );
    const [sortBy, setSortBy] = useState("created_at");
    const [sortOrder, setSortOrder] = useState("desc");
    const [categoryLoading, setCategoryLoading] = useState(false);
    const [viewMode, setViewMode] = useState("grid"); // grid or list

    const { addToCart } = useCart();

    // Fetch category when slug changes
    useEffect(() => {
        if (slug) {
            fetchCategoryBySlug();
        } else {
            setSelectedCategory(null);
        }
    }, [slug]);

    // Fetch products when filters change
    useEffect(() => {
        fetchProducts();
    }, [selectedCategory, searchTerm, sortBy, sortOrder]);

    const fetchCategoryBySlug = async () => {
        try {
            setCategoryLoading(true);
            const response = await categoryService.getCategory(slug);
            if (response.success) {
                setSelectedCategory(response.data);
            } else {
                navigate("/products");
            }
        } catch (error) {
            console.error("Failed to fetch category:", error);
            navigate("/products");
        } finally {
            setCategoryLoading(false);
        }
    };

    const fetchProducts = async () => {
        try {
            setLoading(true);
            const params = {
                sort: sortBy,
                direction: sortOrder,
            };

            if (slug) {
                params.category = slug;
            } else if (selectedCategory && !slug) {
                params.category_id = selectedCategory.id;
            }

            if (searchTerm) {
                params.search = searchTerm;
                setSearchParams({ search: searchTerm });
            } else {
                setSearchParams({});
            }

            const response = await productService.getProducts(params);
            if (response.success) {
                setProducts(response.data);
            }
        } catch (error) {
            console.error("Failed to fetch products:", error);
        } finally {
            setLoading(false);
        }
    };

    const handleCategorySelect = (category) => {
        if (category) {
            navigate(`/categories/${category.slug}`);
        } else {
            navigate("/products");
        }
        setSelectedCategory(category);
        setSearchTerm("");
    };

    const handleClearFilters = () => {
        setSearchTerm("");
        setSelectedCategory(null);
        navigate("/products");
    };

    const handleSearch = (e) => {
        e.preventDefault();
        fetchProducts();
    };

    const handleAddToCart = async (sku) => {
        try {
            await addToCart(sku.id, 1);
        } catch (error) {
            alert(error.response?.data?.message || "Failed to add to cart");
        }
    };

    const handleSortChange = (e) => {
        const [field, order] = e.target.value.split("_");
        setSortBy(field);
        setSortOrder(order);
    };

    const getProductImageUrl = (product) => {
        const imageData = product.main_image;

        if (!imageData) return null;

        if (imageData.url) {
            return imageData.url;
        }

        if (typeof imageData === "string") {
            return imageData;
        }

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
    const getCategoryImageUrl = (category) => {
        const imageData = category?.main_image;

        if (!imageData) return null;

        if (imageData.url) {
            return imageData.url;
        }

        if (typeof imageData === "string") {
            return imageData;
        }

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
        <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
            {/* Header Section */}
            <div className="bg-white/80 backdrop-blur-sm border-b border-slate-200/60 sticky top-0 z-40">
                <div className="w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        {/* Breadcrumb */}
                        <nav className="flex items-center space-x-2 text-sm">
                            <Link
                                to="/"
                                className="text-slate-600 hover:text-slate-900 transition-colors"
                            >
                                Home
                            </Link>
                            <span className="text-slate-400">›</span>
                            <Link
                                to="/products"
                                className="text-slate-600 hover:text-slate-900 transition-colors"
                            >
                                Products
                            </Link>
                            {selectedCategory && (
                                <>
                                    <span className="text-slate-400">›</span>
                                    <span className="text-slate-900 font-medium">
                                        {selectedCategory.name}
                                    </span>
                                </>
                            )}
                        </nav>
                    </div>
                </div>
            </div>

            <div className="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div className="flex flex-col lg:flex-row gap-8">
                    {/* Sidebar */}
                    <div className="lg:w-80">
                        <CategorySidebar
                            onCategorySelect={handleCategorySelect}
                            currentCategory={selectedCategory}
                        />
                    </div>

                    {/* Main Content */}
                    <div className="flex-1">
                        {/* Hero Section */}
                        <div className="mb-8">
                            <div className="relative rounded-2xl overflow-hidden shadow-xl">
                                {/* Background Image with Overlay */}
                                <div className="absolute inset-0">
                                    {selectedCategory ? (
                                        <>
                                            {getCategoryImageUrl(
                                                selectedCategory
                                            ) ? (
                                                <img
                                                    src={getCategoryImageUrl(
                                                        selectedCategory
                                                    )}
                                                    alt={selectedCategory.name}
                                                    className="w-full h-full object-cover"
                                                />
                                            ) : (
                                                <div className="w-full h-full bg-gradient-to-r from-blue-600 to-purple-600"></div>
                                            )}
                                            {/* Dark Overlay for better text readability */}
                                            <div className="absolute inset-0 bg-black/40"></div>
                                        </>
                                    ) : (
                                        <div className="w-full h-full bg-gradient-to-r from-blue-600 to-purple-600"></div>
                                    )}
                                </div>

                                {/* Content */}
                                <div className="relative z-10 p-8 text-white">
                                    <h1 className="text-4xl font-bold mb-4">
                                        {selectedCategory
                                            ? selectedCategory.name
                                            : "All Products"}
                                    </h1>
                                    <p className="text-white/90 text-lg mb-6 max-w-2xl">
                                        {selectedCategory?.description ||
                                            "Discover our curated collection of premium products designed to elevate your experience."}
                                    </p>
                                    <div className="flex items-center gap-4">
                                        <div className="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 border border-white/30">
                                            <span className="font-semibold">
                                                {products.length} products
                                            </span>
                                        </div>
                                        <div className="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 border border-white/30">
                                            <span className="font-semibold">
                                                Free Shipping
                                            </span>
                                        </div>
                                        {selectedCategory?.parent && (
                                            <div className="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 border border-white/30">
                                                <span className="font-semibold">
                                                    In:{" "}
                                                    {
                                                        selectedCategory.parent
                                                            .name
                                                    }
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Controls Bar */}
                        <div className="bg-white/80 backdrop-blur-sm rounded-2xl p-6 mb-8 shadow-sm border border-slate-200/60">
                            <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                {/* Search */}
                                <form
                                    onSubmit={handleSearch}
                                    className="flex-1 max-w-md"
                                >
                                    <div className="relative">
                                        <input
                                            type="text"
                                            placeholder="Search products..."
                                            value={searchTerm}
                                            onChange={(e) =>
                                                setSearchTerm(e.target.value)
                                            }
                                            className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        />
                                        <svg
                                            className="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                            />
                                        </svg>
                                    </div>
                                </form>

                                {/* Sort and Filter */}
                                <div className="flex items-center gap-4">
                                    <select
                                        value={`${sortBy}_${sortOrder}`}
                                        onChange={handleSortChange}
                                        className="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    >
                                        <option value="created_at_desc">
                                            Newest First
                                        </option>
                                        <option value="created_at_asc">
                                            Oldest First
                                        </option>
                                        <option value="name_asc">
                                            Name A-Z
                                        </option>
                                        <option value="name_desc">
                                            Name Z-A
                                        </option>
                                        <option value="price_asc">
                                            Price: Low to High
                                        </option>
                                        <option value="price_desc">
                                            Price: High to Low
                                        </option>
                                    </select>

                                    <button
                                        onClick={handleClearFilters}
                                        className="px-6 py-3 border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition-all font-medium"
                                    >
                                        Clear All
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Products Grid */}
                        <div className="flex items-center space-x-2 mb-2">
                            <button
                                onClick={() => setViewMode("grid")}
                                className={`p-2 rounded-lg transition-all ${
                                    viewMode === "grid"
                                        ? "bg-blue-100 text-blue-600"
                                        : "text-slate-400 hover:text-slate-600"
                                }`}
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
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                    />
                                </svg>
                            </button>
                            <button
                                onClick={() => setViewMode("list")}
                                className={`p-2 rounded-lg transition-all ${
                                    viewMode === "list"
                                        ? "bg-blue-100 text-blue-600"
                                        : "text-slate-400 hover:text-slate-600"
                                }`}
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
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                </svg>
                            </button>
                        </div>
                        {loading || categoryLoading ? (
                            <div
                                className={`gap-6 ${
                                    viewMode === "grid"
                                        ? "grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                                        : "space-y-4"
                                }`}
                            >
                                {[...Array(8)].map((_, i) => (
                                    <ProductCardSkeleton
                                        key={i}
                                        viewMode={viewMode}
                                    />
                                ))}
                            </div>
                        ) : (
                            <>
                                {products.length > 0 ? (
                                    <div
                                        className={`gap-6 ${
                                            viewMode === "grid"
                                                ? "grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                                                : "space-y-4"
                                        }`}
                                    >
                                        {products.map((product) => (
                                            <ProductCard
                                                key={product.id}
                                                product={product}
                                                onAddToCart={handleAddToCart}
                                                getProductImageUrl={
                                                    getProductImageUrl
                                                }
                                                viewMode={viewMode}
                                            />
                                        ))}
                                    </div>
                                ) : (
                                    <div className="text-center py-16 bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60">
                                        <div className="w-32 h-32 bg-gradient-to-br from-blue-50 to-purple-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg
                                                className="w-16 h-16 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth={1}
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                                />
                                            </svg>
                                        </div>
                                        <h3 className="text-2xl font-bold text-slate-900 mb-3">
                                            No products found
                                        </h3>
                                        <p className="text-slate-600 mb-6 max-w-md mx-auto">
                                            We couldn't find any products
                                            matching your search criteria.
                                        </p>
                                        <button
                                            onClick={handleClearFilters}
                                            className="bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700 transition-all font-medium shadow-lg hover:shadow-xl"
                                        >
                                            Clear Filters & Show All
                                        </button>
                                    </div>
                                )}
                            </>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

// Product Card Component
const ProductCard = ({
    product,
    onAddToCart,
    getProductImageUrl,
    viewMode,
}) => {
    const hasAvailableSkus = product.skus?.some((sku) => sku.inventory > 0);
    const productImageUrl = getProductImageUrl(product);
    const firstSku = product.skus?.[0];

    if (viewMode === "list") {
        return product.status === "Published" ? (
            <div className="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 hover:shadow-md transition-all duration-300 group">
                <div className="flex gap-6">
                    {/* Product Image */}
                    <Link
                        to={`/products/${product.slug}`}
                        className="flex-shrink-0"
                    >
                        <div className="w-32 h-32 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden">
                            {productImageUrl ? (
                                <img
                                    src={productImageUrl}
                                    alt={product.name}
                                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                />
                            ) : (
                                <div className="w-full h-full flex items-center justify-center">
                                    <span className="text-slate-400 text-sm">
                                        No Image
                                    </span>
                                </div>
                            )}
                        </div>
                    </Link>

                    {/* Product Info */}
                    <div className="flex-1 min-w-0">
                        <div className="flex items-start justify-between mb-3">
                            <div>
                                <Link to={`/products/${product.slug}`}>
                                    <h3 className="text-xl font-semibold text-slate-900 hover:text-blue-600 transition-colors line-clamp-2 mb-2">
                                        {product.name}
                                    </h3>
                                </Link>
                                <p className="text-slate-600 line-clamp-2 mb-4">
                                    {product.description}
                                </p>
                            </div>
                            <div className="text-right">
                                {product.skus && product.skus.length > 0 ? (
                                    (() => {
                                        const prices = product.skus.map((sku) =>
                                            parseFloat(sku.price)
                                        );
                                        const minPrice = Math.min(...prices);
                                        const maxPrice = Math.max(...prices);
                                        const hasRange = minPrice !== maxPrice;

                                        return (
                                            <div className="text-right">
                                                {hasRange ? (
                                                    <>
                                                        <div className="text-l font-bold text-slate-900 mb-1">
                                                            ${minPrice} - $
                                                            {maxPrice}
                                                        </div>
                                                        <div className="text-xs text-slate-500 mb-2">
                                                            Price varies
                                                        </div>
                                                    </>
                                                ) : (
                                                    <div className="text-l font-bold text-slate-900 mb-2">
                                                        ${minPrice}
                                                    </div>
                                                )}
                                                <span
                                                    className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${
                                                        product.status ===
                                                        "Published"
                                                            ? "bg-green-100 text-green-800"
                                                            : "bg-slate-100 text-slate-800"
                                                    }`}
                                                >
                                                    {product.status}
                                                </span>
                                            </div>
                                        );
                                    })()
                                ) : (
                                    <div className="text-right">
                                        <div className="text-l font-bold text-slate-900 mb-2">
                                            ${product.price}
                                        </div>
                                        <span
                                            className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${
                                                product.status === "Published"
                                                    ? "bg-green-100 text-green-800"
                                                    : "bg-slate-100 text-slate-800"
                                            }`}
                                        >
                                            {product.status}
                                        </span>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-3">
                                {firstSku && (
                                    <button
                                        onClick={() => onAddToCart(firstSku)}
                                        disabled={!hasAvailableSkus}
                                        className={`px-6 py-2 rounded-lg font-medium transition-all ${
                                            hasAvailableSkus
                                                ? "bg-blue-600 text-white hover:bg-blue-700 shadow-lg hover:shadow-xl"
                                                : "bg-slate-200 text-slate-400 cursor-not-allowed"
                                        }`}
                                    >
                                        {hasAvailableSkus
                                            ? "Add to Cart"
                                            : "Out of Stock"}
                                    </button>
                                )}
                                <Link
                                    to={`/products/${product.slug}`}
                                    className="px-6 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-all font-medium"
                                >
                                    View Details
                                </Link>
                            </div>
                            {product.skus && product.skus.length > 1 && (
                                <span className="text-sm text-slate-500">
                                    {product.skus.length} variants available
                                </span>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        ) : null;
    }

    // Grid View
    return (
        product.status === "Published" && (
            <div className="group bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden shadow-sm border border-slate-200/60 hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                {/* Product Image */}
                <Link
                    to={`/products/${product.slug}`}
                    className="block relative"
                >
                    <div className="aspect-square bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden">
                        {productImageUrl ? (
                            <img
                                src={productImageUrl}
                                alt={product.name}
                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            />
                        ) : (
                            <div className="w-full h-full flex items-center justify-center">
                                <span className="text-slate-400">No Image</span>
                            </div>
                        )}
                    </div>

                    {/* Status Badge */}
                    <div className="absolute top-4 right-4">
                        {!hasAvailableSkus && (
                            <span className="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                Out of Stock
                            </span>
                        )}
                    </div>

                    {/* Quick Actions Overlay */}
                    <div className="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <button className="bg-white text-slate-900 px-6 py-3 rounded-lg font-medium transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-lg hover:bg-slate-50">
                            Quick View
                        </button>
                    </div>
                </Link>

                {/* Product Info */}
                <div className="p-6">
                    <Link to={`/products/${product.slug}`}>
                        <h3 className="font-semibold text-slate-900 hover:text-blue-600 transition-colors text-lg mb-2 line-clamp-2 leading-tight">
                            {product.name}
                        </h3>
                    </Link>

                    <p className="text-slate-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                        {product.description}
                    </p>

                    {/* Price and Status */}
                    <div className="flex items-center justify-between mb-4">
                        <div className="flex items-center gap-2">
                            {product.skus && product.skus.length > 0 ? (
                                (() => {
                                    const prices = product.skus.map((sku) =>
                                        parseFloat(sku.price)
                                    );
                                    const minPrice = Math.min(...prices);
                                    const maxPrice = Math.max(...prices);
                                    const hasRange = minPrice !== maxPrice;

                                    return (
                                        <div className="flex flex-col">
                                            {hasRange ? (
                                                <>
                                                    <span className="text-l font-bold text-slate-900">
                                                        ${minPrice} - $
                                                        {maxPrice}
                                                    </span>
                                                    <span className="text-xs text-slate-500">
                                                        Price varies by options
                                                    </span>
                                                </>
                                            ) : (
                                                <span className="text-l font-bold text-slate-900">
                                                    ${minPrice}
                                                </span>
                                            )}
                                        </div>
                                    );
                                })()
                            ) : (
                                <span className="text-l font-bold text-slate-900">
                                    ${product.price}
                                </span>
                            )}
                        </div>
                        <span
                            className={`px-3 py-1 rounded-full text-xs font-medium ${
                                product.status === "Published"
                                    ? "bg-green-100 text-green-800"
                                    : "bg-slate-100 text-slate-800"
                            }`}
                        >
                            {product.status}
                        </span>
                    </div>

                    {/* Action Buttons */}
                    <div className="flex gap-2">
                        <Link
                            to={`/products/${product.slug}`}
                            className="flex-1 bg-slate-900 text-white text-center py-3 rounded-lg hover:bg-slate-800 transition-all font-medium text-sm"
                        >
                            View Details
                        </Link>
                        <button
                            onClick={() => firstSku && onAddToCart(firstSku)}
                            disabled={!hasAvailableSkus}
                            className={`p-3 rounded-lg transition-all font-medium text-sm ${
                                hasAvailableSkus
                                    ? "bg-blue-600 text-white hover:bg-blue-700 shadow-lg hover:shadow-xl"
                                    : "bg-slate-200 text-slate-400 cursor-not-allowed"
                            }`}
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
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        )
    );
};

// Skeleton Loader
const ProductCardSkeleton = ({ viewMode }) => {
    if (viewMode === "list") {
        return (
            <div className="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 animate-pulse">
                <div className="flex gap-6">
                    <div className="w-32 h-32 bg-slate-200 rounded-xl"></div>
                    <div className="flex-1 space-y-3">
                        <div className="h-6 bg-slate-200 rounded w-3/4"></div>
                        <div className="h-4 bg-slate-200 rounded w-full"></div>
                        <div className="h-4 bg-slate-200 rounded w-2/3"></div>
                        <div className="flex gap-3 mt-4">
                            <div className="h-10 bg-slate-200 rounded w-32"></div>
                            <div className="h-10 bg-slate-200 rounded w-32"></div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden shadow-sm border border-slate-200/60 animate-pulse">
            <div className="aspect-square bg-slate-200"></div>
            <div className="p-6 space-y-3">
                <div className="h-6 bg-slate-200 rounded"></div>
                <div className="h-4 bg-slate-200 rounded w-3/4"></div>
                <div className="h-6 bg-slate-200 rounded w-1/2"></div>
                <div className="h-10 bg-slate-200 rounded"></div>
            </div>
        </div>
    );
};

export default Products;
