<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Feedback;
class AdminController extends Controller
{
    protected $adminRepository;
    public function __construct(UserRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        $admins  = User::where('is_admin', 1)->get();
        return view('backend.admin.index', compact('admins'));
    }

    public function store(Request $request){
        $input = $request->all();
        $admin = User::where('email', $input['email'])->first();
        if($admin){
            Session::put('error', 'Email đã tồn tại');
            return Redirect::to(route('admin.index'));
        }
        $validator = Validator::make($request->all(), [
            'c_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            Session::put('error', 'Mật khẩu không khớp');
            return Redirect::to(route('admin.index'));
        }
        $input['password'] = bcrypt($input['password']);
        $input['is_admin'] = 1;
        $input['role_id'] = 2;
        $input['status'] = 1;
        $user = User::create($input);

        Session::put('success', 'Tạo tài khoảng thành công');
        return redirect()->route('admin.index');
    }
    
    public function delete($id){
        $user = $this->adminRepository->find($id);
        $user->status = 0;
        $user->save();
        Session::put('success', 'Xóa tài khoảng thành công');
        return redirect()->route('admin.index');
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'c_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            Session::put('error', 'Mật khẩu không khớp');
            return Redirect::to(route('admin.index'));
        }
        $input['password'] = bcrypt($request->password);
        $this->adminRepository->update($id, $input);
        Session::put('success', 'Đổi mật khẩu thành công');
        return redirect()->route('admin.index');
    }

    public function feedback(){
        $feedbacks = Feedback::all();
        return view('backend.admin.feedback', compact('feedbacks'));

    }
}
