<?php

namespace App\Repositories\Store;

use App\Repositories\EloquentRepository;
use App\Repositories\Store\StoreRepositoryInterface;

class StoreRepository extends EloquentRepository implements StoreRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Store::class;
    }

}
