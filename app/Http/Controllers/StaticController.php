<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShiftWork;
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
}
