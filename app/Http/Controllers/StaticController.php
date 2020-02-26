<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShiftWork;
use App\Order;
use App\OrderItem;
use DB;
use Excel;
use App\Exports\OrderExport;


class StaticController extends Controller{
    public function doanhthutheongay(){
        return view('backend.static.doanhthutheongay');
    }

    public function laydoanhthutheongay(Request $request){
        if($request->ajax()){
            $output = '';
            $shiftworks = Shiftwork::where('created_at', 'LIKE', '%' . $request->dateselected . '%')
                                    ->where('store_code', $request->storecode)->get();
            $totalPrice = 0;
            foreach ($shiftworks as $shift){
               $output .='
                <tr>
                    <th class="align-middle" scope="row">' . $shift->name_shift . '</th>
                    <td class="align-middle">' . $shift->name_admin . '</td>
                    <td class="align-middle">' . number_format($shift->price_box) . ' VNĐ' . '</td>
                </tr>
               ';
           }
           $orders = Order::where('order_date', 'LIKE', '%' . $request->dateselected . '%')->where('status', 3)->get();
           $data = array('shiftwork' => $output, 'orders' => $orders);
           return json_encode($data);
        }
    }

    public function doanhthutheothang(){
        return view('backend.static.doanhthutheothang');
    }

    public function laydoanhthutheothang(Request $request){
        $orders = Order::where('store_code', $request->storecode)->where('status', 3)
                ->where('order_date', 'LIKE', '%' .$request->dateselected . '%')->get();
        $totalPrice = 0;
        $output = '';
       foreach ($orders as $shift){
           $output .='
            <tr>
                <th class="align-middle" scope="row">' . $shift->table . '</th>
                <td class="align-middle">' . $shift->customer_id . '</td>
                <td class="align-middle">' . number_format($shift->price) . ' VNĐ' . '</td>
            </tr>
           ';
           $totalPrice = $totalPrice + $shift->price;
       }
       $data = array(['a' => $output, 'b' => $totalPrice]);
       
       return json_encode($data);
    }

    public function exportMoth(Request $request){
        $orders = Order::where('store_code', auth()->user()->store_code)->where('status', 3)
        ->where('order_date', 'LIKE', '%' .$request->date_selected . '%')->select('id')->get()->toArray();
       $order_items = OrderItem::whereIn('order_id', $orders)->get();
      
       foreach ($order_items as $order_item){
           $order_item['product_name'] = $order_item->product->name;
           $order_item['product_code'] = $order_item->product->product_code;
           $order_item['total_price'] = $order_item->quantity * $order_item->price;
           unset($order_item['id']);
           unset($order_item['product_id']);
           unset($order_item['order_id']);
           unset($order_item['created_at']);
           unset($order_item['updated_at']);
           unset($order_item['size']);
           unset($order_item['recipe']);
           unset($order_item['product']);
       }
       return Excel::download( new OrderExport($order_items->toArray()), auth()->user()->store_code . $request->date_selected .'.xls');
    }
}
