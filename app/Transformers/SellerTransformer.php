<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'seller_identifier' => (string)$seller->id,
            'name' => (string)$seller->name,
            'email' => (string)$seller->email,
            'is_Verified' => (int)$seller->verified,
            'creation_Date' => (string)$seller->created_at,
            'last_Change' => (string)$seller->updated_at,
            'deleted_Date' => (isset($seller->deleted_at) ? (string)$seller->deleted_at : null),

            'links' =>[
                [
                    'href' => route('sellers.show', $seller->id),
                    'rel' => 'self',
                    'type' => 'GET'
                ],
                [
                    'href' => route('sellers.buyers.index', $seller->id),
                    'rel' => 'sellers.buyers',
                    'type' => 'GET'
                ],
                [
                    'href' => route('sellers.categories.index', $seller->id),
                    'rel' => 'sellers.categories',
                    'type' => 'GET'
                ],
                [
                    'href' => route('sellers.products.index', $seller->id),
                    'rel' => 'sellers.products',
                    'type' => 'GET'
                ],
                [
                    'href' => route('sellers.transactions.index', $seller->id),
                    'rel' => 'sellers.transactions',
                    'type' => 'GET'
                ],
                [
                    'href' => route('users.show', $seller->id),
                    'rel' => 'user.profile',
                    'type' => 'GET'
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $array = [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'is_Verified' => 'verified',
            'creation_Date' => 'created_at',
            'last_Change' => 'updated_at',
            'deleted_Date' => 'deleted_at'
        ];

        return isset($array[$index]) ? $array[$index] : null;
    }
}
