<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'buyer_identifier' => (string)$buyer->id,
            'name' => (string)$buyer->name,
            'email' => (string)$buyer->email,
            'is_Verified' => (int)$buyer->verified,
            'creation_Date' => (string)$buyer->created_at,
            'last_Change' => (string)$buyer->updated_at,
            'deleted_Date' => (isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null)
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
