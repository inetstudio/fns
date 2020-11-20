<?php

namespace InetStudio\Fns\Drivers\Fns\Models;

use Carbon\Carbon;

final class TemporaryToken
{
    protected string $token;

    protected Carbon $expireTime;

    protected final function __construct(string $token, Carbon $expireTime)
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
