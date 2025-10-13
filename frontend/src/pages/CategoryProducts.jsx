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

const CategoryProducts = () => {
    const { slug } = useParams();
    const [searchParams, setSearchParams] = useSearchParams();
    const navigate = useNavigate();
    const [products, setProducts] = useState([]);
    const [category, setCategory] = useState(null);
    const [loading, setLoading] = useState(true);
    const [categoryLoading, setCategoryLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState(
        searchParams.get("search") || ""
    );
    const [sortBy, setSortBy] = useState("created_at");
    const [sortOrder, setSortOrder] = useState("desc");

    const { addToCart } = useCart();

    // Fetch category data
    useEffect(() => {
        const fetchCategory = async () => {
            try {
                setCategoryLoading(true);
                const response = await categoryService.getCategoryBySlug(slug);
                if (response.success) {
                    setCategory(response.data);
                } else {
                    // Category not found, redirect to categories
                    navigate("/categories", { replace: true });
                }
            } catch (error) {
                console.error("Failed to fetch category:", error);
                navigate("/categories", { replace: true });
            } finally {
                setCategoryLoading(false);
            }
        };

        if (slug) {
            fetchCategory();
        }
    }, [slug, navigate]);

    // Fetch products for this category
    useEffect(() => {
        const fetchProducts = async () => {
            try {
                setLoading(true);
                const params = {
                    category: slug,
                    sort: sortBy,
                    direction: sortOrder,
                };

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

        if (slug) {
            fetchProducts();
        }
    }, [slug, searchTerm, sortBy, sortOrder, setSearchParams]);

    const handleCategorySelect = (selectedCategory) => {
        if (selectedCategory) {
            navigate(`/category/${selectedCategory.slug}`);
        } else {
            navigate("/products");
        }
    };

    const handleSearch = (e) => {
        e.preventDefault();
        // The useEffect will trigger automatically
    };

    const handleAddToCart = async (sku) => {
        try {
            await addToCart(sku.id, 1);
            // You can add a toast notification here
        } catch (error) {
            alert(error.response?.data?.message || "Failed to add to cart");
        }
    };

    const handleSortChange = (e) => {
        const [field, order] = e.target.value.split("_");
        setSortBy(field);
        setSortOrder(order);
    };

    const handleClearSearch = () => {
        setSearchTerm("");
    };

    const getImageUrl = (category) => {
        const imageData = category?.main_image;
        if (!imageData) return null;

        if (imageData.url) {
            return imageData.url;
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

    if (categoryLoading) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <div className="flex flex-col items-center space-y-4">
                    <div className="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
                    <p className="text-gray-600">Loading category...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="max-w-7xl mx-auto px-4 py-8">
            <div className="flex flex-col lg:flex-row gap-8">
                {/* Main Content */}
                <div className="lg:w-3/4">
                    {/* Category Header */}
                    {category && (
                        <div className="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <div className="flex flex-col md:flex-row md:items-start gap-6">
                                {/* Category Image */}
                                <div className="flex-shrink-0">
                                    <div className="w-24 h-24 rounded-lg overflow-hidden bg-gray-100">
                                        {getImageUrl(category) ? (
                                            <img
                                                src={getImageUrl(category)}
                                                alt={category.name}
                                                className="w-full h-full object-cover"
                                            />
                                        ) : (
                                            <div className="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg
                                                    className="w-8 h-8 text-gray-400"
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
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Category Info */}
                                <div className="flex-1">
                                    <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div>
                                            <h1 className="text-3xl font-bold text-gray-900 mb-2">
                                                {category.name}
                                            </h1>
                                            {category.description && (
                                                <p className="text-gray-600 text-lg mb-4">
                                                    {category.description}
                                                </p>
                                            )}
                                            <div className="flex items-center gap-4 text-sm text-gray-500">
                                                <span>
                                                    {products.length} products
                                                </span>
                                                {category.children &&
                                                    category.children.length >
                                                        0 && (
                                                        <span>
                                                            {
                                                                category
                                                                    .children
                                                                    .length
                                                            }{" "}
                                                            subcategories
                                                        </span>
                                                    )}
                                            </div>
                                        </div>

                                        {/* Action Buttons */}
                                        <div className="flex gap-2">
                                            <Link
                                                to="/categories"
                                                className="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium"
                                            >
                                                All Categories
                                            </Link>
                                            <Link
                                                to="/products"
                                                className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium"
                                            >
                                                All Products
                                            </Link>
                                        </div>
                                    </div>

                                    {/* Subcategories */}
                                    {category.children &&
                                        category.children.length > 0 && (
                                            <div className="mt-6">
                                                <h3 className="text-sm font-semibold text-gray-900 mb-3">
                                                    Subcategories
                                                </h3>
                                                <div className="flex flex-wrap gap-2">
                                                    {category.children.map(
                                                        (child) => (
                                                            <Link
                                                                key={child.id}
                                                                to={`/category/${child.slug}`}
                                                                className="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm hover:bg-blue-100 transition"
                                                            >
                                                                {child.name}
                                                            </Link>
                                                        )
                                                    )}
                                                </div>
                                            </div>
                                        )}
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Search and Filters */}
                    <div className="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div>
                                <h2 className="text-lg font-semibold text-gray-900">
                                    Products in this category
                                </h2>
                                {searchTerm && (
                                    <p className="text-gray-600 mt-1">
                                        Searching for "{searchTerm}"
                                        <button
                                            onClick={handleClearSearch}
                                            className="ml-2 text-blue-600 hover:text-blue-700 text-sm"
                                        >
                                            Clear
                                        </button>
                                    </p>
                                )}
                            </div>

                            {/* Search and Sort */}
                            <div className="flex flex-col sm:flex-row gap-4">
                                {/* Search Form */}
                                <form
                                    onSubmit={handleSearch}
                                    className="flex-1"
                                >
                                    <div className="flex gap-2">
                                        <input
                                            type="text"
                                            placeholder="Search in this category..."
                                            value={searchTerm}
                                            onChange={(e) =>
                                                setSearchTerm(e.target.value)
                                            }
                                            className="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                        <button
                                            type="submit"
                                            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                                        >
                                            Search
                                        </button>
                                    </div>
                                </form>

                                {/* Sort Dropdown */}
                                <select
                                    value={`${sortBy}_${sortOrder}`}
                                    onChange={handleSortChange}
                                    className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="created_at_desc">
                                        Newest First
                                    </option>
                                    <option value="created_at_asc">
                                        Oldest First
                                    </option>
                                    <option value="name_asc">Name A-Z</option>
                                    <option value="name_desc">Name Z-A</option>
                                    <option value="price_asc">
                                        Price: Low to High
                                    </option>
                                    <option value="price_desc">
                                        Price: High to Low
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {/* Products Grid */}
                    {loading ? (
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {[...Array(6)].map((_, i) => (
                                <div
                                    key={i}
                                    className="bg-white rounded-lg shadow-md overflow-hidden animate-pulse"
                                >
                                    <div className="h-48 bg-gray-200"></div>
                                    <div className="p-4 space-y-3">
                                        <div className="h-4 bg-gray-200 rounded"></div>
                                        <div className="h-3 bg-gray-200 rounded"></div>
                                        <div className="h-3 bg-gray-200 rounded w-2/3"></div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <>
                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                {products.map((product) => (
                                    <ProductCard
                                        key={product.id}
                                        product={product}
                                        onAddToCart={handleAddToCart}
                                    />
                                ))}
                            </div>

                            {products.length === 0 && (
                                <div className="text-center py-12 bg-white rounded-lg shadow-sm">
                                    <div className="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
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
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                            />
                                        </svg>
                                    </div>
                                    <h3 className="text-xl font-semibold text-gray-900 mb-2">
                                        No products found
                                    </h3>
                                    <p className="text-gray-600 mb-4">
                                        {searchTerm
                                            ? `No products found for "${searchTerm}" in ${category?.name}`
                                            : `No products available in ${category?.name} category`}
                                    </p>
                                    <div className="flex gap-3 justify-center">
                                        {searchTerm && (
                                            <button
                                                onClick={handleClearSearch}
                                                className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                                            >
                                                Clear Search
                                            </button>
                                        )}
                                        <Link
                                            to="/products"
                                            className="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition"
                                        >
                                            Browse All Products
                                        </Link>
                                    </div>
                                </div>
                            )}
                        </>
                    )}
                </div>
            </div>
        </div>
    );
};

const ProductCard = ({ product, onAddToCart }) => {
    const [selectedSku, setSelectedSku] = useState(null);
    const [mainImage, setMainImage] = useState(product.main_image?.url || null);

    // Initialize selected SKU and image
    useEffect(() => {
        if (product.skus && product.skus.length > 0) {
            const firstSku = product.skus[0];
            setSelectedSku(firstSku);

            // Use SKU image if available, otherwise use product image
            if (firstSku.images && firstSku.images.length > 0) {
                setMainImage(firstSku.images[0].url);
            } else {
                setMainImage(product.main_image?.url || null);
            }
        }
    }, [product]);

    const handleSkuClick = (sku) => {
        setSelectedSku(sku);

        // Update image to show the selected SKU's image
        if (sku.images && sku.images.length > 0) {
            setMainImage(sku.images[0].url);
        } else {
            setMainImage(product.main_image?.url || null);
        }
    };

    // Function to get inventory status
    const getInventoryStatus = (inventory) => {
        if (inventory === 0) {
            return {
                text: "Out of Stock",
                class: "bg-red-500 text-white",
                dot: "bg-red-500",
            };
        } else if (inventory < 5) {
            return {
                text: `Only ${inventory} Left`,
                class: "bg-amber-500 text-white",
                dot: "bg-amber-500",
            };
        } else {
            return {
                text: "In Stock",
                class: "bg-green-500 text-white",
                dot: "bg-green-500",
            };
        }
    };

    return (
        <div className="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition duration-200 border border-gray-100">
            {/* Product Image Section */}
            <div className="h-48 bg-gray-100 relative">
                {mainImage ? (
                    <img
                        src={mainImage}
                        alt={product.name}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                        <span className="text-gray-400 text-sm">No Image</span>
                    </div>
                )}

                {/* Inventory Status Badge - Shows for selected SKU */}
                {selectedSku && (
                    <div className="absolute top-3 right-3">
                        <span
                            className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                getInventoryStatus(selectedSku.inventory).class
                            }`}
                        >
                            {getInventoryStatus(selectedSku.inventory).text}
                        </span>
                    </div>
                )}
            </div>

            {/* Product Info */}
            <div className="p-4">
                {/* Product Name */}
                <Link to={`/products/${product.slug}`}>
                    <h3 className="font-semibold text-gray-900 hover:text-blue-600 transition text-base mb-2 line-clamp-2">
                        {product.name}
                    </h3>
                </Link>

                {/* Product Description */}
                <p className="text-gray-600 text-sm mb-3 line-clamp-2">
                    {product.description}
                </p>

                {/* Selected SKU Display */}
                {selectedSku && (
                    <div className="mb-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div className="flex justify-between items-center">
                            <div>
                                <div className="text-xs text-gray-600 mb-1">
                                    Selected Variant
                                </div>
                                <div className="flex items-center space-x-2">
                                    <span className="text-sm font-medium text-gray-900">
                                        {selectedSku.attributes?.color ||
                                            selectedSku.attributes?.size ||
                                            selectedSku.attributes?.material ||
                                            "Standard"}
                                    </span>
                                    <span className="text-lg font-bold text-green-600">
                                        ${selectedSku.price}
                                    </span>
                                </div>
                            </div>
                            <div className="text-right">
                                <div
                                    className={`px-2 py-1 rounded text-xs font-medium ${
                                        getInventoryStatus(
                                            selectedSku.inventory
                                        ).class
                                    }`}
                                >
                                    {selectedSku.inventory} in stock
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* SKU Variants */}
                <div className="space-y-2 mb-3">
                    <div className="text-xs font-medium text-gray-700 mb-2">
                        Available Variants:
                    </div>
                    {product.skus?.slice(0, 4).map((sku) => {
                        const isSelected = selectedSku?.id === sku.id;
                        const status = getInventoryStatus(sku.inventory);

                        return (
                            <div
                                key={sku.id}
                                className={`flex items-center justify-between p-2 rounded-lg border transition-all cursor-pointer ${
                                    isSelected
                                        ? "bg-blue-100 border-blue-300 ring-2 ring-blue-200"
                                        : "bg-gray-50 border-gray-200 hover:bg-gray-100"
                                }`}
                                onClick={() => handleSkuClick(sku)}
                            >
                                <div className="flex items-center space-x-3 flex-1">
                                    {/* SKU Image Thumbnail */}
                                    <div className="flex-shrink-0">
                                        {sku.images && sku.images.length > 0 ? (
                                            <div className="w-10 h-10 rounded border border-gray-300 overflow-hidden">
                                                <img
                                                    src={sku.images[0].url}
                                                    alt={
                                                        sku.attributes?.color ||
                                                        "SKU"
                                                    }
                                                    className="w-full h-full object-cover"
                                                />
                                            </div>
                                        ) : (
                                            <div className="w-10 h-10 rounded border border-gray-300 bg-gray-200 flex items-center justify-center">
                                                <span className="text-gray-400 text-xs">
                                                    IMG
                                                </span>
                                            </div>
                                        )}
                                    </div>

                                    {/* SKU Info */}
                                    <div className="flex-1">
                                        <div className="flex justify-between items-center">
                                            <div>
                                                <div className="text-sm font-medium text-gray-900">
                                                    {sku.attributes?.color ||
                                                        sku.attributes?.size ||
                                                        sku.attributes
                                                            ?.material ||
                                                        "Variant"}
                                                </div>
                                                <div className="text-green-600 font-semibold">
                                                    ${sku.price}
                                                </div>
                                            </div>
                                            <div
                                                className={`w-3 h-3 rounded-full ${status.dot}`}
                                                title={status.text}
                                            />
                                        </div>
                                    </div>
                                </div>

                                {/* Add to Cart Button */}
                                <button
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        onAddToCart(sku);
                                    }}
                                    disabled={sku.inventory === 0}
                                    className={`ml-2 px-3 py-2 text-sm rounded-lg font-medium transition ${
                                        sku.inventory === 0
                                            ? "bg-gray-200 text-gray-500 cursor-not-allowed"
                                            : "bg-blue-600 text-white hover:bg-blue-700"
                                    }`}
                                >
                                    {sku.inventory === 0 ? "Out" : "Add"}
                                </button>
                            </div>
                        );
                    })}
                </div>

                {/* View More Variants Link */}
                {product.skus?.length > 4 && (
                    <Link
                        to={`/products/${product.slug}`}
                        className="block text-center text-blue-600 text-sm hover:underline py-2 border-t"
                    >
                        View all {product.skus.length} variants
                    </Link>
                )}
            </div>
        </div>
    );
};
export default CategoryProducts;
