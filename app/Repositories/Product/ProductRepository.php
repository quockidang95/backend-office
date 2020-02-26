<?php

namespace App\Repositories\Product;

use App\Product;
use App\Category;
use Illuminate\Support\Facades\Session;
use App\Repositories\EloquentRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use Redirect;
use App\ProductRecipe;
use App\Recipe;
class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Product::class;
    }

    public function getAllProductByCategory($id){
        return App\Product::where('category_id', $id)->where('status', 1)->get();
    }

    public function getAllPaginate(){
        $products = Product::where('status', 1) ->simplePaginate(5);
        $categories = Category::all();
        Session::put('success', 'Load danh sách sản phẩm thành công');
        return view('backend.product.index', compact('products'));
    }

    public function addProduct($req){
        $productAdd = new Product;
        $productAdd->category_id = $req->category_id;
        $productAdd->name = $req->name;
        $productAdd->description = $req->description;
        $productAdd->content = $req->content;
        $productAdd->price = $req->price;
        $productAdd->price_L = $req->price_L;
        $productAdd->promotion_price =  $req->promotion_price;

        $get_image = $req->file('image');
        $name_image = current(explode('.', $get_image->getClientOriginalName()));
        $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
        $get_image->move('source/images', $new_image);
        $productAdd->image = $new_image;
        $productAdd->save();
        Session::put('success', 'Add sản phẩm thành công');
        return Redirect::to('product/index');
    }
    function disableProduct($id){
        $product = Product::find($id);
        $product->status = 0;
        $product->save();
        Session::put('success', 'Khóa sản phẩm thành công');
        return Redirect::to('product/index');
    }

    function updateProduct($req, $id){
        $recipes = $req->input('recipe');
        $product_recipe = ProductRecipe::where('product_id', $id)->get();
        if(!$product_recipe){
            foreach($recipes as $recipe){
                ProductRecipe::create([
                    'product_id' => $id,
                    'recipe_id' => $recipe
                ]);
            }
        }else{
            foreach($product_recipe as $item){
                ProductRecipe::destroy($item->id);
            }

            foreach($recipes as $recipe){
                ProductRecipe::create([
                    'product_id' => $id,
                    'recipe_id' => $recipe
                ]);
            }
        }

        $productAdd = Product::find($id);
        $productAdd->category_id = $req->category_id;
        $productAdd->name = $req->name;
        $productAdd->price = $req->price;
        $productAdd->description = $req->description;
        $productAdd->content = $req->content;
        $productAdd->price = $req->price;
        $productAdd->price_L = $req->price_L;
        $productAdd->promotion_price =  $req->promotion_price;

        $get_image = $req->file('image');
        if ($get_image) {
            $name_image = current(explode('.', $get_image->getClientOriginalName()));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('source/images', $new_image);
            $productAdd->image = $new_image;
            $productAdd->save();
            Session::put('success', 'Update sản phẩm thành công');
            return Redirect::to('product/index');
        } else {
            $productAdd->save();
            Session::put('success', 'Update sản phẩm thành công');
            return Redirect::to('product/index');
        }
    }

    public function findProductByName($requset){
        if ($request->ajax()) {
            $output = '';
            $products = Product::where('name', 'LIKE', '%' . $request->search . '%')->get();

            foreach ($products as $key => $item) {
                $output .= '
                            <tr>
                                <th class="align-middle" scope="row">' . $item->id . '</th>
                                <td class="align-middle">' . $item->name . '</th>
                                <td class="align-middle">' . $item->category->name . '</td>

                                <td class="align-middle">
                                    <img src="source/images/' . $item->image . '" width="60" height="60">
                                </td>
                                <td class="align-middle">' . number_format($item->price) . ' VNĐ' . '</td>
                                <td class="align-middle">

                                    <a title="update product" href="' . route('product.viewupdate', ['id' => $item->id]) . '"
                                    class="btn btn-warning btn-circle">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <!-- Delete category -->
                                    <button class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteProduct' . $item->id . '"
                                        title="delete category">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="deleteProduct' . $item->id . '" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bạn có chắn chắn muốn xóa sản phẩm này?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <a href="' . route('product.delete', ['id' => $item->id]) . '"
                                                class="btn btn-primary">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end delete category -->
                        </td>
                        </tr>
                            ';
            }
            return Response($output);
        }
    }

    public function find_1($id){
        $product = Product::find($id);
        $recipe_ids = ProductRecipe::where('product_id', $id)->get('recipe_id');
        $recipes = Recipe::whereIn('id', $recipe_ids)->get();
        $product['recipe'] = $recipes;

        return response()->json($product, 200);
    }
}
