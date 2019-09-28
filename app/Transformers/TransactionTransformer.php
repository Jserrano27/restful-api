<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int)$transaction->id,
            'quantity' => (int)$transaction->quantity,
            'buyer' => (int)$transaction->buyer_id,
            'product' => (int)$transaction->product_id,
            'creation_Date' => (string)$transaction->created_at,
            'last_Change' => (string)$transaction->updated_at,
            'deleted_Date' => (isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null)
        ];
    }
}
