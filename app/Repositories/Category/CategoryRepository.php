<?php

namespace App\Repositories\Category;

use App\Repositories\EloquentRepository;
use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryRepository extends EloquentRepository implements CategoryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Category::class;
    }

}
