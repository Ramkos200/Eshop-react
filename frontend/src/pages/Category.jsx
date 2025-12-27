// frontend/src/pages/Category.jsx
import React, { useState, useEffect } from "react";
import { useParams, Link } from "react-router-dom";
import { categoryService } from "../services/categoryService";
import CategorySidebar from "../components/category/CategorySidebar";

const Category = () => {
  const { slug } = useParams();
  const [category, setCategory] = useState(null);
  const [products, setProducts] = useState([]);
  const [subcategories, setSubcategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Function to determine if a category is third level (grandchild)
  const isThirdLevelCategory = (categoryData) => {
    if (!categoryData) return false;

    // Method 1: Check if category has parent and grandparent
    if (categoryData.parent && categoryData.parent.parent_id) {
      return true;
    }

    // Method 2: Check depth level (if your API provides it)
    if (categoryData.level >= 3) {
      return true;
    }

    // Method 3: Check if category has no children (leaf node)
    if (!categoryData.children || categoryData.children.length === 0) {
      return true;
    }

    // Method 4: Check parent chain depth
    let depth = 0;
    let current = categoryData;
    while (current && current.parent) {
      depth++;
      current = current.parent;
    }
    if (depth >= 2) {
      return true;
    }

    return false;
  };

  useEffect(() => {
    if (slug) {
      fetchCategoryData();
    }
  }, [slug]);

  const fetchCategoryData = async () => {
    try {
      setLoading(true);
      setError(null);

      // Fetch category details
      const categoryResponse = await categoryService.getCategory(slug);

      if (categoryResponse.success) {
        const categoryData = categoryResponse.data;
        setCategory(categoryData);

        // Check if this is a third level category (grandchild)
        const isThirdLevel = isThirdLevelCategory(categoryData);

        if (isThirdLevel) {
          // For third level categories, show products
          const productsResponse =
            await categoryService.getCategoryProducts(slug);
          if (productsResponse.success) {
            setProducts(productsResponse.data.products || []);
            setSubcategories([]);
          }
        } else {
          // For first and second level categories, show subcategories
          setSubcategories(categoryData.children || []);
          setProducts([]);
        }
      } else {
        setError("Category not found");
      }
    } catch (err) {
      console.error("Error fetching category data:", err);
      setError("Failed to load category. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  const getProductImageUrl = (product) => {
    const imageData = product.main_image;
    if (!imageData) return null;

    if (imageData.url) return imageData.url;
    if (typeof imageData === "string") return imageData;

    const API_BASE_URL =
      import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";
    if (imageData.path) return `${API_BASE_URL}/storage/${imageData.path}`;
    if (imageData.filename)
      return `${API_BASE_URL}/storage/images/${imageData.filename}`;
    return null;
  };

  const getImageUrl = (category) => {
    const imageData = category.main_image || category.img;
    if (!imageData) return null;

    if (imageData.url) return imageData.url;
    if (typeof imageData === "string") {
      const API_BASE_URL =
        import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";
      return `${API_BASE_URL}/storage/${imageData}`;
    }
    if (imageData.path) {
      const API_BASE_URL =
        import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";
      return `${API_BASE_URL}/storage/${imageData.path}`;
    }
    if (imageData.filename) {
      const API_BASE_URL =
        import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";
      return `${API_BASE_URL}/storage/images/${imageData.filename}`;
    }
    return null;
  };

  // Render subcategories grid
  const renderSubcategories = () => (
    <div className="bg-white rounded-lg shadow-sm p-6 mb-6">
      <h2 className="text-xl font-semibold text-gray-900 mb-4">
        Subcategories
      </h2>
      {subcategories.length > 0 ? (
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          {subcategories.map((child) => {
            const imageUrl = getImageUrl(child);
            return (
              <Link
                key={child.id}
                to={`/categories/${child.slug}`}
                className="block group border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-md transition overflow-hidden"
              >
                {/* Subcategory Image */}
                <div className="h-32 bg-gray-100 overflow-hidden">
                  {imageUrl ? (
                    <img
                      src={imageUrl}
                      alt={child.name}
                      className="w-full h-full object-cover object-top group-hover:scale-105 transition duration-200"
                      onError={(e) => {
                        e.target.style.display = "none";
                        e.target.nextSibling?.classList.remove(
                          "hidden"
                        );
                      }}
                    />
                  ) : null}

                  {/* Fallback when no image */}
                  <div
                    className={`w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 ${imageUrl ? "hidden" : ""
                      }`}
                  >
                    <div className="text-center text-gray-400">
                      <svg
                        className="w-8 h-8 mx-auto mb-1"
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
                      <span className="text-xs">
                        No Image
                      </span>
                    </div>
                  </div>
                </div>

                {/* Subcategory Info */}
                <div className="p-3">
                  <h3 className="font-medium text-gray-900 group-hover:text-blue-600 transition text-sm">
                    {child.name}
                  </h3>
                  {child.products_count > 0 && (
                    <p className="text-xs text-gray-500 mt-1">
                      {child.products_count} product
                      {child.products_count !== 1
                        ? "s"
                        : ""}
                    </p>
                  )}
                </div>
              </Link>
            );
          })}
        </div>
      ) : (
        <div className="text-center py-8 text-gray-500">
          <p>No subcategories available.</p>
        </div>
      )}
    </div>
  );

  // Render products grid
  const renderProducts = () => (
    <div>
      <h2 className="text-xl font-semibold text-gray-900 mb-4">
        Products in {category?.name}
      </h2>
      {products && products.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {products.map((product) => {
            const productImageUrl = getProductImageUrl(product);
            return (
              <div
                key={product.id}
                className="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition"
              >
                {/* Product Image */}
                <div className="aspect-w-16 aspect-h-9 bg-gray-100">
                  {productImageUrl ? (
                    <img
                      src={productImageUrl}
                      alt={product.name}
                      className="w-full h-48 object-cover"
                      onError={(e) => {
                        e.target.style.display = "none";
                        e.target.nextSibling?.classList.remove(
                          "hidden"
                        );
                      }}
                    />
                  ) : null}

                  {/* Fallback when no image */}
                  <div
                    className={`w-full h-48 flex items-center justify-center bg-gray-100 ${productImageUrl ? "hidden" : ""
                      }`}
                  >
                    <span className="text-gray-400">
                      No Image
                    </span>
                  </div>
                </div>

                {/* Product Info */}
                <div className="p-4">
                  <h3 className="font-semibold text-lg mb-2 line-clamp-2">
                    {product.name}
                  </h3>
                  <p className="text-gray-600 text-sm mb-3 line-clamp-2">
                    {product.description}
                  </p>
                  <div className="flex items-center justify-between">
                    <span className="text-2xl font-bold text-blue-600">
                      {product.price_range}


                    </span>
                    <span
                      className={`px-2 py-1 text-xs rounded-full ${product.status === "Published"
                        ? "bg-green-100 text-green-800"
                        : "bg-gray-100 text-gray-800"
                        }`}
                    >
                      {product.status}
                    </span>
                  </div>

                  {/* Action Buttons */}
                  <div className="mt-4 flex space-x-2">
                    <Link
                      to={`/products/${product.slug}`}
                      className="flex-1 bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition"
                    >
                      View Details
                    </Link>
                    <button className="px-4 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-50 transition">
                      Add to Cart
                    </button>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      ) : (
        <div className="bg-white rounded-lg shadow-sm p-8 text-center">
          <h3 className="text-lg font-semibold text-gray-900 mb-2">
            No Products Found
          </h3>
          <p className="text-gray-600 mb-4">
            There are no products available in this category yet.
          </p>
          <Link
            to="/products"
            className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Browse All Products
          </Link>
        </div>
      )}
    </div>
  );

  // Determine what to show based on category level
  const renderContent = () => {
    if (!category) return null;

    const isThirdLevel = isThirdLevelCategory(category);

    if (isThirdLevel) {
      return renderProducts();
    } else {
      return renderSubcategories();
    }
  };

  if (loading) {
    return (
      <div className="container mx-auto px-4 py-8">
        <div className="flex gap-8">
          <div className="w-1/4">
            <div className="bg-white rounded-lg shadow-sm p-6">
              <div className="animate-pulse space-y-3">
                {[...Array(6)].map((_, i) => (
                  <div
                    key={i}
                    className="h-4 bg-gray-200 rounded"
                  ></div>
                ))}
              </div>
            </div>
          </div>
          <div className="w-3/4">
            <div className="animate-pulse space-y-4">
              <div className="h-8 bg-gray-200 rounded w-1/3"></div>
              <div className="h-4 bg-gray-200 rounded w-1/2"></div>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                {[...Array(6)].map((_, i) => (
                  <div
                    key={i}
                    className="bg-white rounded-lg shadow-sm border border-gray-200 p-4"
                  >
                    <div className="h-48 bg-gray-200 rounded mb-4"></div>
                    <div className="h-4 bg-gray-200 rounded mb-2"></div>
                    <div className="h-4 bg-gray-200 rounded w-1/3"></div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="container mx-auto px-4 py-8">
        <div className="text-center">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">
            Category Not Found
          </h2>
          <p className="text-gray-600 mb-6">{error}</p>
          <Link
            to="/products"
            className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Back to Products
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="flex gap-8">
        {/* Sidebar */}
        <div className="w-1/4">
          <CategorySidebar
            currentCategory={category}
            onCategorySelect={(selectedCategory) => {
            }}
          />
        </div>

        {/* Main Content */}
        <div className="w-3/4">
          {/* Breadcrumb */}
          <nav className="flex mb-6" aria-label="Breadcrumb">
            <ol className="flex items-center space-x-2 text-sm text-gray-600">
              <li>
                <Link to="/" className="hover:text-blue-600">
                  Home
                </Link>
              </li>
              <li>
                <span className="mx-2">/</span>
              </li>
              <li>
                <Link
                  to="/products"
                  className="hover:text-blue-600"
                >
                  Products
                </Link>
              </li>
              <li>
                <span className="mx-2">/</span>
              </li>
              <li className="text-gray-900 font-medium">
                {category?.name}
              </li>
            </ol>
          </nav>

          {/* Category Header */}
          <div className="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div className="relative h-64 md:h-80 lg:h-96 bg-gray-100">
              {category ? (
                <>
                  {getImageUrl(category) ? (
                    <img
                      src={getImageUrl(category)}
                      alt={category.name}
                      className="w-full h-full object-cover"
                      onError={(e) => {
                        e.target.style.display = "none";
                      }}
                    />
                  ) : (
                    <div className="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                      <div className="text-center text-white">
                        <svg
                          className="w-16 h-16 mx-auto mb-4 opacity-80"
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
                        <span className="text-lg font-medium">
                          No Category Image
                        </span>
                      </div>
                    </div>
                  )}
                  <div className="absolute inset-0 bg-black/20"></div>
                </>
              ) : (
                <div className="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                  <span className="text-white text-lg">
                    Loading...
                  </span>
                </div>
              )}
            </div>

            <div className="relative z-10 p-6 bg-white">
              <h1 className="text-3xl font-bold text-gray-900 mb-3">
                {category?.name}
              </h1>

              {category?.description && (
                <p className="text-gray-600 text-lg mb-4 max-w-3xl">
                  {category.description}
                </p>
              )}

              {/* Category Info */}
              <div className="flex items-center space-x-6 text-sm text-gray-500">
                <span className="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium">
                  {isThirdLevelCategory(category)
                    ? `${products.length} product${products.length !== 1 ? "s" : ""
                    } available`
                    : `${subcategories.length} subcategor${subcategories.length !== 1
                      ? "ies"
                      : "y"
                    } available`}
                </span>

                {category?.parent && (
                  <span className="flex items-center space-x-2">
                    <span className="text-gray-500">
                      Parent category:
                    </span>
                    <Link
                      to={`/categories/${category.parent.slug}`}
                      className="text-blue-600 hover:text-blue-700 font-medium"
                    >
                      {category.parent.name}
                    </Link>
                  </span>
                )}
              </div>
            </div>
          </div>

          {/* Dynamic Content - Subcategories or Products */}
          {renderContent()}
        </div>
      </div>
    </div>
  );
};

export default Category;
