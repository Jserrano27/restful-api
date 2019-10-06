<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'user_identifier' => (string)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'is_Verified' => (int)$user->verified,
            'is_Admin' => ($user->admin === "true"),
            'creation_Date' => (string)$user->created_at,
            'last_Change' => (string)$user->updated_at,
            'deleted_Date' => (isset($user->deleted_at) ? (string)$user->deleted_at : null),

            'links' =>[
                [
                    'href' => route('users.show', $user->id),
                    'rel' => 'self',
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
            'is_Admin' => 'admin',
            'creation_Date' => 'created_at',
            'last_Change' => 'updated_at',
            'deleted_Date' => 'deleted_at',
        ];

        return isset($array[$index]) ? $array[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $array = [
            'id' => 'identifier',
            'name' => 'name',
            'email' => 'email',
            'verified' => 'is_Verified',
            'admin' => 'is_Admin',
            'created_at' => 'creation_Date',
            'updated_at' => 'last_Change',
            'deleted_at' => 'deleted_Date',
        ];

        return isset($array[$index]) ? $array[$index] : null;
    }
}
