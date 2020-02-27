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
        $order = new Order;

        $order->order_code = '#' . $request->store_code . time() . $user->id;
        $order->store_code = $request->store_code;
        $order->table = $request->table;
        $order->total_price = $request->total_price;
        $order->customer_id = $user->id;
        $order->order_here = 1;
        $order->note = $request->note;
        $order->payment_method = $request->payment_method;
        $order->order_date = Carbon::now('Asia/Ho_Chi_Minh');
        $order->status = 1;
        $order->price = $request->total_price - ($request->total_price * $setting->discount_user/100);
        $order->save();

        $products = json_decode($request->products);
        foreach ($products as $product) {
            $item = new OrderItem;
            $item->order_id = $order->id;
            $item->product_id = $product->id;
            if ($product->price != 0) {
                $item->price = $product->price;
                $item->size = 'Vừa';
            } else {
                $item->price = $product->price_L;
                $item->size = 'Lớn';
            }
            $item->quantity = $product->slChon;
            $item->save();
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
}
