<?php

namespace InetStudio\Fns\Accounts\Services\Back;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\Fns\Accounts\Contracts\Models\AccountModelContract;
use InetStudio\Fns\Accounts\Contracts\Services\Back\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    /**
     * ItemsService constructor.
     *
     * @param  AccountModelContract  $model
     */
    public function __construct(AccountModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return AccountModelContract
     */
    public function save(array $data, int $id): AccountModelContract
    {
        $itemData = Arr::only($data, $this->model->getFillable());
        $item = $this->saveModel($itemData, $id);

        return $item;
    }

    /**
     * Получаем активный аккаунт.
     *
     * @return AccountModelContract|null
     */
    public function getActiveAccount(): ?AccountModelContract
    {
        return $this->model::whereNull('blocked_at')->first();
    }

    /**
     * Блокируем аккаунт.
     *
     * @param  string  $login
     * @param  int  $timestamp
     */
    public function blockAccount(string $login, int $timestamp): void
    {
        $account = $this->model::where('login', $login)->first();

        if (! $account) {
            return;
        }

        $this->save(
            [
                'blocked_at' => $timestamp,
            ],
            $account['id']
        );
    }

    /**
     * Сбрасываем блокировку аккаунтов.
     *
     * @param  int  $days
     */
    public function resetAccountsBlocking(int $days): void
    {
        $this->model::where('blocked_at', '<=', Carbon::now()->subDays($days)->subHour()->toDateTimeString())
            ->update(
                [
                    'blocked_at' => null,
                ]
            );
    }
}
