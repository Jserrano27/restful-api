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
            'deleted_Date' => (isset($seller->deleted_at) ? (string)$seller->deleted_at : null)
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
