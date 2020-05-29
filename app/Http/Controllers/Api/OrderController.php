<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\OrderItem;
use Illuminate\Support\Carbon;
use Pusher\Pusher;
use App\User;
use App\Product;
use App\Setting;
use function GuzzleHttp\json_decode;

class OrderController extends Controller
{
    protected $successStatusCode = 200;

    public function order(Request $request)
    {
        $user = auth('api')->user();
        $setting = Setting::find(1);

        $price = 0;
        if ($request->price) {
            $price = $request->price;
        } else {
            $price = $request->total_price - ($request->total_price * $setting->discount_user/100);
        }
        
        $is_pay;
        if ($request->payment_method == 1) {
            $is_pay = 1;
        } else {
            $is_pay = 2;
        }
        
        
        $order = Order::create([
            'order_code' => '#' . $request->store_code . time() . $user->id,
            'store_code' => $request->store_code,
            'table' => $request->table,
            'total_price' => $request->total_price,
            'customer_id' => $user->id,
            'order_here' => 1,
            'note' => $request->note,
            'payment_method' => $request->payment_method,
            'order_date' => Carbon::now('Asia/Ho_Chi_Minh'),
            'status' => 1,
            'price' => $price,
            'is_pay' => $is_pay
        ]);

        $products = json_decode($request->products);
      
        foreach ($products as $product) {
            if (isset($product->recipe)) {
                $output = '';
                foreach ($product->recipe as $recipe) {
                    $output .= $recipe->name . ': ' . $recipe->value . '%. ';
                }

                $priceProduct = 0;
                $size = '';
                if ($product->price != 0) {
                    $priceProduct = $product->price;
                    $size  = 'Vừa';
                } else {
                    $priceProduct = $product->price_L;
                    $size = 'Lớn';
                }

                $item = OrderItem::create([
                    'recipe' => $output,
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'price' => $price,
                    'size' => $size,
                    'quantity' => $product->slChon,
                ]);
            } else {
                $item = OrderItem::create([
                    'recipe' => $output,
                    'order_id' => $order->id,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'price' => $price,
                    'size' => $size,
                    'product_id' => $product->id,
                    'quantity' => $product->slChon,
                ]);
            }
        }

        $data['store_code'] = $request->store_code;
        $data['table'] = $request->table;
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
        return response()->json($order, 200);
    }
    
    public function historyorder()
    {
        $userid = auth('api')->user()->id;
        $order = Order::where('customer_id', $userid)->orderby('order_date', 'desc')->get();
        return response()->json($order, $this->successStatusCode);
    }

    public function historyorderdetails($id)
    {
        $order = Order::find($id);
        $listItem = $order->orderitems;
        $data = array();
        foreach ($listItem as $orderItem) {
            $product = Product::find($orderItem->product_id);

            $input['order_id'] = $order->id;
            $input['quantity'] = $orderItem->quantity;
            $input['price'] = $orderItem->price;
            $input['product_name'] = $product->name;
            $input['product_image'] = $product->image;

            array_push($data, $input);
        }
        return response()->json($data, $this->successStatusCode);
    }

    public function delivery(Request $request)
    {
        $setting = Setting::find(1);

        $price = 0;
        if ($request->price) {
            $price = $request->price;
        } else {
            $price = $request->total_price - ($request->total_price * $setting->discount_user/100);
        }

        $is_pay;
        if ($request->payment_method == 1) {
            $is_pay = 1;
        } else {
            $is_pay = 2;
        }
        
        $order = Order::create([
            'store_code' => $request->store_code,
            'total_price' => $request->total_price,
            'customer_id' => auth('api')->id(),
            'address' => $request->address,
            'order_here' => 3,
            'order_date' => Carbon::now('Asia/Ho_Chi_Minh'),
            'note' => $request->note,
            'payment_method' => $request->payment_method,
            'price' => $price,
            'order_code' => '#' . $request->store_code . time() . auth()->id(),
            'is_pay' => $is_pay,
        ]);

        $products = json_decode($request->products);
      
        foreach ($products as $product) {
            if (isset($product->recipe)) {
                $output = '';
                foreach ($product->recipe as $recipe) {
                    $output .= $recipe->name . ': ' . $recipe->value . '%. ';
                }

                $priceProduct = 0;
                $size = '';
                if ($product->price != 0) {
                    $priceProduct = $product->price;
                    $size  = 'Vừa';
                } else {
                    $priceProduct = $product->price_L;
                    $size = 'Lớn';
                }

                $item = OrderItem::create([
                    'recipe' => $output,
                    'order_id' => $order->id,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'price' => $price,
                    'size' => $size,
                    'product_id' => $product->id,
                    'quantity' => $product->slChon,
                ]);
            } else {
                $item = OrderItem::create([
                    'recipe' => $output,
                    'order_id' => $order->id,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'price' => $price,
                    'size' => $size,
                    'product_id' => $product->id,
                    'quantity' => $product->slChon,
                ]);
            }
        }

        $data['store_code'] = $request->store_code;
        $data['table'] = $request->table;
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
        return response()->json($order, 200);
    }
}
