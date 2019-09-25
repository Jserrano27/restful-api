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
            'dateCreated' => $user->created_at,
            'lastChange' => $user->updated_at,
            'dateDeleted' => (isset($user->deleted_at) ? (string)$user->deleted_at : null)
        ];
    }
}
