<?php

namespace App\Http\Controllers\Api;

use App\Recipe;
use App\Product;
use App\ProductRecipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function GuzzleHttp\json_decode;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;

class ProductController extends Controller
{
    protected $successStatus = 200;
    protected $productReposotory;
    protected $categoryRepository;
    public function __construct(ProductRepositoryInterface $productReposotory, CategoryRepositoryInterface $categoryRepository) {
        $this->productReposotory = $productReposotory;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(){
        $products = $this->productReposotory->getAll();
        return response()->json($products, $this->successStatus);
    }

    public function GetProductByCategory($id){
        $category = $this->categoryRepository->find($id);
        $products = $category->products;

        foreach($products as $product){
          //  $product = Product::find($id);
            $recipe_ids = ProductRecipe::where('product_id', $product->id)->get('recipe_id');
            $recipes = Recipe::whereIn('id', $recipe_ids)->get();
            $product['recipe'] = $recipes;
        }

        return response()->json($products, $this->successStatus);
    }

    public function GetProductById($id){
        //return response()->json($this->productReposotory->find_1($id), $this->successStatus);

        return $this->productReposotory->find_1($id);
    }



    public function jsondecode(Request $request){

        $string = $request->json;
        $array = json_decode($string);
        if(gettype($array) === "array"){
           return  response()->json($array);
        }

        return response()->json('error');
    }
}
