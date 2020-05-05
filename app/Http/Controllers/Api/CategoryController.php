<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $successStatus = 200;
    protected $categoryReposotory;

    public function __construct(CategoryRepositoryInterface $categoryReposotory) {
        $this->categoryReposotory = $categoryReposotory;
    }

    public function index(){
        $categories = $this->categoryReposotory->getAll();
        return response()->json($categories, $this->successStatus);
    
    }
}
