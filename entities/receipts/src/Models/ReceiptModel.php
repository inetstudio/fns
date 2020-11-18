<?php

namespace InetStudio\Fns\Receipts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\AdminPanel\Models\Traits\HasJSONColumns;
use InetStudio\AddressesPackage\Points\Models\Traits\HasPoints;
use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;
use InetStudio\AdminPanel\Base\Models\Traits\Scopes\BuildQueryScopeTrait;

class ReceiptModel extends Model implements ReceiptModelContract
{
    use HasPoints;
    use SoftDeletes;
    use HasJSONColumns;
    use BuildQueryScopeTrait;

    /**
     * Тип сущности.
     */
    const ENTITY_TYPE = 'fns_receipt';

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'fns_receipts';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'qr_code',
        'receipt',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы к базовым типам.
     *
     * @var array
     */
    protected $casts = [
        'receipt' => 'array',
    ];

    /**
     * Загрузка модели.
     */
    protected static function boot()
    {
        parent::boot();

        self::$buildQueryScopeDefaults['columns'] = [
            'id',
            'qr_code',
            'receipt',
        ];
    }

    /**
     * Сеттер атрибута qr_code.
     *
     * @param $value
     */
    public function setQrCodeAttribute($value): void
    {
        $this->attributes['qr_code'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута receipt.
     *
     * @param $value
     */
    public function setReceiptAttribute($value)
    {
        $this->attributes['receipt'] = json_encode((array) $value);
    }

    /**
     * Геттер атрибута type.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }
}
