<?php

namespace Tests\Traits;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

trait AuthHelper
{
    /**
     * Returns headers with JWT Authorization for a given user.
     *
     * @param User|null $user
     * @return array
     */
    public function getAuthHeaders(User $user = null): array
    {
        $user = $user ?: User::factory()->create();
        $token = JWTAuth::fromUser($user);

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
    }
}
