// frontend/src/pages/Categories.jsx
import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { categoryService } from "../services/categoryService";

const Categories = () => {
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchCategories();
    }, []);

    const fetchCategories = async () => {
        try {
            setLoading(true);
            const response = await categoryService.getCategories();
            if (response.success) {
                setCategories(response.data);
            }
        } catch (error) {
            console.error("Failed to fetch categories:", error);
        } finally {
            setLoading(false);
        }
    };

    // Function to get image URL from category data
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

    const renderCategoryCard = (category, level = 0) => {
        const productCount =
            category.products_count || category.products?.length || 0;
        const imageUrl = getImageUrl(category);

        return (
            <div key={category.id} className={`${level > 0 ? "ml-4" : ""}`}>
                <Link
                    to={`/categories/${category.slug}`}
                    className="block group mb-4"
                >
                    <div
                        className={`bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition duration-200 border border-gray-100 ${
                            level === 0 ? "hover:border-blue-300" : ""
                        }`}
                    >
                        <div className="h-32 bg-gray-100">
                            {imageUrl ? (
                                <img
                                    src={imageUrl}
                                    alt={category.name}
                                    className="w-full h-full object-cover group-hover:scale-105 transition duration-200"
                                    onError={(e) => {
                                        console.error(
                                            "Image failed to load:",
                                            imageUrl
                                        );
                                        e.target.style.display = "none";
                                        // Show fallback when image fails to load
                                        e.target.nextSibling?.classList.remove(
                                            "hidden"
                                        );
                                    }}
                                />
                            ) : null}

                            {/* Fallback when no image or image fails to load */}
                            <div
                                className={`w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 ${
                                    imageUrl ? "hidden" : ""
                                }`}
                            >
                                <div className="text-center text-gray-400">
                                    <svg
                                        className="w-6 h-6 mx-auto mb-1"
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
                                    <span className="text-xs">No Image</span>
                                </div>
                            </div>
                        </div>

                        <div className="p-3">
                            <h3
                                className={`font-medium text-gray-900 group-hover:text-blue-600 transition ${
                                    level === 0 ? "text-base" : "text-sm"
                                }`}
                            >
                                {category.name}
                            </h3>

                            <div className="flex justify-between items-center mt-2">
                                {/* <span className="text-xs text-gray-500">
                                    {productCount} product
                                    {productCount !== 1 ? "s" : ""}
                                </span> */}

                                {/* {category.children &&
                                    category.children.length > 0 && (
                                        <span className="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded">
                                            {category.children.length} sub
                                        </span>
                                    )} */}
                            </div>
                        </div>
                    </div>
                </Link>

                {/* Render subcategories in a grid */}
                {category.children &&
                    category.children.length > 0 &&
                    level === 0 && (
                        <div className="grid grid-cols-3 md:grid-cols-3 gap-3 mt-3">
                            {category.children.map((child) =>
                                renderCategoryCard(child, level + 1)
                            )}
                        </div>
                    )}
            </div>
        );
    };

    if (loading) {
        return (
            <div className="max-w-6xl mx-auto px-4 py-8">
                <div className="animate-pulse">
                    <div className="h-6 bg-gray-200 rounded w-48 mb-6"></div>
                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        {[...Array(8)].map((_, i) => (
                            <div
                                key={i}
                                className="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100"
                            >
                                <div className="h-32 bg-gray-200"></div>
                                <div className="p-3 space-y-2">
                                    <div className="h-4 bg-gray-200 rounded"></div>
                                    <div className="h-3 bg-gray-200 rounded w-16"></div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="max-w-6xl mx-auto px-4 py-8">
            {/* Header */}
            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-900 mb-2">
                    All Categories sss
                </h1>
                <p className="text-gray-600 text-sm">
                    Browse our complete collection of product categories
                </p>
            </div>

            {/* Categories Grid */}
            <div className="space-y-6">
                {categories.map((category) => renderCategoryCard(category))}
            </div>

            {/* Empty State */}
            {categories.length === 0 && (
                <div className="text-center py-12 bg-white rounded-lg border border-gray-200">
                    <div className="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
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
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">
                        No Categories Found
                    </h3>
                    <p className="text-gray-600 text-sm mb-4">
                        There are no categories available at the moment.
                    </p>
                    <Link
                        to="/products"
                        className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium"
                    >
                        Browse All Products
                    </Link>
                </div>
            )}
        </div>
    );
};

export default Categories;
