<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShiftWork;
use App\Order;
class StaticController extends Controller
{
    
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

               $totalPrice = $totalPrice + $shift->price_box;
           }

           $data = array(['a' => $output, 'b' => $totalPrice]);
           return json_encode($data);
        }
    }

    public function doanhthutheothang(){
        return view('backend.static.doanhthutheothang');
    }

    public function laydoanhthutheothang(Request $request){
        $orders = Order::where('store_code', $request->storecode)->where('status', 3)->whereMonth('order_date', $request->dateselected)->get();
        
        
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
}
