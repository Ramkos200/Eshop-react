import React, { useState, useEffect } from "react";
import { Link, useParams } from "react-router-dom";
import { categoryService } from "../../services/categoryService";

const CategorySidebar = ({ onCategorySelect, currentCategory }) => {
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);
    const [expandedCategories, setExpandedCategories] = useState(new Set());
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const { slug } = useParams();

    useEffect(() => {
        fetchCategories();
    }, []);

    // Auto-expand parent categories when a category is selected
    useEffect(() => {
        if (currentCategory && categories.length > 0) {
            expandParentCategories(currentCategory);
        }
    }, [currentCategory, categories]);

    // Close mobile menu when category is selected on mobile
    useEffect(() => {
        if (currentCategory && window.innerWidth < 768) {
            setIsMobileMenuOpen(false);
        }
    }, [currentCategory]);

    const fetchCategories = async () => {
        try {
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

    // Function to expand all parent categories of the current category
    const expandParentCategories = (category) => {
        const newExpanded = new Set(expandedCategories);
        let current = category;

        // Traverse up the parent chain and expand all parents
        while (current && current.parent_id) {
            // Find the parent in the categories tree
            const parent = findCategoryById(categories, current.parent_id);
            if (parent) {
                newExpanded.add(parent.id);
                current = parent;
            } else {
                break;
            }
        }

        setExpandedCategories(newExpanded);
    };

    // Helper function to find a category by ID in the tree
    const findCategoryById = (categoryList, id) => {
        for (const category of categoryList) {
            if (category.id === id) return category;
            if (category.children) {
                const found = findCategoryById(category.children, id);
                if (found) return found;
            }
        }
        return null;
    };

    const toggleCategory = (categoryId, event) => {
        event.preventDefault();
        event.stopPropagation();

        const newExpanded = new Set(expandedCategories);
        if (newExpanded.has(categoryId)) {
            newExpanded.delete(categoryId);
        } else {
            newExpanded.add(categoryId);
        }
        setExpandedCategories(newExpanded);
    };

    const toggleMobileMenu = () => {
        setIsMobileMenuOpen(!isMobileMenuOpen);
    };

    const handleCategorySelect = (category) => {
        if (onCategorySelect) {
            onCategorySelect(category);
        }
        // Close mobile menu when category is selected on mobile
        if (window.innerWidth < 768) {
            setIsMobileMenuOpen(false);
        }
    };

    const renderCategoryTree = (categoryList, level = 0) => {
        return categoryList.map((category) => {
            const isExpanded = expandedCategories.has(category.id);
            const hasChildren =
                category.children && category.children.length > 0;

            return (
                <div key={category.id} className={`${level > 0 ? "ml-2" : ""}`}>
                    <div className="flex items-center">
                        {/* Expand/Collapse Button */}
                        {hasChildren && (
                            <button
                                onClick={(e) => toggleCategory(category.id, e)}
                                className="w-4 h-4 flex items-center justify-center text-gray-500 hover:text-gray-700 transition mr-1 flex-shrink-0"
                            >
                                <svg
                                    className={`w-3 h-3 transform transition-transform ${
                                        isExpanded ? "rotate-90" : "rotate-0"
                                    }`}
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </button>
                        )}

                        {/* Spacer for categories without children */}
                        {!hasChildren && (
                            <div className="w-4 h-4 mr-1 flex-shrink-0"></div>
                        )}

                        {/* Category Link */}
                        <Link
                            to={`/categories/${category.slug}`}
                            onClick={() => handleCategorySelect(category)}
                            className={`flex-1 py-2 px-2 rounded-lg transition text-sm truncate ${
                                currentCategory?.id === category.id
                                    ? "bg-blue-100 text-blue-700 font-semibold"
                                    : "text-gray-700 hover:bg-gray-100"
                            } ${level > 0 ? "text-sm" : "font-medium"}`}
                            title={category.name}
                        >
                            <span className="flex items-center justify-between">
                                <span className="truncate">
                                    {category.name}
                                </span>
                                {category.products_count && (
                                    <span className="text-gray-500 text-xs ml-2 bg-gray-100 px-1.5 py-0.5 rounded flex-shrink-0">
                                        {category.products_count}
                                    </span>
                                )}
                            </span>
                        </Link>
                    </div>

                    {/* Render children if expanded */}
                    {hasChildren && isExpanded && (
                        <div className="mt-1 border-l border-gray-200 ml-2 pl-2">
                            {renderCategoryTree(category.children, level + 1)}
                        </div>
                    )}
                </div>
            );
        });
    };

    const renderSidebarContent = () => (
        <div className="max-h-[calc(100vh-120px)] overflow-y-auto">
            {/* Header */}
            <div className="pb-4 border-b border-gray-200 mb-4">
                <h3 className="text-lg font-semibold text-gray-900">
                    Categories
                </h3>
            </div>

            <div className="space-y-1">
                {/* All Categories Link */}
                <Link
                    to="/products"
                    onClick={() => handleCategorySelect(null)}
                    className={`block py-2 px-3 rounded-lg transition text-sm ${
                        !currentCategory
                            ? "bg-blue-100 text-blue-700 font-semibold"
                            : "text-gray-700 hover:bg-gray-100"
                    }`}
                >
                    All Categories
                </Link>

                {/* Category Tree */}
                <div className="space-y-1">
                    {renderCategoryTree(categories)}
                </div>
            </div>

            {/* Price Filter */}
            {/* <div className="mt-6 pt-4 border-t border-gray-200">
                <h4 className="font-semibold text-gray-900 mb-3 text-sm">
                    Price Range
                </h4>
                <div className="space-y-2">
                    {[
                        { label: "Under $25", value: "0-25" },
                        { label: "$25 - $50", value: "25-50" },
                        { label: "$50 - $100", value: "50-100" },
                        { label: "Over $100", value: "100-" },
                    ].map((range) => (
                        <label
                            key={range.value}
                            className="flex items-center space-x-2 cursor-pointer"
                        >
                            <input
                                type="radio"
                                name="price-range"
                                value={range.value}
                                className="text-blue-600 focus:ring-blue-500 h-3 w-3"
                            />
                            <span className="text-sm text-gray-700">
                                {range.label}
                            </span>
                        </label>
                    ))}
                </div>
            </div> */}

            {/* Clear Filters */}
            {/* <div className="mt-4 pt-4 border-t border-gray-200">
                <button
                    onClick={() => {
                        handleCategorySelect(null);
                        setExpandedCategories(new Set());
                    }}
                    className="w-full text-center text-sm text-blue-600 hover:text-blue-700 font-medium py-2"
                >
                    Clear All Filters
                </button>
            </div> */}

            {/* Scrollbar Styling */}
            <style jsx="true">{`
                .max-h-\[calc\(100vh-120px\)\]::-webkit-scrollbar {
                    width: 4px;
                }
                .max-h-\[calc\(100vh-120px\)\]::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 4px;
                }
                .max-h-\[calc\(100vh-120px\)\]::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 4px;
                }
                .max-h-\[calc\(100vh-120px\)\]::-webkit-scrollbar-thumb:hover {
                    background: #a8a8a8;
                }
            `}</style>
        </div>
    );

    if (loading) {
        return (
            <div className="bg-white rounded-lg shadow-sm p-4">
                <div className="animate-pulse space-y-3">
                    {[...Array(6)].map((_, i) => (
                        <div key={i} className="h-4 bg-gray-200 rounded"></div>
                    ))}
                </div>
            </div>
        );
    }

    return (
        <>
            {/* Desktop Sidebar - Only visible on desktop */}
            <div className="hidden md:block bg-white rounded-lg shadow-sm p-4 sticky top-6">
                {renderSidebarContent()}
            </div>

            {/* Mobile Section - Only visible on mobile */}
            <div className="md:hidden">
                {/* Mobile Hamburger Button */}
                <div className="mb-4">
                    <button
                        onClick={toggleMobileMenu}
                        className="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
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
                        <span>Categories</span>
                    </button>
                </div>

                {/* Mobile Overlay */}
                {isMobileMenuOpen && (
                    <div
                        className="fixed inset-0 bg-black bg-opacity-50 z-40"
                        onClick={() => setIsMobileMenuOpen(false)}
                    />
                )}

                {/* Mobile Drawer - Only visible on mobile */}
                <div
                    className={`
                fixed top-0 left-0 h-full w-80 bg-white shadow-xl z-50 transform transition-transform duration-300 ease-in-out
                ${isMobileMenuOpen ? "translate-x-0" : "-translate-x-full"}
            `}
                >
                    {/* Mobile Header */}
                    <div className="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 className="text-lg font-semibold text-gray-900">
                            Categories & Filters
                        </h3>
                        <button
                            onClick={toggleMobileMenu}
                            className="p-2 text-gray-500 hover:text-gray-700 transition"
                        >
                            <svg
                                className="w-6 h-6"
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

                    {/* Sidebar Content */}
                    <div className="p-4 h-[calc(100vh-80px)] overflow-y-auto">
                        {renderSidebarContent()}
                    </div>
                </div>
            </div>
        </>
    );
};

export default CategorySidebar;
