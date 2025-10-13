// import React from 'react';
// import { Link } from 'react-router-dom';
// import AddToCart from '../cart/AddToCart';

// const ProductCard = ({ product }) => {
//     const firstSku = product.skus?.[0];
    
//     return (
//         <div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
//             <Link to={`/products/${product.slug}`}>
//                 <img 
//                     src={product.image || '/images/placeholder.jpg'} 
//                     alt={product.name}
//                     className="w-full h-48 object-cover"
//                 />
//             </Link>
            
//             <div className="p-4">
//                 <Link to={`/products/${product.slug}`}>
//                     <h3 className="text-lg font-semibold text-gray-800 hover:text-blue-600">
//                         {product.name}
//                     </h3>
//                 </Link>
                
//                 <p className="text-gray-600 text-sm mt-1 line-clamp-2">
//                     {product.description}
//                 </p>
                
//                 <div className="mt-3 flex items-center justify-between">
//                     <span className="text-2xl font-bold text-gray-900">
//                         ${firstSku?.price || product.price}
//                     </span>
                    
//                     {firstSku && (
//                         <AddToCart sku={firstSku} productName={product.name} />
//                     )}
//                 </div>
//             </div>
//         </div>
//     );
// };

// export default ProductCard;