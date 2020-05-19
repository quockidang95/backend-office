<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use DateTime;
use App\Order;
use App\Product;
use App\OrderItem;
use App\ShiftWork;
use Carbon\Carbon;
use App\Exports\OrderExport;
use Illuminate\Http\Request;


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
                    <td class="align-middle">' . number_format($shift->surplus_box) . '</td>
                    <td class="align-middle">' . number_format($shift->total_revenue) . '</td>
                    <td class="align-middle">' . number_format($shift->revenue_cash) . '</td>
                    <td class="align-middle">' . number_format($shift->revenue_online) . '</td>
                    <td class="align-middle">' . number_format($shift->end_balance_shift) . '</td>
                </tr>
               ';
            }
            $orders = Order::whereBetween('order_date', [$form_date, $to_date])->where('store_code', $request->storecode)->where('status', 3)->get();
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
        $orders = Order::where('store_code', $request->storecode)
                        ->whereYear('order_date', '=',  $date[0])->where('status', 3)
                        ->whereRaw('MONTH(order_date) = ?', [$date[1]])->get();

        $subMonth = Carbon::create($request->dateselected)->subMonth();

        $revenue_diff_month = Order::where('store_code', $request->storecode)
                                    ->whereYear('order_date', '=',  $subMonth->year)->where('status', 3)
                                    ->whereRaw('MONTH(order_date) = ?', [$subMonth->month])->get('price')->sum('price');

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

        $diff_month = 0;

        if($totalPrice != 0)
        {
            $diff_month = ($totalPrice - $revenue_diff_month) / $totalPrice * 100;
        }
        
        $data = array(['a' => $output, 'b' => $totalPrice, 'diff_month' => $diff_month]);

        return json_encode($data);
    }

    public function exportMoth(Request $request)
    {
        $date = explode("-", (string) $request->date_selected);
        $product_ids = Product::where('is_report', 1)->get('id')->toArray();
        $order_ids = Order::where('store_code', $request->store_code)->where('status', 3)
            ->whereYear('order_date', '=',  $date[0])->whereRaw('MONTH(order_date) = ?', [$date[1]])->select('id')->get()->toArray();
        $order_items = OrderItem::whereIn('order_id', $order_ids)->whereIn('product_id', $product_ids)->get();

        $data = array();
        foreach ($order_items as $order_item) {
            if ($order_item->product->is_report === 1) {
                
                $item['created_at'] = (new DateTime(Order::find($order_item->order_id)->order_date))->format('d-m-Y');
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

    public function exportDay (Request $request){
        $form_date = $request->date_selected . ' 00:00:00';
        $to_date = $request->date_selected . ' 23:59:59';
        $product_ids = Product::where('is_report', 1)->get('id')->toArray();
        $order_ids = Order::where('store_code', $request->store_code)
                        ->whereBetween('order_date', [$form_date, $to_date])
                        ->where('status', 3)->select('id')->get()->toArray();
    
        $order_items = OrderItem::whereIn('order_id', $order_ids)->whereIn('product_id', $product_ids)->get();
        $data = array();
        foreach ($order_items as $order_item){               
                $item['created_at'] = (new DateTime(Order::find($order_item->order_id)->order_date))->format('d-m-Y');
                $item['product_name'] = $order_item->product->name;
                $item['product_code'] = $order_item->product->product_code;
                $item['total_price'] = $order_item->quantity * $order_item->price;
                $item['quantity'] = $order_item->quantity;
                $item['price'] = $order_item->price;
                $item['dvt'] = 'Ly';
                array_push($data, $item);
        }

        return Excel::download(new OrderExport($data), 'BestReportView' . auth()->user()->store_code . $request->date_selected . '.xls');
    }

    public function doanhthutheotuan(){
        return view('backend.static.doanhthutheotuan');
    }

    public function laydoanhthutheotuan (Request $request) {
        if($request->ajax())
        {
            $weekSelected = Carbon::create($request->dateselected);

            $revenueWeek = Order::where('store_code', $request->storecode)
                            ->whereBetween('order_date', [$weekSelected->startOfWeek()->format('Y-m-d H:i'), $weekSelected->endOfWeek()->format('Y-m-d H:i')])
                            ->where('status', 3)->get('price')->sum('price');

            $diffWeekSelected = $weekSelected->subWeek();

            $revenueDiffWeek = Order::where('store_code', $request->storecode)
                                    ->whereBetween('order_date', [$diffWeekSelected->startOfWeek()->format('Y-m-d H:i'), $diffWeekSelected->endOfWeek()->format('Y-m-d H:i')])
                                    ->where('status', 3)->get('price')->sum('price');
            
            $diff_week = 0;
            if($revenueWeek == 0)
            {
                $diff_week = 0;
            }else{
                $diff_week = ($revenueWeek - $revenueDiffWeek) / $revenueWeek * 100;
            }
            
            return Response(['revenueWeek' => $revenueWeek, 'diff_week' => $diff_week]);
        }
           // return Response($request->all());
    }

    public function daonhthutuychon(){

        return view('backend.static.doanhthutuychon');
    }

    public function laydoanhutuychon (Request $request) {
        if ( $request->ajax()) {

            $start_date = Carbon::create($request->start_date)->startOfDay();

            $end_date = Carbon::create($request->end_date)->endOfDay();

            $revenueDate = Order::where('store_code', $request->store_code)
                                    ->whereBetween('order_date', [$start_date->format('Y-m-d H:i'), $end_date->format('Y-m-d H:i')])
                                    ->where('status', 3)->get('price')->sum('price');
            return Response(['revenueDate' => $revenueDate]);
            
        }
    }
}
