<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCartAPIRequest;
use App\Http\Requests\API\UpdateCartAPIRequest;
use App\Models\Cart;
use App\Models\Offers;
use App\Services\ProductService;
use Illuminate\Http\Request;

class CartAPIController extends AppBaseController
{
    /**
     * @var productService
     */
    protected $productService;

    /**
     * CartController Constructor
     *
     * @param ProductService $productService
     *
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return mixed
     */
    public function cart()
    {
        try {

            $cart = Cart::query()->get();

            if (count($cart) > 0) {
                $sub_total = 0;
                $total_price = 0;
                $taxes = 0;
                $discounts = [];
                $cart->map( function ($value) use (&$total_price, &$discounts, &$sub_total, &$taxes){
                    $product = $this->productService->getById($value->product_id);

                    if (\request()->has('currency') && \request('currency') !== null) {
                        $currency = env('C_CURRENCY');

                        $url = 'https://api.exchangerate.host/convert?from=' . $currency . '&to=' . \request('currency');

                        $rate = $this->getUserCurrency($url);

                        $product->price = ($product->price * $value->quantity) * $rate;
                    }

                    $taxes += ($product->price * $value->quantity) * 14 / 100;
                    $sub_total += $product->price * $value->quantity;

                    $discount = $this->calculateDiscount($product);
                    if ($discount !== 0) {
                        $discount_value = ($product->price * $value->quantity) * $discount / 100;
                        $total_price += ( $product->price * $value->quantity) - $discount_value;

                        $discount_text = $discount . '% off ' . $product->name . ':  -' . $discount_value;
                        array_push($discounts, $discount_text);

                    } else {
                        $total_price += $product->price * $value->quantity;
                    }
                });

                $response = array(
                    'sub_total' => number_format($sub_total, 2),
                    'taxes' => number_format($taxes, 2),
                    'discounts' => $discounts,
                    'total_price' => number_format($total_price + $taxes, 2),
                );

                return $this->sendApiResponse($response, 'Cart retrieved successfully');
            }

            return $this->sendApiResponse(array(), 'Cart retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendApiError('Something went wrong! ', 500);
        }
    }

    /**
     * @param $request
     * @param $product_id
     * @return mixed
     */
    public function addToCart(CreateCartAPIRequest $request, $product_id)
    {
        try {

            $product = $this->productService->getById($product_id);

            if (!$product) return $this->sendApiError('Item not found', 404);

            if ( $product->in_stock == 0) return $this->sendApiError('This Product is out of stock', 404);

            $cart = Cart::query()->where('product_id', $product->id)->first();

            if (!$cart) {
                Cart::query()->insert([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity
                ]);
                return $this->sendApiResponse(array(), 'Item added to cart successfully');
            }

            return $this->sendApiResponse(array(), 'Item already added to cart successfully');

        } catch (\Exception $e) {
            return $this->sendApiError('Something went wrong! ', 500);
        }
    }

    /**
     * @param UpdateCartAPIRequest $request
     * @return mixed
     */
    public function updateCart(UpdateCartAPIRequest $request)
    {
        try {

            if ($request->product_id and $request->quantity) {
                $cart = Cart::query()->where('product_id', $request->product_id)->first();

                $product = $this->productService->getById($cart->product_id);

                if (!$cart) {
                    return $this->sendApiError('Item not found!');
                }

                if ($request->quantity > $product->in_stock) {
                    return $this->sendApiError('Quantity Not enough!');
                }

                \DB::transaction(function() use ($product, $cart, $request){
                    Cart::query()->where('product_id', $product->id)->update([
                        'product_id' => $product->id,
                        'quantity' => $request->quantity
                    ]);
                });

                return $this->sendApiResponse(array(), __('Cart updated successfully.'));
            }

            return $this->sendApiError('Bad Request');
        } catch (\Exception $e) {
            return $this->sendApiError('Something went wrong!');
        }
    }

    /**
     * Calculate discount of price based on product offer
     * @param $product
     * @return int|mixed
     */
    private function calculateDiscount($product)
    {
        if ($product->offer_id) {
            $offer = Offers::query()->where('id', $product->offer_id)->first();

            if ($offer->product_id == null) {
                return $offer->discount;
            }

            $product_in_cart = Cart::query()->where('product_id', $offer->product_id)->first();

            if ($product_in_cart) {
              if ($product_in_cart->quantity >= $offer->quantity) {
                  return $offer->discount;
              }
            }
        }

        return 0;
    }
}
