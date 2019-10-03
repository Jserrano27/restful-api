<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'category_identifier' => (int)$category->id,
            'title' => (string)$category->name,
            'details' => (string)$category->description,
            'creation_Date' => (string)$category->created_at,
            'last_Change' => (string)$category->updated_at,
            'deleted_Date' => (isset($category->deleted_at) ? (string)$category->deleted_at : null),

            'links' =>[
                [
                    'href' => route('categories.show', $category->id),
                    'rel' => 'self',
                    'type' => 'GET',
                ],
                [
                    'href' => route('categories.buyers.index', $category->id),
                    'rel' => 'categories.buyers',
                    'type' => 'GET',
                ],
                [
                    'href' => route('categories.products.index', $category->id),
                    'rel' => 'categories.products',
                    'type' => 'GET',
                ],
                [
                    'href' => route('categories.sellers.index', $category->id),
                    'rel' => 'categories.sellers',
                    'type' => 'GET',
                ],
                [
                    'href' => route('categories.transactions.index', $category->id),
                    'rel' => 'categories.transactions',
                    'type' => 'GET',
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
            'creation_Date' => 'created_at',
            'last_Change' => 'updated_at',
            'deleted_Date' => 'deleted_at'
        ];

        return isset($array[$index]) ? $array[$index] : null;
    }
}
