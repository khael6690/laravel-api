<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    public function update(User $user, Item $item)
    {
        return $user->id === $item->user_id
            ? Response::allow()
            : Response::deny('You do not own this item.');
    }

    public function delete(User $user, Item $item)
    {
        return $user->id === $item->user_id
            ? Response::allow()
            : Response::deny('You do not own this item.');
    }
}
