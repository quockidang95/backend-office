<?php

namespace App\Http\Controllers;

use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Product;
use App\Category;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Recipe;
use App\ProductRecipe;
class ProductController extends Controller
{
    protected $productReposotory;

    public function __construct(ProductRepositoryInterface $productReposotory)
    {
        $this->productReposotory = $productReposotory;
    }

    public function index()
    {
        return $this->productReposotory->getAllPaginate();
    }

    public function add(Request $req)
    {
        return $this->productReposotory->addProduct($req);
    }
    public function delete($id)
    {
        return $this->productReposotory->disableProduct($id);

    }
    public function update(Request $req, $id)
    {

        return $this->productReposotory->updateProduct($req, $id);
    }

    public function viewupdate($id)
    {
        $item = Product::find($id);
        $recipes = Recipe::all();
        $product_recipe = ProductRecipe::select('recipe_id')->where('product_id', $id)->get();
        $product_recipe = Recipe::whereIn('id', $product_recipe)->get();
       
        return view('backend.product.update', compact('item', 'recipes', 'product_recipe'));
    }

    public function search(Request $request)
    {
        return $this->productReposotory->findProductByName($request);
    }

    public function getAllByCategory($id){
        $products = Product::where('category_id', $id)->where('status', 1)->get();
        $category = Category::find($id);
        return view('backend.product.bycategory', compact('products', 'category'));
    }

    public function indexRecipe(){
        $recipes = Recipe::all();
        return view('backend.product.indexRecipe', compact('recipes'));
    }

    public function storeRecipe(){
        return view('backend.product.storeRecipe');
    }

    public function postStoreRecipe(Request $request){
        $Recipe = Recipe::create($request->all());
        return redirect(route('recipe.index'));
    }
}
