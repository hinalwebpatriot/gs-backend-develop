<?php


namespace GSD\Containers\Import\Policies;


use App\User;
use GSD\Containers\Import\Models\ImportLog;

/**
 * Class ImportLogPolicy
 * @package GSD\Containers\Import\Policies
 */
class ImportLogPolicy
{
    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, ImportLog $model)
    {
        return false;
    }

    public function delete(User $user, ImportLog $model)
    {
        return false;
    }

    public function view(User $user, ImportLog $model)
    {
        return false;
    }
}