<?php namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'App\Models\User';
    }




}