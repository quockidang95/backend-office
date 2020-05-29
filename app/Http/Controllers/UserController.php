<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Rechage;
use Carbon\Carbon;
use App\Notification;
use Illuminate\Support\Arr;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Repositories\User\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index(){
        $users = User::where('is_admin', 0)->orderBy('point', 'desc')->paginate(10);
        Session::put('success', 'Load danh sách khách hàng thành công');
        return view('backend.customer.index', compact('users'));
    }

    public function naptien($id, Request $request){
        return  $this->userRepository->rechangeUserAndCreateNotification($id, $request);
    }

    public function search(Request $request){
       return $this->userRepository->searchUser($request);
    }

    public function info($id){
      return $this->userRepository->infoUser($id);
    }

    public function viewstore(){
        return view('backend.customer.store');
    }

    public function store(Request $request){
        $user = User::where('phone', $request->phone)->first();
        if($user){
            Session::put('error', 'Số điện thoại này đã tồn tại trong hệ thống');
            return redirect()->route('customer.index');
        }
        $input = $request->all();

        $input['is_admin'] = 0;
        $input['role_id'] = 3;
        User::create($input);
        Session::put('success', 'Thêm mới tài khoản thành công');
        return redirect()->route('customer.index');
    }

    public function getBirthdayForMonth(Request $request)
    {
        $date = Carbon::create($request->dateselected);
        $users = User::where('birthday', '!=', null)->get();

        $userBirthdays = $users->filter( function ($user) use ($request){
           return $this->checkBirthday($user->birthday, $request) === true;
        });

    
        return Response($userBirthdays);
    }

    public function getCustomerDear(Request $request){
        
        if($request->ajax())
        {
            $dt = Carbon::now('Asia/Ho_Chi_Minh');
            $start = $dt->startOfMonth()->format('Y-m-d H:i:s');
            $end = $dt->endOfMonth()->format('Y-m-d H:i:s');

            $customerIds = Order::whereBetween('order_date', [ $start, $end ])
                                ->where('status', 3)->get();
            $arr = [];
            foreach ($customerIds as $id){
                array_push($arr, $id->customer_id);
            }
           
            $res = array_count_values($arr);
            arsort($res);
            $customers = [];
            foreach ($res as $key => $value){
                $user = User::find($key);

                $user['count'] = $value;
                $user['total_price'] = $user->orders->sum->price;
                unset($user['orders']);
                array_push($customers, $user);
            }
           // dd($customer);
            return Response($customers);

        }
    }


    // repositories for controllers
    public function checkBirthday( $birthday, $request) 
    {
        $daysInMonth = Carbon::create($request->dateselected)->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            if($i < 10){
                if(Carbon::parse($birthday)->isBirthday(Carbon::parse($request->dateselected . '-0' . $i)))
                return true;
            }else{
                if(Carbon::parse($birthday)->isBirthday(Carbon::parse($request->dateselected . '-' . $i)))
                return true;
            }
            
        }
        return false;
    }
}

?>
