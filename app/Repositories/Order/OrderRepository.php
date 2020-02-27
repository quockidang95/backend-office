<?php
namespace App\Repositories\Order;

use App\User;
use App\Order;
use App\Product;
use App\Rechage;
use App\Setting;
use App\OrderItem;
use Carbon\Carbon;
use App\Notification;
use App\Repositories\EloquentRepository;
use App\Repositories\Order\OrderRepositoryInterface;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{

    public function getModel()
    {
        return \App\Order::class;
    }

    public function getOrderByID($orderID){
        return $this->find($orderID);
    }

    public function getOrderItemByID($orderItemID){
        return OrderItem::find($orderItemID);
    }

    public function getProductByID($productID){
        return Product::find($productID);
    }
    
    public function getAllOrderItemByOrderId($orderID){
        return OrderItem::where('order_id', $orderID)->get();
    }

    public function getDetailOrder(&$orderItems)
    {
        foreach ($orderItems as $item) {
            $product = Product::find($item->product_id);
            $item->product_id = $product;
            $recipe_arr = json_decode($item->recipe);
            $output = '';
            $item->recipe = $output;
        }
    }

    public function printOrder($order, &$orderItems)
    {
        switch ($order->store_code) {
            case 'CH53MT':
                $order->store_code = '53 Man Thiện 2, P. Hiệp Phú, Quận 9, TP HCM';
                break;
            case 'CH34TL2':
                $order->store_code = '34 Tân Lập 2, Hiệp Phú, Q9, TP.HCM';
                break;
            case 'CH102TH':
                $order->store_code = '102 Tân Hòa, Hiệp Phú, Q9, TP.HCM';
                break;
        }
        foreach ($orderItems as $item) {
            $product = Product::find($item->product_id);
            $item->product_id = $product;
            $recipe_arr = json_decode($item->recipe);
            $output = '';
            if($recipe_arr){
                foreach ($recipe_arr as $recipe) {
                    $output .= $recipe->name . ': ' . $recipe->value . '% , ';
                }
            }
            $item->recipe = $output;
        }
    }

    public function revenue(){
        $user = auth('web')->user();
        $date = date('Y-m-d');
        $orders = Order::where('order_date', 'LIKE', '%' . $date . '%')->whereIn('status', [3, 4])->where('store_code', $user->store_code)->orderby('order_date', 'desc')->get();
    
        $totalPrice = 0;
        $price = 0;
        $tienmat = 0;
        foreach($orders as $item){
            if($item->status == 3){
                $totalPrice= $totalPrice + $item->total_price;
                $price = $price + $item->price;
                if($item->payment_method == 2){
                    $tienmat = $tienmat + $item->price;
                }
            }
        }
        return view('backend.order.totalbyday', compact('totalPrice', 'orders', 'price', 'tienmat'));
    }

    public function checkOrder($orderID){
        $order = $this->find($orderID);
        if($order->status != 2){
            return false;
        }
        return true;
    }

    public function changeStatusAndCheckOutOrder($order, $user){
        $setting = Setting::find(1);
        if($order->payment_method == 1){ // dùng ví điện tử
            $order->update(['status' => 3, 'created_by' => auth()->user()->name]);
            $session_priceBox = session('price_box');
            $session_priceBox = $session_priceBox + $price;
            session(['price_box' => $session_priceBox]);
            $wallet =  $user->wallet - $order->price;
            $point = $user->point + $order->price/$setting->discount_point;
            $user->update(['wallet' => $wallet, 'point' => $point]);
        }else if($order->payment_method == 2){ // tien mat tai ban
            $order->update(['status' => 3, 'created_by' => auth()->user()->name]);
            $point = $user->point + $order->price/$setting->discount_point;
            $user->update(['point' => $point]);

            $session_priceBox = session('price_box');
            $session_priceBox = $session_priceBox + $price;
            session(['price_box' => $session_priceBox]);
        }
    }

    public function createdNotificationSuccessOrder($order, $user){
        $noti['title'] = 'Thông báo đơn hàng';
        $noti['body'] = 'Đơn hàng ' . $order->order_code . ' của Quí khách đã hoàn tất.';
        $noti['created_at'] =  Carbon::now('Asia/Ho_Chi_Minh');
        $noti['type_notifi'] = 'order';
        $noti['customer_id'] = $user->id;
        $noti['id'] = $order->id;

        $notifi = Notification::create($noti);
        $dataMess = [
            'title' => 'Thông báo đơn hàng',
            'body' => 'Đơn hàng ' . $order->order_code . ' của Quí khách đã hoàn tất.',
        ];
        return $dataMess;
    }
    public function changeStatusAndCancelOrder($order, $user){
        $setting = Setting::find(1);
        $price = $order->total_price - ($setting->discount_user * $order->total_price)/100;
        $order->update(['status' => 4, 'created_by' => auth()->user()->name, 'price' => $price]);
    }

    public function createdNotificationCancelOrder($order, $user){
        $noti['title'] = 'Thông báo đơn hàng';
        $noti['body'] = 'Đơn hàng ' . $order->order_code . ' của Quí khách bị hủy. Cám ơn quí khách đã sử dụng dịch vụ tại OFFICE COFFEE.';
        $noti['created_at'] =  Carbon::now('Asia/Ho_Chi_Minh');
        $noti['type_notifi'] = 'order';
        $noti['customer_id'] = $user->id;
        $notifi = Notification::create($noti);

        $dataMess = [
            'title' => 'Thông báo đơn hàng',
            'body' => 'Đơn hàng ' . $order->order_code . ' của Quí khách đã bị hủy. Cám ơn quí khách đã sử dụng dịch vụ tại OFFICE COFFEE',
        ];

        return $dataMess;
    }

    public function changeStatusNextOrder($orderID){
        $order = $this->find($orderID);
        $order->update(['status' => 2]);
    }

    public function send($to = "", $data = array())
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = "AIzaSyB7ZLUj7bIfiAWHStCpKVDfguewapUloX0";

        $fields = array('to' => $to, 'data' => $data, 'priority' => 'high');
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $serverKey
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: '  .  curl_error($ch));
        }
        curl_close($ch);
        return json_decode($result, true);
    }

    function vieworder($id){
        $order = Order::find($id);
        $orderItems = OrderItem::where('order_id', $id)->get();
        foreach ($orderItems as $item) {
            $product = Product::find($item->product_id);
            $item->product_id = $product;
        }
        return view('backend.order.vieworder', compact('order', 'orderItems'));
    }

    public function indexRechage(){
        $currentDate = date('Y-m-d');
        $rechages = Rechage::where('created_at', 'LIKE', '%' . $currentDate . '%')->where('store_code', auth()->user()->store_code)->orderby('created_at', 'desc')->get();
        $totalPrice = 0;
        foreach($rechages as $item){
            $totalPrice = $totalPrice + $item->price;
        }
        return view('backend.rechage.index', compact('rechages', 'totalPrice'));
    }
}
