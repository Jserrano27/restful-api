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
            'identifier' => (string)$seller->id,
            'name' => (string)$seller->name,
            'email' => (string)$seller->email,
            'is_Verified' => (int)$seller->verified,
            'creation_Date' => (string)$seller->created_at,
            'last_Change' => (string)$seller->updated_at,
            'deleted_Date' => (isset($seller->deleted_at) ? (string)$seller->deleted_at : null)
        ];
    }
}
