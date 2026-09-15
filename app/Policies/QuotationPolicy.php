<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
{
    public function view(User $user, Quotation $quotation): bool
    {
        return $user->id === $quotation->user_id || $user->isAdmin();
    }

    public function update(User $user, Quotation $quotation): bool
    {
        return $user->id === $quotation->user_id || $user->isAdmin();
    }

    public function delete(User $user, Quotation $quotation): bool
    {
        return $user->isAdmin();
    }
}
