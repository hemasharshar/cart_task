<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Services\ProductService;

class ProductAPIController extends AppBaseController
{
    /**
     * @var productService
     */
    protected $productService;

    /**
     * authController Constructor
     *
     * @param ProductService $productService
     *
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the products resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $products = $this->productService->getAll();

            $response = array(
                'products' => $products
            );
            return $this->sendApiResponse($response, 'Products retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendApiError('Something went wrong!', 500);
        }
    }

    /**
     * Display the specified product resource.
     *
     * @param  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = $this->productService->getById($id);

            $response = array(
                'product_details' => $product
            );

            return $this->sendApiResponse($response, 'Product details retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendApiError('Something went wrong!', 500);
        }
    }
}
