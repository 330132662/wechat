<?php namespace App\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;

/**
 * Class CorporationRepository
 * @package App\Repositories
 */
class CorporationRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'App\Models\Corporation';
    }
}