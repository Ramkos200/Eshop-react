// frontend/src/pages/Products.jsx
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

const Products = () => {
 const { slug } = useParams();
 const [searchParams, setSearchParams] = useSearchParams();
 const navigate = useNavigate();
 const [products, setProducts] = useState([]);
 const [allCategories, setAllCategories] = useState([]);
 const [loading, setLoading] = useState(true);
 const [selectedCategories, setSelectedCategories] = useState([]);
 const [priceRange, setPriceRange] = useState({ min: "", max: "" });
 const [searchTerm, setSearchTerm] = useState(
  searchParams.get("search") || ""
 );
 const [sortBy, setSortBy] = useState("created_at");
 const [sortOrder, setSortOrder] = useState("desc");
 const [selectedSort, setSelectedSort] = useState("created_at_desc");
 const [viewMode, setViewMode] = useState("grid");
 const [pagination, setPagination] = useState({
  current_page: 1,
  total_pages: 1,
  per_page: 25, // Increased to 25 for 5x5 grid
  total: 0,
 });

 const { addToCart } = useCart();

 // Fetch all categories for the filter sidebar
 useEffect(() => {
  fetchAllCategories();
 }, []);

 // Fetch products when filters change
 useEffect(() => {
  fetchProducts();
 }, [
  selectedCategories,
  priceRange,
  searchTerm,
  sortBy,
  sortOrder,
  pagination.current_page,
 ]);

 const fetchAllCategories = async () => {
  try {
   const response = await categoryService.getCategories();
   if (response.success) {
    setAllCategories(response.data);
   }
  } catch (error) {
   console.error("Failed to fetch categories:", error);
  }
 };

 const fetchProducts = async () => {
  try {
   setLoading(true);
   const params = {
    sort: sortBy,
    direction: sortOrder,
    page: pagination.current_page,
    per_page: pagination.per_page,
   };

   if (selectedCategories.length > 0) {
    params.categories = selectedCategories.join(",");
   }

   if (priceRange.min) {
    params.min_price = priceRange.min;
   }

   if (priceRange.max) {
    params.max_price = priceRange.max;
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
    setPagination((prev) => ({
     ...prev,
     current_page: response.pagination.current_page,
     total_pages: response.pagination.total_pages,
     total: response.pagination.total,
    }));
   }
  } catch (error) {
   console.error("Failed to fetch products:", error);
  } finally {
   setLoading(false);
  }
 };

 const handlePriceRangeChange = (min, max) => {
  setPriceRange({ min, max });
  setPagination((prev) => ({ ...prev, current_page: 1 }));
 };

 const handleClearFilters = () => {
  setSelectedCategories([]);
  setPriceRange({ min: "", max: "" });
  setSearchTerm("");
  setPagination((prev) => ({ ...prev, current_page: 1 }));
 };

 const handleSearch = (e) => {
  e.preventDefault();
  setPagination((prev) => ({ ...prev, current_page: 1 }));
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
  const value = e.target.value;
  setSelectedSort(value);

  const sortMap = {
   created_at_desc: { field: "created_at", order: "desc" },
   created_at_asc: { field: "created_at", order: "asc" },
   name_asc: { field: "name", order: "asc" },
   name_desc: { field: "name", order: "desc" },
   price_asc: { field: "price", order: "asc" },
   price_desc: { field: "price", order: "desc" },
  };

  const sortConfig = sortMap[value];
  if (sortConfig) {
   setSortBy(sortConfig.field);
   setSortOrder(sortConfig.order);
   setPagination((prev) => ({ ...prev, current_page: 1 }));
  }
 };

 const handlePageChange = (page) => {
  setPagination((prev) => ({ ...prev, current_page: page }));
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

 // Get the lowest price from product SKUs
 const getLowestPrice = (product) => {
  if (!product.skus || product.skus.length === 0) {
   // If no SKUs, use product price or default to 0
   const basePrice = parseFloat(product.price) || 0;
   return basePrice;
  }

  try {
   // Extract all SKU prices and convert to numbers
   const prices = product.skus
    .map((sku) => {
     const price = parseFloat(sku.price);
     return isNaN(price) ? Infinity : price; // Use Infinity for invalid prices
    })
    .filter((price) => price !== Infinity); // Remove invalid prices

   // If no valid prices found, return 0
   if (prices.length === 0) {
    return 0;
   }

   return Math.min(...prices);
  } catch (error) {
   console.error(
    "Error calculating lowest price for product:",
    product.id,
    error
   );
   return 0;
  }
 };

 // Recursive function to render category tree with checkboxes
 const getAllCategoryIdsInHierarchy = (categoryId) => {
  const category = findCategoryById(allCategories, categoryId);
  if (!category) return [categoryId];

  let allIds = [categoryId];

  // Recursively get all children IDs
  const getChildrenIds = (cat) => {
   if (cat.children && cat.children.length > 0) {
    cat.children.forEach((child) => {
     allIds.push(child.id);
     getChildrenIds(child);
    });
   }
  };

  getChildrenIds(category);
  return [...new Set(allIds)]; // Remove duplicates
 };
 const getAllParentCategoryIds = (categoryId) => {
  const category = findCategoryById(allCategories, categoryId);
  if (!category) return [categoryId];

  let parentIds = [categoryId];
  let current = category;

  // Traverse up the parent chain
  while (current && current.parent) {
   parentIds.push(current.parent.id);
   current = current.parent;
  }

  return [...new Set(parentIds)]; // Remove duplicates
 };
 const findCategoryById = (categories, id) => {
  for (const category of categories) {
   if (category.id === id) return category;
   if (category.children) {
    const found = findCategoryById(category.children, id);
    if (found) return found;
   }
  }
  return null;
 };

 const handleCategoryToggle = (categoryId) => {
  setSelectedCategories((prev) => {
   const category = findCategoryById(allCategories, categoryId);
   if (!category) return prev;

   // Check if category is currently selected
   const isCurrentlySelected = prev.includes(categoryId);

   if (isCurrentlySelected) {
    // Deselecting: Remove this category and all its children
    const childrenIds = getAllCategoryIdsInHierarchy(categoryId);
    return prev.filter((id) => !childrenIds.includes(id));
   } else {
    // Selecting: Add this category, all its parents, and all its children
    const parentIds = getAllParentCategoryIds(categoryId);
    const childrenIds = getAllCategoryIdsInHierarchy(categoryId);
    const allIdsToAdd = [...parentIds, ...childrenIds];

    const newSelection = [...prev];
    allIdsToAdd.forEach((id) => {
     if (!newSelection.includes(id)) {
      newSelection.push(id);
     }
    });

    return newSelection;
   }
  });
  setPagination((prev) => ({ ...prev, current_page: 1 }));
 };
 const isCategoryPartiallySelected = (categoryId) => {
  const category = findCategoryById(allCategories, categoryId);
  if (!category || !category.children || category.children.length === 0) {
   return false;
  }

  const childrenIds = getAllCategoryIdsInHierarchy(categoryId).filter(
   (id) => id !== categoryId
  );
  const selectedChildrenCount = childrenIds.filter((id) =>
   selectedCategories.includes(id)
  ).length;
  const allChildrenCount = childrenIds.length;

  return (
   selectedChildrenCount > 0 &&
   selectedChildrenCount < allChildrenCount
  );
 };

 const renderCategoryTree = (categories, level = 0) => {
  return categories.map((category) => {
   const isSelected = selectedCategories.includes(category.id);
   const isPartial = isCategoryPartiallySelected(category.id);
   const hasChildren =
    category.children && category.children.length > 0;

   return (
    <div key={category.id} className={`${level > 0 ? "ml-4" : ""}`}>
     <label className="flex items-center space-x-2 py-1 cursor-pointer group">
      <div className="relative">
       <input
        type="checkbox"
        checked={isSelected}
        onChange={() =>
         handleCategoryToggle(category.id)
        }
        className={`text-blue-600 focus:ring-blue-500 h-4 w-4 ${isPartial ? "opacity-70" : ""
         }`}
        ref={(el) => {
         if (el) {
          el.indeterminate = isPartial;
         }
        }}
       />
       {isPartial && (
        <div className="absolute inset-0 flex items-center justify-center">
         <div className="w-2 h-0.5 bg-blue-600 rounded"></div>
        </div>
       )}
      </div>
      <span
       className={`text-sm group-hover:text-blue-600 transition-colors ${isSelected
         ? "text-blue-700 font-medium"
         : "text-gray-700"
        }`}
      >
       {category.name}
       {category.products_count > 0 && (
        <span className="text-xs text-gray-500 ml-1">
         ({category.products_count})
        </span>
       )}
      </span>
     </label>

     {/* Render children with smooth animation */}
     {hasChildren && (
      <div className="mt-1 transition-all duration-200">
       {renderCategoryTree(category.children, level + 1)}
      </div>
     )}
    </div>
   );
  });
 };

 // Product Card Component - Compact version for 5x5 grid
 const ProductCard = ({
  product,
  onAddToCart,
  getProductImageUrl,
  viewMode,
 }) => {
  const hasAvailableSkus = product.skus?.some((sku) => sku.inventory > 0);
  const productImageUrl = getProductImageUrl(product);
  const firstSku = product.skus?.[0];
  const lowestPrice = getLowestPrice(product);

  if (viewMode === "list") {
   return product.status === "Published" ? (
    <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 group">
     <div className="flex gap-6">
      {/* Product Image */}
      <Link
       to={`/products/${product.slug}`}
       className="flex-shrink-0"
      >
       <div className="w-32 h-32 rounded-lg bg-gray-100 overflow-hidden">
        {productImageUrl ? (
         <img
          src={productImageUrl}
          alt={product.name}
          className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
         />
        ) : (
         <div className="w-full h-full flex items-center justify-center">
          <span className="text-gray-400 text-sm">
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
          <h3 className="text-xl font-semibold text-gray-900 hover:text-blue-600 transition-colors line-clamp-2 mb-2">
           {product.name}
          </h3>
         </Link>
         <p className="text-gray-600 line-clamp-2 mb-4">
          {product.description}
         </p>
        </div>
        <div className="text-right">
         <div className="text-2xl font-bold text-blue-600 mb-2">
          $
          {typeof lowestPrice === "number"
           ? lowestPrice.toFixed(2)
           : "0.00"}
         </div>
         <span
          className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${product.status === "Published"
            ? "bg-green-100 text-green-800"
            : "bg-gray-100 text-gray-800"
           }`}
         >
          {product.status}
         </span>
        </div>
       </div>

       {/* Actions */}
       <div className="flex items-center justify-between">
        <div className="flex items-center gap-3">
         {firstSku && (
          <button
           onClick={() =>
            onAddToCart(firstSku)
           }
           disabled={!hasAvailableSkus}
           className={`px-6 py-2 rounded-lg font-medium transition-all ${hasAvailableSkus
             ? "bg-blue-600 text-white hover:bg-blue-700 shadow-lg hover:shadow-xl"
             : "bg-gray-200 text-gray-400 cursor-not-allowed"
            }`}
          >
           {hasAvailableSkus
            ? "Add to Cart"
            : "Out of Stock"}
          </button>
         )}
         <Link
          to={`/products/${product.slug}`}
          className="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium"
         >
          View Details
         </Link>
        </div>
        {product.skus && product.skus.length > 1 && (
         <span className="text-sm text-gray-500">
          {product.skus.length} variants available
         </span>
        )}
       </div>
      </div>
     </div>
    </div>
   ) : null;
  }

  // Grid View - Compact version
  return (
   product.status === "Published" && (
    <div className="group bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
     {/* Product Image */}
     <Link
      to={`/products/${product.slug}`}
      className="block relative"
     >
      <div className="aspect-square bg-gray-100 overflow-hidden">
       {productImageUrl ? (
        <img
         src={productImageUrl}
         alt={product.name}
         className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
       ) : (
        <div className="w-full h-full flex items-center justify-center">
         <span className="text-gray-400 text-xs">
          No Image
         </span>
        </div>
       )}
      </div>

      {/* Status Badge */}
      <div className="absolute top-2 right-2">
       {!hasAvailableSkus && (
        <span className="bg-red-500 text-white px-1.5 py-0.5 rounded-full text-xs font-medium shadow">
         Out of Stock
        </span>
       )}
      </div>
     </Link>

     {/* Product Info */}
     <div className="p-3">
      <Link to={`/products/${product.slug}`}>
       <h3 className="font-medium text-gray-900 hover:text-blue-600 transition-colors text-sm mb-1 line-clamp-2 leading-tight">
        {product.name}
       </h3>
      </Link>

      <p className="text-gray-600 text-xs mb-2 line-clamp-2 leading-relaxed">
       {product.description}
      </p>

      {/* Price and Status */}
      <div className="flex items-center justify-between mb-2">
       <div className="text-lg font-bold text-blue-600">
        $
        {typeof lowestPrice === "number"
         ? lowestPrice.toFixed(2)
         : "0.00"}
       </div>
       <span
        className={`px-1.5 py-0.5 rounded-full text-xs font-medium ${product.status === "Published"
          ? "bg-green-100 text-green-800"
          : "bg-gray-100 text-gray-800"
         }`}
       >
        {product.status}
       </span>
      </div>

      {/* Action Buttons */}
      <div className="flex gap-1.5">
       <Link
        to={`/products/${product.slug}`}
        className="flex-1 bg-blue-600 text-white text-center py-1.5 rounded-lg hover:bg-blue-700 transition-all font-medium text-xs"
       >
        View Details
       </Link>
       <button
        onClick={() =>
         firstSku && onAddToCart(firstSku)
        }
        disabled={!hasAvailableSkus}
        className={`p-1.5 rounded-lg transition-all font-medium text-xs ${hasAvailableSkus
          ? "bg-blue-600 text-white hover:bg-blue-700"
          : "bg-gray-200 text-gray-400 cursor-not-allowed"
         }`}
       >
        <svg
         className="w-4 h-4"
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

 // Skeleton Loader - Compact version
 const ProductCardSkeleton = ({ viewMode }) => {
  if (viewMode === "list") {
   return (
    <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 animate-pulse">
     <div className="flex gap-6">
      <div className="w-32 h-32 bg-gray-200 rounded-lg"></div>
      <div className="flex-1 space-y-3">
       <div className="h-6 bg-gray-200 rounded w-3/4"></div>
       <div className="h-4 bg-gray-200 rounded w-full"></div>
       <div className="h-4 bg-gray-200 rounded w-2/3"></div>
       <div className="flex gap-3 mt-4">
        <div className="h-10 bg-gray-200 rounded w-32"></div>
        <div className="h-10 bg-gray-200 rounded w-32"></div>
       </div>
      </div>
     </div>
    </div>
   );
  }

  return (
   <div className="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden animate-pulse">
    <div className="aspect-square bg-gray-200"></div>
    <div className="p-3 space-y-2">
     <div className="h-4 bg-gray-200 rounded"></div>
     <div className="h-3 bg-gray-200 rounded w-3/4"></div>
     <div className="h-5 bg-gray-200 rounded w-1/2"></div>
     <div className="h-8 bg-gray-200 rounded"></div>
    </div>
   </div>
  );
 };

 return (
  <div className="min-h-screen bg-gray-50">
   <div className="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div className="flex flex-col lg:flex-row gap-6 h-full">
     {/* Filter Sidebar - Full page height */}
     <div className="lg:w-64 lg:h-[calc(100vh-4rem)] lg:sticky lg:top-8">
      {" "}
      {/* Full height sidebar */}
      <div className="bg-white rounded-lg shadow-sm p-4 h-full overflow-y-auto">
       {" "}
       {/* Added overflow-y-auto */}
       {/* Categories Filter */}
       <div className="mb-4">
        <h3 className="text-base font-semibold text-gray-900 mb-3">
         Categories
        </h3>
        <div className="max-h-[calc(100vh-20rem)] overflow-y-auto">
         {" "}
         {/* Dynamic height based on viewport */}
         {renderCategoryTree(allCategories)}
        </div>
       </div>
       {/* Price Range Filter */}
       <div className="mb-4">
        <h3 className="text-base font-semibold text-gray-900 mb-3">
         Price Range
        </h3>
        <div className="space-y-2">
         <div className="flex gap-2">
          <input
           type="number"
           placeholder="Min"
           value={priceRange.min}
           onChange={(e) =>
            handlePriceRangeChange(
             e.target.value,
             priceRange.max
            )
           }
           className="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
          />
          <input
           type="number"
           placeholder="Max"
           value={priceRange.max}
           onChange={(e) =>
            handlePriceRangeChange(
             priceRange.min,
             e.target.value
            )
           }
           className="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
          />
         </div>
         <div className="grid grid-cols-2 gap-1.5">
          {[
           {
            label: "Under $25",
            min: 0,
            max: 25,
           },
           {
            label: "$25 - $50",
            min: 25,
            max: 50,
           },
           {
            label: "$50 - $100",
            min: 50,
            max: 100,
           },
           {
            label: "Over $100",
            min: 100,
            max: "",
           },
          ].map((range) => (
           <button
            key={range.label}
            onClick={() =>
             handlePriceRangeChange(
              range.min,
              range.max
             )
            }
            className="text-xs text-gray-700 bg-gray-100 hover:bg-gray-200 px-2 py-1.5 rounded transition"
           >
            {range.label}
           </button>
          ))}
         </div>
        </div>
       </div>
       {/* Active Filters */}
       {(selectedCategories.length > 0 ||
        priceRange.min ||
        priceRange.max) && (
         <div className="mb-4">
          <h3 className="text-base font-semibold text-gray-900 mb-2">
           Active Filters
          </h3>
          <div className="flex flex-wrap gap-1.5">
           {selectedCategories
            .map((categoryId) => {
             const category = allCategories
              .flatMap((cat) => [
               cat,
               ...(cat.children || []),
               ...(cat.children?.flatMap(
                (child) =>
                 child.children ||
                 []
               ) || []),
              ])
              .find(
               (c) =>
                c.id === categoryId
              );

             // Only show leaf categories in active filters to avoid duplication
             const isLeafCategory =
              !category?.children ||
              category.children.length ===
              0;
             const hasSelectedParent =
              category?.parent &&
              selectedCategories.includes(
               category.parent.id
              );

             if (
              category &&
              isLeafCategory &&
              !hasSelectedParent
             ) {
              return (
               <span
                key={categoryId}
                className="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs flex items-center"
               >
                {category.name}
                <button
                 onClick={() =>
                  handleCategoryToggle(
                   categoryId
                  )
                 }
                 className="ml-1 hover:text-blue-600 font-bold text-xs"
                >
                 ×
                </button>
               </span>
              );
             }
             return null;
            })
            .filter(Boolean)}

           {(priceRange.min || priceRange.max) && (
            <span className="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs flex items-center">
             ${priceRange.min || 0} - $
             {priceRange.max || "∞"}
             <button
              onClick={() =>
               handlePriceRangeChange(
                "",
                ""
               )
              }
              className="ml-1 hover:text-green-600 font-bold text-xs"
             >
              ×
             </button>
            </span>
           )}
          </div>

          {/* Show parent categories info */}
          {selectedCategories.length > 0 && (
           <p className="text-xs text-gray-500 mt-1">
            Parent categories are automatically
            included when selecting
            subcategories
           </p>
          )}
         </div>
        )}
       {/* Clear Filters - Positioned at the bottom */}
       <div className="mt-auto pt-4">
        {" "}
        {/* Pushes to bottom */}
        <button
         onClick={handleClearFilters}
         className="w-full bg-gray-200 text-gray-700 py-1.5 px-3 rounded-lg hover:bg-gray-300 transition font-medium text-sm"
        >
         Clear All Filters
        </button>
       </div>
      </div>
     </div>

     {/* Main Content - Full width */}
     <div className="flex-1">
      {/* Header */}
      <div className="bg-white rounded-lg shadow-sm p-4 mb-4">
       <h1 className="text-2xl font-bold text-gray-900 mb-1">
        All Products
       </h1>
       <p className="text-gray-600 text-sm">
        Discover our complete collection of{" "}
        {pagination.total} products
       </p>
      </div>

      {/* Controls Bar */}
      <div className="bg-white rounded-lg shadow-sm p-4 mb-4">
       <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
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
           className="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
          />
          <svg
           className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"
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

        {/* Sort and View Controls */}
        <div className="flex items-center gap-3">
         {/* View Mode Toggle */}
         <div className="flex items-center space-x-1">
          <button
           onClick={() => setViewMode("grid")}
           className={`p-1.5 rounded transition-all ${viewMode === "grid"
             ? "bg-blue-100 text-blue-600"
             : "text-gray-400 hover:text-gray-600"
            }`}
          >
           <svg
            className="w-4 h-4"
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
           className={`p-1.5 rounded transition-all ${viewMode === "list"
             ? "bg-blue-100 text-blue-600"
             : "text-gray-400 hover:text-gray-600"
            }`}
          >
           <svg
            className="w-4 h-4"
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

         {/* Sort Dropdown */}
         <select
          value={selectedSort}
          onChange={handleSortChange}
          className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
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
        </div>
       </div>
      </div>

      {/* Products Grid - 5x5 layout */}
      {loading ? (
       <div
        className={
         viewMode === "grid"
          ? "grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"
          : "space-y-3"
        }
       >
        {[...Array(25)].map((_, i) => (
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
          className={
           viewMode === "grid"
            ? "grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"
            : "space-y-3"
          }
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
             strokeWidth={1}
             d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
            />
           </svg>
          </div>
          <h3 className="text-xl font-bold text-gray-900 mb-2">
           No products found
          </h3>
          <p className="text-gray-600 mb-4 max-w-md mx-auto text-sm">
           We couldn't find any products
           matching your search criteria.
          </p>
          <button
           onClick={handleClearFilters}
           className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all font-medium text-sm"
          >
           Clear Filters & Show All
          </button>
         </div>
        )}
       </>
      )}

      {/* Pagination */}
      {pagination.total_pages > 1 && (
       <div className="flex justify-center mt-6">
        <nav className="flex items-center space-x-1">
         {/* Previous Page */}
         <button
          onClick={() =>
           handlePageChange(
            pagination.current_page - 1
           )
          }
          disabled={pagination.current_page === 1}
          className="px-2 py-1.5 rounded border border-gray-300 text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
         >
          Previous
         </button>

         {/* Page Numbers */}
         {Array.from(
          {
           length: pagination.total_pages,
          },
          (_, i) => i + 1
         ).map((page) => (
          <button
           key={page}
           onClick={() =>
            handlePageChange(page)
           }
           className={`px-2.5 py-1.5 rounded border text-sm font-medium transition-all ${pagination.current_page === page
             ? "bg-blue-600 text-white border-blue-600"
             : "border-gray-300 text-gray-500 hover:bg-gray-50"
            }`}
          >
           {page}
          </button>
         ))}

         {/* Next Page */}
         <button
          onClick={() =>
           handlePageChange(
            pagination.current_page + 1
           )
          }
          disabled={
           pagination.current_page ===
           pagination.total_pages
          }
          className="px-2 py-1.5 rounded border border-gray-300 text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
         >
          Next
         </button>
        </nav>
       </div>
      )}
     </div>
    </div>
   </div>
  </div>
 );
};

export default Products;
