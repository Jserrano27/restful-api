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
            'identifier' => (string)$buyer->id,
            'name' => (string)$buyer->name,
            'email' => (string)$buyer->email,
            'is_Verified' => (int)$buyer->verified,
            'creation_Date' => (string)$buyer->created_at,
            'last_Change' => (string)$buyer->updated_at,
            'deleted_Date' => (isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null)
        ];
    }
}
