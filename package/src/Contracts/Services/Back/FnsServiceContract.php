<?php

namespace InetStudio\Fns\Contracts\Services\Back;

/**
 * Interface FnsServiceContract.
 */
interface FnsServiceContract
{
    /**
     * Регистрация в сервисе ФНС.
     *
     * @param  array  $params
     *
     * @return bool
     */
    public function signUp(array $params): bool;

    /**
     * Проверка существования чека.
     *
     * @param  array  $params
     *
     * @return bool
     */
    public function checkReceipt(array $params): bool;

    /**
     * Возвращаем содержимое чека.
     *
     * @param  array  $params
     *
     * @return array|null
     */
    public function getReceipt(array $params): ?array;
}
