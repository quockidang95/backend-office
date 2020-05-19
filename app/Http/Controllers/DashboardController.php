<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $totalPriceForMonth = $this->getRevenueForMonth($now->month, $now->year);
        $totalPriceForYear = $this->getRevenueForYear();
        $revenueFor12Month = $this->getRevenueFor12Month();
        $diff_day = $this->doanh_thu_so_voi_ngay_hom_qua();
        return view('backend.dashboard.index', compact( 'totalPriceForYear',
                                                        'totalPriceForMonth',
                                                        'revenueFor12Month',
                                                        'diff_day'));
    }

    public function revenueMonth(Request $request){
        if ($request->ajax()) {
            $daysInMonth = Carbon::parse($request->dateselected)->daysInMonth;
            $data_day = [];
            $data_revenue = [];
            for($i = 1; $i <= $daysInMonth; $i++)
            {   
                if($i < 10){
                    $date = $request->dateselected . '-0' . $i;
                    $revenue_day = $this->getRevenueForDay($date);
                    array_push($data_day, '0' . $i);
                    array_push($data_revenue, $revenue_day);
                }else{
                    $date = $request->dateselected . '-' . $i;
                    $revenue_day = $this->getRevenueForDay($date);
                    array_push($data_day, '' . $i);
                    array_push($data_revenue, $revenue_day);
                }
            }

            return Response(['data_date' => $data_day, 'data_revenue' => $data_revenue]);
        }
    }
    //|--------------------------------------------------------------------------|
    // repo for dashboard controller 


    // daonh thu trong thang    
    public function getRevenueForMonth($month, $year){
        return Order::where('store_code', auth()->user()->store_code)
                        ->whereYear('order_date', '=',  $year)->where('status', 3)
                        ->whereRaw('MONTH(order_date) = ?', [$month])->get('price')->sum('price');
    }

    // doanh thu cua nam
    public function getRevenueForYear(){
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        return Order::where('store_code', auth()->user()->store_code)
                        ->whereYear('order_date', '=',  $now->year)->where('status', 3)
                        ->get('price')->sum('price');
    }

    // doanh thu tung thang.
    public function getRevenueFor12Month(){
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $moth = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $callback = function($item) use ($now){
            return $this->getRevenueForMonth($item, $now->year);
        };
        return array_map($callback, $moth);
    }

    public function doanh_thu_so_voi_ngay_hom_qua(){
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $curent_date = [
            'from' => $now->toDateString() . ' ' . '00:00:00',
            'to' => $now->toDateTimeString(),
        ];
        $diff = $now->subDay();
        $diff_date = [
            'from' => $diff->toDateString() . ' ' . '00:00:00',
            'to' => $diff->toDateTimeString()
        ];
        $revenue_crrent_date = Order::whereBetween('order_date', [$curent_date['from'], $curent_date['to']])
                                    ->where('store_code', auth()->user()->store_code)
                                    ->where('status', 3)->get('price')->sum('price');
        
        if ( $revenue_crrent_date == 0){
            return 0;
        }                           

        $revenue_diff_date = Order::whereBetween('order_date', [$diff_date['from'], $diff_date['to']])
                                    ->where('store_code', auth()->user()->store_code)
                                    ->where('status', 3)->get('price')->sum('price');
        
        return ($revenue_crrent_date - $revenue_diff_date) / $revenue_crrent_date * 100;

    }

    public function getRevenueForDay($day){
        $dt = Carbon::parse($day);
        
        return Order::whereBetween('order_date', [$dt->startOfDay()->format('Y-m-d H:i'), $dt->endOfDay()->format('Y-m-d H:i')])
                    ->where('store_code', auth()->user()->store_code)
                    ->where('status', 3)->get('price')->sum('price');
    }
}
