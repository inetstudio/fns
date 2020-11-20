<?php

namespace InetStudio\Fns\Drivers\Fns\Models;

use Carbon\Carbon;

final class Receipt
{
    public int $type;

    public Carbon $time;

    public int $sum;

    public int $fn;

    public int $fd;

    public int $fpd;

    public function __construct(int $type, Carbon $time, int $sum, int $fn, int $fd, int $fpd)
    {
        $this->type = $type;
        $this->time = $time;
        $this->sum = $sum;
        $this->fn = $fn;
        $this->fd = $fd;
        $this->fpd = $fpd;
    }
}
