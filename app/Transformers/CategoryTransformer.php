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
            'identifier' => (int)$category->id,
            'title' => (string)$category->name,
            'details' => (string)$category->description,
            'creation_Date' => (string)$category->created_at,
            'last_Change' => (string)$category->updated_at,
            'deleted_Date' => (isset($category->deleted_at) ? (string)$category->deleted_at : null)
        ];
    }
}
