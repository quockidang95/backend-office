<?php

namespace App\Http\Controllers\Api;

use DB;
use App\User;
use App\Order;
use Exception;
use App\Product;
use App\Setting;
use App\OrderItem;
use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use function GuzzleHttp\json_decode;

class OrderController extends Controller
{
    protected $successStatusCode = 200;

    public function order(Request $request)
    {
        DB::beginTransaction();

        $user = auth('api')->user();
        $setting = Setting::find(1);
        
        $price = isset($request->price) ? $request->price
            : ($request->total_price - ($request->total_price * $setting->discount_user/100));
        
        $is_pay = ($request->payment_method == 1) ?  1 : 2;
    
        try {
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
                $recipes = isset($product->recipe) ? $product->recipe : [];
    
                $output = null;
                if ($recipes != []) {
                    foreach ($recipes as $recipe) {
                        $output .= $recipe->name . ': ' . $recipe->value . '%. ';
                    }
                }
    
                $priceProduct = ($product->price != 0) ? $product->price : $product->price_L;
    
                $size = ($product->price != 0) ? 'M' : 'L';
    
                $item = OrderItem::create([
                    'recipe' => $output,
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'price' => $priceProduct,
                    'size' => $size,
                    'quantity' => $product->slChon,
                ]);
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
            DB::commit();
            return response()->json($order, 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $ex->getMessage()], 500);
        }
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
        DB::beginTransaction();

        try {
            $setting = Setting::find(1);
            $price = isset($request->price) ? $request->price
                        : ($request->total_price - ($request->total_price * $setting->discount_user/100));
    
            $is_pay = ($request->payment_method == 1) ?  1 : 2;
        
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
                $recipes = isset($product->recipe) ? $product->recipe : [];

                $output = null;
                if ($recipes != []) {
                    foreach ($recipes as $recipe) {
                        $output .= $recipe->name . ': ' . $recipe->value . '%. ';
                    }
                }

                $priceProduct = ($product->price != 0) ? $product->price : $product->price_L;

                $size = ($product->price != 0) ? 'M' : 'L';

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

            DB::commit();
            return response()->json($order, 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json($e->getMessage(), 400);
        }
    }
}
