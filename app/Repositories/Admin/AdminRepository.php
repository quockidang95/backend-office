<?php

namespace App\Repositories\Admin;

use App\Repositories\EloquentRepository;
use App\Repositories\Admin\AdminRepositoryInterface;

class AdminRepository extends EloquentRepository implements AdminRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Admin::class;
    }

}
