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
            'transaction_identifier' => (int)$transaction->id,
            'quantity' => (int)$transaction->quantity,
            'buyer' => (int)$transaction->buyer_id,
            'product' => (int)$transaction->product_id,
            'creation_Date' => (string)$transaction->created_at,
            'last_Change' => (string)$transaction->updated_at,
            'deleted_Date' => (isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null),

            'links' =>[
                [
                    'href' => route('transactions.show', $transaction->id),
                    'rel' => 'self',
                    'type' => 'GET'
                ],
                [
                    'href' => route('transactions.categories.index', $transaction->id),
                    'rel' => 'transactions.categories',
                    'type' => 'GET'
                ],
                [
                    'href' => route('transactions.sellers.index', $transaction->id),
                    'rel' => 'transactions.sellers',
                    'type' => 'GET'
                ],
                [
                    'href' => route('buyers.show', $transaction->buyer_id),
                    'rel' => 'buyer',
                    'type' => 'GET'
                ],
                [
                    'href' => route('products.show', $transaction->product_id),
                    'rel' => 'product',
                    'type' => 'GET'
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $array = [
            'identifier' => 'id',
            'quantity' => 'quantity',
            'buyer' => 'buyer_id',
            'product' => 'product_id',
            'creation_Date' => 'created_at',
            'last_Change' => 'updated_at',
            'deleted_Date' => 'deleted_at'
        ];

        return isset($array[$index]) ? $array[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $array = [
            'id' => 'identifier',
            'quantity' => 'quantity',
            'buyer_id' => 'buyer',
            'product_id' => 'product',
            'created_at' => 'creation_Date',
            'updated_at' => 'last_Change',
            'deleted_at' => 'deleted_Date'
        ];

        return isset($array[$index]) ? $array[$index] : null;
    }
}
