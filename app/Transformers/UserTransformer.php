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
            'identifier' => (string)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'isVerified' => (int)$user->verified,
            'isAdmin' => ($user->admin === "true"),
            'creation_Date' => (string)$user->created_at,
            'last_Change' => (string)$user->updated_at,
            'deleted_Date' => (isset($user->deleted_at) ? (string)$user->deleted_at : null)
        ];
    }
}
