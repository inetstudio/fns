<?php

namespace InetStudio\Fns\Receipts\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\AdminPanel\Models\Traits\HasJSONColumns;
use InetStudio\AddressesPackage\Points\Models\Traits\HasPoints;
use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;

class ReceiptModel extends Model implements ReceiptModelContract
{
    use HasPoints;
    use SoftDeletes;
    use HasJSONColumns;

    const ENTITY_TYPE = 'fns_receipt';

    protected $table = 'fns_receipts';

    protected $fillable = [
        'qr_code',
        'data',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($receipt) {
            $receipt->{$receipt->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }
}
