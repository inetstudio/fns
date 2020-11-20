<?php

namespace InetStudio\Fns\Drivers\Fns\Models;

use Carbon\Carbon;

final class TemporaryToken
{
    private string $token;

    private Carbon $expireTime;

    private final function __construct(string $token, Carbon $expireTime)
    {
        $this->token = $token;
        $this->expireTime = $expireTime;
    }

    public static function create(string $token, Carbon $expireTime): self
    {
        return new TemporaryToken($token, $expireTime);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpireTime(): Carbon
    {
        return $this->expireTime;
    }
}
