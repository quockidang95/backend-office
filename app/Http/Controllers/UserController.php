<?php

namespace App\Http\Controllers;

use App\User;
use App\Rechage;
use Carbon\Carbon;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Repositories\User\UserRepositoryInterface;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index(){
        $users = User::where('is_admin', 0)->paginate(10);
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
}

?>
