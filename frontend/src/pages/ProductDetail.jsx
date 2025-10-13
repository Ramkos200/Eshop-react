import React, { useState, useEffect } from "react";
import { useParams, Link } from "react-router-dom";
import { productService } from "../services/productService";
import { useCart } from "../context/CartContext";

const ProductDetail = () => {
    const { slug } = useParams();
    const [product, setProduct] = useState(null);
    const [selectedSku, setSelectedSku] = useState(null);
    const [mainImage, setMainImage] = useState(null);
    const [quantity, setQuantity] = useState(1);
    const [loading, setLoading] = useState(true);
    const { addToCart } = useCart();

    useEffect(() => {
        fetchProduct();
    }, [slug]);

    const getImageUrl = (imageData) => {
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

    const fetchProduct = async () => {
        try {
            setLoading(true);
            const response = await productService.getProduct(slug);

            if (response.success) {
                setProduct(response.data);

                // Set first available SKU as default
                const availableSku =
                    response.data.skus?.find((sku) => sku.inventory > 0) ||
                    response.data.skus?.[0];
                setSelectedSku(availableSku);
                // Set initial image based on selected SKU's main_image
                if (availableSku?.main_image) {
                    setMainImage(availableSku.main_image);
                } else if (
                    availableSku?.gallery_images &&
                    availableSku.gallery_images.length > 0
                ) {
                    // Fallback to first gallery image if no main_image
                    setMainImage(availableSku.gallery_images[0]);
                }
            }
        } catch (error) {
            console.error("Failed to fetch product:", error);
        } finally {
            setLoading(false);
        }
    };

    const handleSkuSelect = (sku) => {
        setSelectedSku(sku);
        setQuantity(1); // Reset quantity when SKU changes

        // Update main image to show the selected SKU's main_image
        if (sku.main_image) {
            setMainImage(sku.main_image);
        } else if (sku.gallery_images && sku.gallery_images.length > 0) {
            // Fallback to first gallery image if no main_image
            setMainImage(sku.gallery_images[0]);
        } else {
            // If SKU has no images at all, clear the main image
            setMainImage(null);
        }
    };

    const handleImageSelect = (image) => {
        setMainImage(image);
    };

    const handleAddToCart = async () => {
        if (!selectedSku) return;

        try {
            await addToCart(selectedSku.id, quantity);
            alert("Product added to cart!");
        } catch (error) {
            alert(error.response?.data?.message || "Failed to add to cart");
        }
    };

    const getInventoryStatus = (inventory) => {
        if (inventory === 0) {
            return { text: "Out of Stock", class: "text-red-600 bg-red-100" };
        } else if (inventory < 5) {
            return {
                text: `Only ${inventory} Left`,
                class: "text-amber-600 bg-amber-100",
            };
        } else {
            return { text: "In Stock", class: "text-green-600 bg-green-100" };
        }
    };

    if (loading) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        );
    }

    if (!product) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <div className="text-center">
                    <h2 className="text-2xl font-bold text-gray-900 mb-4">
                        Product Not Found
                    </h2>
                    <Link
                        to="/products"
                        className="text-blue-600 hover:underline"
                    >
                        Back to Products
                    </Link>
                </div>
            </div>
        );
    }

    // Get all images ONLY from the selected SKU
    const allImages = [];

    // Add selected SKU's main image first
    if (selectedSku?.main_image) {
        allImages.push(selectedSku.main_image);
    }

    // Add selected SKU's gallery images
    if (selectedSku?.gallery_images) {
        allImages.push(...selectedSku.gallery_images);
    }

    const currentInventoryStatus = selectedSku
        ? getInventoryStatus(selectedSku.inventory)
        : null;

    return (
        <div className="max-w-7xl mx-auto px-4 py-8">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* Product Images */}
                <div>
                    {/* Main Image */}
                    <div className="bg-white rounded-lg shadow-lg overflow-hidden mb-4 relative">
                        {mainImage ? (
                            <img
                                src={getImageUrl(mainImage)}
                                alt={product.name}
                                className="w-full h-96 object-cover"
                                onError={(e) => {
                                    console.error(
                                        "Image failed to load:",
                                        mainImage
                                    );
                                    e.target.style.display = "none";
                                }}
                            />
                        ) : (
                            <div className="w-full h-96 bg-gray-200 flex items-center justify-center">
                                <span className="text-gray-400">
                                    No Image Available for this Variant
                                </span>
                            </div>
                        )}

                        {/* Inventory Status Badge */}
                        {currentInventoryStatus && (
                            <div className="absolute top-4 right-4">
                                <span
                                    className={`px-3 py-1 rounded-full text-sm font-semibold ${currentInventoryStatus.class}`}
                                >
                                    {currentInventoryStatus.text}
                                </span>
                            </div>
                        )}
                    </div>

                    {/* Gallery Images - Only show if selected SKU has multiple images */}
                    {allImages.length > 1 && (
                        <div className="grid grid-cols-4 gap-2">
                            {allImages.map((image) => (
                                <button
                                    key={image.id}
                                    onClick={() => handleImageSelect(image)}
                                    className={`w-full h-20 rounded border-2 overflow-hidden ${
                                        mainImage?.id === image.id
                                            ? "border-blue-600 ring-2 ring-blue-200"
                                            : "border-gray-300 hover:border-gray-400"
                                    }`}
                                >
                                    <img
                                        src={getImageUrl(image)}
                                        alt={product.name}
                                        className="w-full h-full object-cover"
                                        onError={(e) => {
                                            console.error(
                                                "Gallery image failed to load:",
                                                image
                                            );
                                            e.target.style.display = "none";
                                        }}
                                    />
                                </button>
                            ))}
                        </div>
                    )}

                    {/* Product Description - MOVED HERE, below the images */}
                    <div className="mt-6">
                        <h3 className="text-lg font-semibold mb-3">
                            Description
                        </h3>
                        <p className="text-gray-600 leading-relaxed">
                            {product.description}
                        </p>
                    </div>
                </div>

                {/* Product Info */}
                <div className="space-y-6">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900 mb-2">
                            {product.name}
                        </h1>
                        <div className="flex items-center space-x-2 mb-4">
                            <span className="text-2xl font-bold text-gray-900">
                                ${selectedSku?.price || product.price}
                            </span>
                            {currentInventoryStatus && (
                                <span
                                    className={`text-sm px-2 py-1 rounded ${currentInventoryStatus.class}`}
                                >
                                    {currentInventoryStatus.text}
                                </span>
                            )}
                        </div>
                    </div>

                    {/* SKU Selection */}
                    {product.skus && product.skus.length > 0 && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">
                                Available Variants
                            </h3>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {product.skus.map((sku) => {
                                    const skuStatus = getInventoryStatus(
                                        sku.inventory
                                    );
                                    const isSelected =
                                        selectedSku?.id === sku.id;

                                    return (
                                        <button
                                            key={sku.id}
                                            onClick={() => handleSkuSelect(sku)}
                                            disabled={sku.inventory === 0}
                                            className={`p-3 border rounded-lg text-left transition-all ${
                                                isSelected
                                                    ? "border-blue-600 bg-blue-50 ring-2 ring-blue-200"
                                                    : "border-gray-300 hover:border-gray-400"
                                            } ${
                                                sku.inventory === 0
                                                    ? "opacity-50 cursor-not-allowed"
                                                    : ""
                                            }`}
                                        >
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <div className="font-medium">
                                                        {sku.attributes
                                                            ?.color &&
                                                            `${sku.attributes.color}`}
                                                        {sku.attributes?.size &&
                                                            ` - ${sku.attributes.size}`}
                                                        {sku.attributes
                                                            ?.material &&
                                                            ` (${sku.attributes.material})`}
                                                        {!sku.attributes
                                                            ?.color &&
                                                            !sku.attributes
                                                                ?.size &&
                                                            !sku.attributes
                                                                ?.material &&
                                                            "Standard"}
                                                    </div>
                                                    <div className="text-lg font-bold text-gray-900 mt-1">
                                                        ${sku.price}
                                                    </div>
                                                </div>
                                                <div
                                                    className={`text-xs px-2 py-1 rounded ${skuStatus.class}`}
                                                >
                                                    {skuStatus.text}
                                                </div>
                                            </div>

                                            {/* SKU Image */}
                                            {(sku.main_image ||
                                                sku.gallery_images?.length >
                                                    0) && (
                                                <div className="mt-2">
                                                    <div className="w-12 h-12 rounded border border-gray-300 overflow-hidden">
                                                        <img
                                                            src={getImageUrl(
                                                                sku.main_image ||
                                                                    sku
                                                                        .gallery_images[0]
                                                            )}
                                                            alt={
                                                                sku.attributes
                                                                    ?.color ||
                                                                "SKU"
                                                            }
                                                            className="w-full h-full object-cover"
                                                            onError={(e) => {
                                                                console.error(
                                                                    "SKU thumbnail failed to load:",
                                                                    sku.main_image ||
                                                                        sku
                                                                            .gallery_images[0]
                                                                );
                                                                e.target.style.display =
                                                                    "none";
                                                            }}
                                                        />
                                                    </div>
                                                </div>
                                            )}
                                        </button>
                                    );
                                })}
                            </div>
                        </div>
                    )}

                    {/* Add to Cart */}
                    {selectedSku && selectedSku.inventory > 0 && (
                        <div className="space-y-4">
                            <div className="flex items-center space-x-4">
                                <label className="text-sm font-medium">
                                    Quantity:
                                </label>
                                <select
                                    value={quantity}
                                    onChange={(e) =>
                                        setQuantity(parseInt(e.target.value))
                                    }
                                    className="border border-gray-300 rounded px-3 py-2"
                                >
                                    {[
                                        ...Array(
                                            Math.min(10, selectedSku.inventory)
                                        ),
                                    ].map((_, i) => (
                                        <option key={i + 1} value={i + 1}>
                                            {i + 1}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <button
                                onClick={handleAddToCart}
                                className="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition font-semibold"
                            >
                                Add to Cart - $
                                {(selectedSku.price * quantity).toFixed(2)}
                            </button>
                        </div>
                    )}

                    {/* Product Details */}
                    <div className="border-t pt-6">
                        <h3 className="text-lg font-semibold mb-3">
                            Product Details
                        </h3>
                        <div className="space-y-2 text-sm text-gray-600">
                            <div className="flex justify-between">
                                <span>Category:</span>
                                <span>{product.category?.name}</span>
                            </div>
                            <div className="flex justify-between">
                                <span>SKU Code:</span>
                                <span>{selectedSku?.code}</span>
                            </div>
                            {selectedSku && (
                                <div className="flex justify-between">
                                    <span>Current Variant:</span>
                                    <span>
                                        Color:{" "}
                                        {selectedSku.attributes?.color &&
                                            `${selectedSku.attributes.color} `}
                                        | Size:{" "}
                                        {selectedSku.attributes?.size &&
                                            ` ${selectedSku.attributes.size} `}
                                        | Material:{" "}
                                        {selectedSku.attributes?.material &&
                                            ` ${selectedSku.attributes.material}`}
                                        {!selectedSku.attributes?.color &&
                                            !selectedSku.attributes?.size &&
                                            !selectedSku.attributes?.material &&
                                            "Standard"}
                                    </span>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ProductDetail;
