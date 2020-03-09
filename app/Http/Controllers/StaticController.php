<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShiftWork;
use App\Order;
use App\OrderItem;
use DB;
use Excel;
use App\Exports\OrderExport;


class StaticController extends Controller
{
    public function doanhthutheongay()
    {
        return view('backend.static.doanhthutheongay');
    }

    public function laydoanhthutheongay(Request $request)
    {
        if ($request->ajax()) {
            $form_date = $request->dateselected . ' 00:00:00';
            $to_date = $request->dateselected . ' 23:59:59';
            $output = '';
            $shiftworks = Shiftwork::whereBetween('created_at', [$form_date, $to_date])
                ->where('store_code', $request->storecode)->get();
            $totalPrice = 0;
            foreach ($shiftworks as $shift) {
                $output .= '
                <tr>
                    <th class="align-middle" scope="row">' . $shift->name_shift . '</th>
                    <td class="align-middle">' . $shift->name_admin . '</td>
                    <td class="align-middle">' . $shift->type_shift . '</td>
                    <td class="align-middle">' . $shift->total_revenue . '</td>
                    <td class="align-middle">' . $shift->revenue_cash . '</td>
                    <td class="align-middle">' . $shift->revenue_online . '</td>
                </tr>
               ';
            }
            $orders = Order::whereBetween('order_date', [$form_date, $to_date])->where('status', 3)->get();
            $data = array('shiftwork' => $output, 'orders' => $orders);
            return json_encode($data);
        }
    }

    public function doanhthutheothang()
    {
        return view('backend.static.doanhthutheothang');
    }

    public function laydoanhthutheothang(Request $request)
    {
        $date = explode("-", (string) $request->dateselected);
        $orders = Order::whereYear('order_date', '=',  $date[0])->whereRaw('MONTH(order_date) = ?', [$date[1]])->get();
        
        $totalPrice = 0;
        $output = '';
        foreach ($orders as $shift) {
            $output .= '
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

    public function exportMoth(Request $request)
    {
        $date = explode("-", (string) $request->date_selected);

        $order_ids = Order::where('store_code', auth()->user()->store_code)->where('status', 3)
            ->whereYear('order_date', '=',  $date[0])->whereRaw('MONTH(order_date) = ?', [$date[1]])->select('id')->get()->toArray();
        $order_items = OrderItem::whereIn('order_id', $order_ids)->get();
        $data = array();
        foreach ($order_items as $order_item) {
            if ($order_item->product->is_report === 1) {
                $item['created_at'] = $order_item->created_at;
                $item['product_name'] = $order_item->product->name;
                $item['product_code'] = $order_item->product->product_code;
                $item['total_price'] = $order_item->quantity * $order_item->price;
                $item['quantity'] = $order_item->quantity;
                $item['price'] = $order_item->price;
                $item['dvt'] = 'Ly';
                
                array_push($data, $item);
            }
        }
        return Excel::download(new OrderExport($data), 'BestReportView' . auth()->user()->store_code . $request->date_selected . '.xls');
    }
}
