<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use App\Rechage;
use App\Feedback;
class UserController extends Controller
{

   protected $successStatus = 200;
   protected $errorStatus = 401;
    protected $userReposotory;

    public function __construct(UserRepositoryInterface $userReposotory){
        $this->userReposotory = $userReposotory;
    }

    public function store(Request $request){
       $validate =  $this->userReposotory->validator($request);
       if($validate == false){
        return response()->json(['error' => 'unauthorize'], $this->errorStatus);
       }
       return $this->userReposotory->createUser($request);
    }

    public function login(Request $request){
       return $this->userReposotory->userLogin($request);
    }

    public function details(){
        $user = auth('api')->user();
        return response()->json($user, $this->successStatus);
    }

    public function update(Request $request){
        return $this->userReposotory->userUpdate($request);
    }

    public function getkey(){
        $userID = auth('api')->user()->getKey();
        return response()->json($userID, 200);
    }

    public function rechage(){
        $userID = auth('api')->id();
        $data = Rechage::where('customer_id', $userID)->orderBy('created_at', 'desc')->get();
        return response()->json($data, 200);
    }

    public function logout(){
        $user = auth('api')->user();
        $user->token_device = null;
        $user->save();

        return response()->json('success', 200);
    }

    public function feedback(Request $request){
        $customer_id = auth('api')->id();
        $input['customer_id']= $customer_id;
        $input['body'] = $request->body;
        $input['created_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        $fb = Feedback::create($input);

        return response()->json($fb, 200);
    }
}
