<?php


namespace GSD\Containers\Import\Policies;


use App\User;
use GSD\Containers\Import\Models\ImportLog;
use GSD\Containers\Import\Models\ImportStatistic;

/**
 * Class ImportStatisticPolicy
 * @package GSD\Containers\Import\Policies
 */
class ImportStatisticPolicy
{
    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, ImportStatistic $model)
    {
        return false;
    }

    public function delete(User $user, ImportStatistic $model)
    {
        return false;
    }

    public function view(User $user, ImportStatistic $model)
    {
        return false;
    }
}