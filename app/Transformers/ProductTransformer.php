<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'product_identifier' => (int)$product->id,
            'title' => (string)$product->name,
            'details' => (string)$product->description,
            'stock' => (int)$product->quantity,
            'situation' => (string)$product->status,
            'picture' => url("img/{$product->image}"),
            'seller' => (int)$product->seller_id,
            'creation_Date' => (string)$product->created_at,
            'last_Change' => (string)$product->updated_at,
            'deleted_Date' => (isset ($product->deleted_at) ? (string)$product->deleted_at : null),

            'links' =>[
                [
                    'href' => route('products.show', $product->id),
                    'rel' => 'self',
                    'type' => 'GET'
                ],
                [
                    'href' => route('products.buyers.index', $product->id),
                    'rel' => 'products.buyers',
                    'type' => 'GET'
                ],
                [
                    'href' => route('products.categories.index', $product->id),
                    'rel' => 'products.categories',
                    'type' => 'GET'
                ],
                [
                    'href' => route('products.transactions.index', $product->id),
                    'rel' => 'products.transactions',
                    'type' => 'GET'
                ],
                [
                    'href' => route('sellers.show', $product->seller_id),
                    'rel' => 'seller',
                    'type' => 'GET'
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $array = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'stock' => 'quantity',
            'situation' => 'status',
            'seller' => 'seller_id',
            'creation_Date' => 'created_at',
            'last_Change' => 'updated_at',
            'deleted_Date' => 'deleted_at'
        ];

        return isset($array[$index]) ? $array[$index] : null;
    }
}
