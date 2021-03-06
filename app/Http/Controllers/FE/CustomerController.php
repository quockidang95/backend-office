<?php
namespace App\Http\Controllers\FE;

use Cart;
use App\User;
use App\Order;
use App\Product;
use App\ProductRecipe;
use App\Recipe;
use App\Rechage;
use App\Setting;
use App\OrderItem;
use Carbon\Carbon;
use Pusher\Pusher;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class CustomerController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            return redirect('/')->withCookie(cookie('id', $user->id, 43000));
        }
        $phone = $request->phone;
        return view('frontend.register', compact('phone'));
    }

    public function register(Request $request)
    {
        $user = User::create([
            'phone' => $request->phone,
            'role_id' => 3,
            'is_admin' => 0,
            'name' => $request->name,
            'address' => $request->address
        ]);
        return redirect('/')->withCookie(cookie('id', $user->id, 43000));
        ;
    }

    public function logout()
    {
        session(['name' => null, 'id' => null]);
        Cookie::queue(Cookie::forget('id'));
        return redirect('/logincustomer');
    }

    public function gettable(Request $request)
    {
        if ($request->ajax()) {
            $data = json_decode($request->data);
            session(['store_code' => $data->ChiNhanh, 'table' => $data->SoBan]);
            $redirect = env('APP_URL') . '/cart/show';
            return Response($redirect);
        }
    }

    public function detailsProduct($id)
    {
        $product = Product::find($id);
        $recipe_ids = ProductRecipe::where('product_id', $id)->get('recipe_id');
        $recipes = Recipe::whereIn('id', $recipe_ids)->get();
        if ($recipes) {
            return view('frontend.productdetails', compact('product', 'recipes'));
        }
        return view('frontend.productdetails', compact('product'));
    }

    public function addProduct(Request $request)
    {
        $recipes = json_decode($request->p_recipe);
        if (count($recipes) > 0) {
            $temp_array = explode("k", (string) $request->p_id);
            $setting = Setting::find(1);
            $product_id = $temp_array[0];
            $product = Product::find($product_id);
            $size = '';
            if ($product->price == $request->p_price) {
                $size = 'M';
            } else {
                $size = 'L';
            }

            Cart::add([
                'id' => $request->p_id,
                'name' => $request->p_name,
                'qty' => $request->p_quantity,
                'price' => (int) $request->p_price,
                'weight' => 12,
                'options' => [
                    'size' => $size,
                    'recipe' => $request->p_recipe
                ],
            ]);
        } else {
            $temp_array = explode("k", (string) $request->p_id);
            $setting = Setting::find(1);
            $product_id = $temp_array[0];
            $product = Product::find($product_id);
            $size = '';
            if ($product->price == $request->p_price) {
                $size = 'M';
            } else {
                $size = 'L';
            }

            Cart::add([
                'id' => $request->p_id,
                'name' => $request->p_name,
                'qty' => $request->p_quantity,
                'price' => (int) $request->p_price,
                'weight' => 12,
                'options' => [
                    'size' => $size,
                    'recipe' => null,
                ],
            ]);
        }
        return redirect('/');
    }

    public function ShowCart()
    {
        $setting = Setting::find(1);
        
        if (session('store_code') == null) {
            return redirect('/json');
        }
        return view('frontend.showcart', compact('setting'));
    }

    public function ShowCartDelivery()
    {
        $setting = Setting::find(1);

        return view('frontend.showcartdelivery', compact('setting'));
    }

    public function DeleteCart($rowId)
    {
        Cart::update($rowId, 0);
        if (Cart::count() === 0) {
            return redirect('/');
        }
        return redirect(route('cart.show'));
    }

    public function checkout(Request $request)
    {
        //  dd($request);
        $id = $request->cookie('id');
        $cart_subtotal = Cart::subtotal();
        $temp = explode(".", $cart_subtotal);
        $temp1 = explode(",", $temp[0]);
        $price = $temp1[0] . $temp1[1];
        $total_price = intval($price);

        $setting = Setting::find(1);
       
        $is_pay;
        if ($request->payment_method == 1) {
            $is_pay = 1;
        } else {
            $is_pay = 2;
        }
        
        $point = $request->discount_price/$setting->discount_point;
        $order = new Order;
        $order->order_code = '#' . session('store_code') . time() . $id;
        $order->discount = $request->discount_promotion;
        $order->store_code = session('store_code');
        $order->table = strval(session('table'));
        $order->total_price = $total_price;
        $order->customer_id = $id;
        $order->order_here = 1;
        $order->note = $request->note;
        $order->payment_method = $request->payment_method;
        $order->price = $request->discount_price;
        $order->order_date = Carbon::now('Asia/Ho_Chi_Minh');
        $order->status = 1;
        $order->is_pay = $is_pay;
        $order->save();

        $contents = Cart::content();
        foreach ($contents as $key => $value) {
            $product_id = explode('k', $value->id);
            $id = $product_id[0];
            if ($value->options->recipe == null) {
                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'price' => $product_id[1],
                    'quantity' => $value->qty,
                    'size' => $value->options->size
                ]);
            } else {
                $recipes = json_decode($value->options->recipe);
                $output = '';
                foreach ((array)$recipes as $key => $recipe) {
                    $output .= $recipe->name . ': ' . $recipe->value . '%. ';
                }
                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'price' => $product_id[1],
                    'quantity' => $value->qty,
                    'size' => $value->options->size,
                    'recipe' => $output
                ]);
            }
        }

        $data['store_code'] = session('store_code');
        $data['table'] = session('table');
        $data['id'] = $order->id;
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $pusher->trigger('Notify', 'send-message', $data);
        Cart::destroy();
        session(['store_code' => null, 'table' => null]);
        return view('frontend.success');
    }

    public function checkoutdelivery(Request $request)
    {
        $id = $request->cookie('id');
        $cart_subtotal = Cart::subtotal();
        $temp = explode(".", $cart_subtotal);
        $temp1 = explode(",", $temp[0]);
        $price = $temp1[0] . $temp1[1];
        $total_price = intval($price);

        $is_pay;
        if ($request->payment_method == 1) {
            $is_pay = 1;
        } else {
            $is_pay = 2;
        }

        $setting = Setting::find(1);
        $order = new Order;
        $order->order_code = '#' . time() . $id;
        $order->store_code = 'CH53MT';
        $order->total_price = $total_price;
        $order->customer_id = $id;
        $order->order_here = 3;
        $order->note = $request->note;
        $order->address = $request->address;
        $order->discount = $request->discount_promotion;
        $order->payment_method = $request->payment_method;
        $order->price = $request->discount_price;
        $order->order_date = Carbon::now('Asia/Ho_Chi_Minh');
        $order->status = 1;
        $order->is_pay = $is_pay;
        $order->save();

        $contents = Cart::content();
        foreach ($contents as $key => $value) {
            $product_id = explode('k', $value->id);
            $id = $product_id[0];
            if ($value->options->recipe == null) {
                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'price' => $product_id[1],
                    'quantity' => $value->qty,
                    'size' => $value->options->size
                ]);
            } else {
                $recipes = json_decode($value->options->recipe);
                $output = '';
                foreach ((array)$recipes as $key => $recipe) {
                    $output .= $recipe->name . ': ' . $recipe->value . '%. ';
                }
                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'price' => $product_id[1],
                    'quantity' => $value->qty,
                    'size' => $value->options->size,
                    'recipe' => $output
                ]);
            }
        }
  
        $data['store_code'] = 'CH53MT';
        $data['table'] = session('table');
        $data['id'] = $order->id;
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );
      
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $pusher->trigger('Notify', 'send-message', $data);
        Cart::destroy();
        session(['store_code' => null, 'table' => null]);
        return view('frontend.success');
    }

    public function notification(Request $request)
    {
        $id = intval($request->cookie('id'));
        $rechages = Rechage::where('customer_id', $id)->orderBy('created_at', 'desc')->get();

        return view('frontend.notification', compact('rechages'));
    }

    public function profiler(Request $request)
    {
        $id = $request->cookie('id');
        $user = User::find(intval($id));
        
        return view('frontend.profiler', compact('user'));
    }

    public function orderhere(Request $request)
    {
        $order = Order::where('customer_id', $request->cookie('id'))->orderby('order_date', 'desc')->first();
        return view('frontend.order', compact('order'));
    }
}
