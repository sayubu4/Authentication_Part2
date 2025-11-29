<?php
// Include the product controller
require_once(__DIR__ . '/../controllers/product_controller.php');

// Helper function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Initialize response array
$response = [
    'status' => 'error',
    'message' => 'Invalid request',
    'data' => []
];

/* ----------------------------------------------------------
   ACTION HANDLER
----------------------------------------------------------- */
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {

        /* ------------------- VIEW ALL PRODUCTS (OPTIONALLY BY REGION) ------------------- */
        case 'view_all':
            if (!empty($_GET['region_id'])) {
                $region_id = sanitize_input($_GET['region_id']);
                $products = get_products_by_region_ctr($region_id);
                $message = 'Products for selected region retrieved successfully';
            } else {
                $products = view_all_products_ctr();
                $message = 'All products retrieved successfully';
            }

            $response = [
                'status' => 'success',
                'message' => $message,
                'data' => $products
            ];
            break;

        /* ------------------- VIEW SINGLE PRODUCT ------------------- */
        case 'view_single':
            if (isset($_GET['id'])) {
                $id = sanitize_input($_GET['id']);
                $product = view_single_product_ctr($id);
                $response = [
                    'status' => $product ? 'success' : 'error',
                    'message' => $product ? 'Product found' : 'Product not found',
                    'data' => $product
                ];
            }
            break;

        /* ------------------- SEARCH PRODUCTS ------------------- */
        case 'search':
            if (isset($_GET['query'])) {
                $query = sanitize_input($_GET['query']);
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $perPage = 10;

                $results = search_products_ctr($query);
                $totalResults = count($results);
                $startIndex = ($page - 1) * $perPage;
                $pagedResults = array_slice($results, $startIndex, $perPage);

                $response = [
                    'status' => 'success',
                    'message' => $totalResults > 0 ? 'Results found' : 'No products match your search',
                    'data' => $pagedResults,
                    'pagination' => [
                        'total' => $totalResults,
                        'page' => $page,
                        'pages' => ceil($totalResults / $perPage)
                    ]
                ];
            }
            break;

        /* ------------------- FILTER BY CATEGORY ------------------- */
        case 'filter_category':
            if (isset($_GET['cat_id'])) {
                $cat_id = sanitize_input($_GET['cat_id']);
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $perPage = 10;

                $results = filter_products_by_category_ctr($cat_id);
                $totalResults = count($results);
                $startIndex = ($page - 1) * $perPage;
                $pagedResults = array_slice($results, $startIndex, $perPage);

                $response = [
                    'status' => 'success',
                    'message' => 'Filtered by category successfully',
                    'data' => $pagedResults,
                    'pagination' => [
                        'total' => $totalResults,
                        'page' => $page,
                        'pages' => ceil($totalResults / $perPage)
                    ]
                ];
            }
            break;

        /* ------------------- FILTER BY BRAND ------------------- */
        case 'filter_brand':
            if (isset($_GET['brand_id'])) {
                $brand_id = sanitize_input($_GET['brand_id']);
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $perPage = 10;

                $results = filter_products_by_brand_ctr($brand_id);
                $totalResults = count($results);
                $startIndex = ($page - 1) * $perPage;
                $pagedResults = array_slice($results, $startIndex, $perPage);

                $response = [
                    'status' => 'success',
                    'message' => 'Filtered by brand successfully',
                    'data' => $pagedResults,
                    'pagination' => [
                        'total' => $totalResults,
                        'page' => $page,
                        'pages' => ceil($totalResults / $perPage)
                    ]
                ];
            }
            break;

        /* ------------------- COMPOSITE / ADVANCED FILTER ------------------- */
        case 'filter_advanced':
            $filters = [
                'cat_id'   => $_GET['cat_id'] ?? '',
                'brand_id' => $_GET['brand_id'] ?? '',
                'min_price' => $_GET['min_price'] ?? '',
                'max_price' => $_GET['max_price'] ?? '',
                'keyword'  => $_GET['keyword'] ?? ''
            ];

            foreach ($filters as $key => $value) {
                $filters[$key] = sanitize_input($value);
            }

            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $perPage = 10;

            $results = filter_products_ctr($filters);
            $totalResults = count($results);
            $startIndex = ($page - 1) * $perPage;
            $pagedResults = array_slice($results, $startIndex, $perPage);

            $response = [
                'status' => 'success',
                'message' => 'Advanced filtering applied',
                'data' => $pagedResults,
                'pagination' => [
                    'total' => $totalResults,
                    'page' => $page,
                    'pages' => ceil($totalResults / $perPage)
                ]
            ];
            break;

        default:
            $response = [
                'status' => 'error',
                'message' => 'Unknown action'
            ];
            break;
    }
}

/* ----------------------------------------------------------
   OUTPUT RESPONSE
----------------------------------------------------------- */
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
