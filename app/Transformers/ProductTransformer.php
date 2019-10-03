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
            'deleted_Date' => (isset ($product->deleted_at) ? (string)$product->deleted_at : null)
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
