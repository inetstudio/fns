<?php

namespace InetStudio\Fns\Accounts\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\Fns\Accounts\Contracts\Models\AccountModelContract;
use InetStudio\AdminPanel\Base\Models\Traits\Scopes\BuildQueryScopeTrait;

/**
 * Class AccountModel.
 */
class AccountModel extends Model implements AccountModelContract
{
    use SoftDeletes;
    use BuildQueryScopeTrait;

    /**
     * Тип сущности.
     */
    const ENTITY_TYPE = 'fns_account';

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'fns_accounts';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'login',
        'password',
        'blocked_at',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'blocked_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Загрузка модели.
     */
    protected static function boot()
    {
        parent::boot();

        self::$buildQueryScopeDefaults['columns'] = [
            'id',
            'login',
            'password',
            'blocked_at',
        ];
    }

    /**
     * Сеттер атрибута name.
     *
     * @param $value
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута email.
     *
     * @param $value
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута login.
     *
     * @param $value
     */
    public function setLoginAttribute($value): void
    {
        $this->attributes['login'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута password.
     *
     * @param $value
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута blocked_at.
     *
     * @param $value
     */
    public function setBlockedAtAttribute($value)
    {
        $this->attributes['blocked_at'] = ($value) ? Carbon::createFromTimestamp($value) : null;
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
