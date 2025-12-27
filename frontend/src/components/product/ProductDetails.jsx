// import React, { useState } from "react";
// import AddToCart from "../cart/AddToCart";

// const ProductDetails = ({ product }) => {
//     const [selectedSku, setSelectedSku] = useState(product.skus?.[0]);

//     return (
//         <div className="bg-white rounded-lg shadow-lg p-6">
//             <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
//                 {/* Product Image */}
//                 <div>
//                     <img
//                         src={product.image || "/images/placeholder.jpg"}
//                         alt={product.name}
//                         className="w-full rounded-lg"
//                     />
//                 </div>

//                 {/* Product Info */}
//                 <div>
//                     <h1 className="text-3xl font-bold text-gray-900">
//                         {product.name}
//                     </h1>
//                     <p className="text-gray-600 mt-2">
//                         {product.category?.name}
//                     </p>

//                     <p className="text-2xl font-bold text-gray-900 mt-4">
//                         ${selectedSku?.price || product.price}
//                     </p>

//                     <p className="text-gray-700 mt-4">{product.description}</p>

//                     {/* SKU Selection */}
//                     {product.skus && product.skus.length > 1 && (
//                         <div className="mt-6">
//                             <h3 className="text-lg font-semibold">Variants:</h3>
//                             <div className="flex flex-wrap gap-2 mt-2">
//                                 {product.skus.map((sku) => (
//                                     <button
//                                         key={sku.id}
//                                         onClick={() => setSelectedSku(sku)}
//                                         className={`px-4 py-2 border rounded-lg ${
//                                             selectedSku?.id === sku.id
//                                                 ? "border-blue-500 bg-blue-50"
//                                                 : "border-gray-300"
//                                         }`}
//                                     >
//                                         {sku.attributes?.color} -{" "}
//                                         {sku.attributes?.size}
//                                     </button>
//                                 ))}
//                             </div>
//                         </div>
//                     )}

//                     {/* Add to Cart */}
//                     {selectedSku && (
//                         <div className="mt-6">
//                             <AddToCart
//                                 sku={selectedSku}
//                                 productName={product.name}
//                             />
//                         </div>
//                     )}
//                 </div>
//             </div>
//         </div>
//     );
// };

// export default ProductDetails;
