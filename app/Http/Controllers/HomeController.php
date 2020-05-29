<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\OrderItem;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Cart;
use function GuzzleHttp\json_encode;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->cookie('id') == null) {
            return redirect('/logincustomer');
        }

        $categories = Category::all();
        $product = Product::all();

        $data_array = [];
        foreach ($categories as $key => $value) {
            $object['category'] = $value;
            $object['list_product'] = $value->products;
            array_push($data_array, $object);
        }
        
        return view('home', compact('data_array', 'categories'));
    }

    public function login()
    {
        return view('frontend.login');
    }

    public function json()
    {
        return view('frontend.qrcode');
    }
}
