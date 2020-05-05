<?php

namespace App\Repositories\User;

use App\User;
use App\Rechage;
use App\Setting;
use App\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Validator;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{

    protected $successStatus = 200;
    protected $errorStatus = 401;
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\User::class;
    }

    public function validator($request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'token_device' => 'required',
        ]);
        if ($validator->fails()) {
           return false;
        }

        $phoneValidate = $request->phone;
        $userPhone = User::where('phone', $phoneValidate)->first();
        if ($userPhone){
            return false;
        }
        return true;
    }

    public function createUser($request){
        $input = $request->all();
        $input['is_admin'] = 0;
        $input['role_id'] = 3;

        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        return response()->json($success, $this->successStatus);
    }

    public function userLogin($request){
       
        $user = User::where('phone', $request->phone)->where('role_id', 3)->first();
        if (isset($user)){
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            if($request->token_device !== $user->token_device){
                $user->update(['token_device' => $request->token_device]);
            }
            return response()->json($success, $this->successStatus);
        }
        return response()->json(['error' => 'Unauthorised'], 401);
    }

    public function userUpdate($request){
        $input = $request->all();
        if($request->birthday){
            $input['birthday'] = date('Y-m-d', strtotime($input['birthday']));
            $user = $this->update(auth('api')->id(), $input);
            return response()->json($user, $this->successStatus);
        }
        $user = $this->update(Auth::id(), $input);
        return response()->json($user, $this->successStatus);
    }
//web
    public function rechangeUserAndCreateNotification($id, $request){
        $user = User::find($id);
        $setting = Setting::find(1);

        if($request->money_discount != null){
            $rechageData['money_discount'] = $request->money_discount;
            $user->wallet = $user->wallet + $request->money_discount;
        }
        if($request->point_discount != null){
            if($request->point_discount > $user->point){
                session(['error' => 'Điểm thưởng không hợp lệ']);
                return redirect(route('customer.info', ['id' => $id]));
            }

            $rechageData['point_discount'] = $request->point_discount;
            $user->point = $user->point - $request->point_discount;
            $user->wallet = $user->wallet + $request->point_discount * 1000;
        }
        $user->wallet = $user->wallet + $request->money;
        $user->save();
        $message = 'Quí khách đã nạp thành công ' .  number_format($request->money) . ' VNĐ vào tài khoản.';

        $notifiData['title'] = 'Tài khoản ' . $user->name;
        $notifiData['body'] = $message;
        $notifiData['created_at'] =  Carbon::now('Asia/Ho_Chi_Minh');
        $notifiData['type_notifi'] = 'naptien';
        $notifiData['customer_id'] = $id;
        $notifi = Notification::create($notifiData);

        $dataMessage = [
            'title' => 'Thông báo số dư',
            'body' => $message,

        ];

        $rechageData['customer_id'] = $id;
        $rechageData['price'] = $request->money;
        $rechageData['created_by'] = auth()->user()->name;
        $rechageData['created_at'] = Carbon::now('Asia/Ho_Chi_Minh');
        $rechageData['store_code'] = auth()->user()->store_code;
        $reg = Rechage::create($rechageData);
        if($user->token_device == null){
            Session::put('success', 'Nạp thành công ' . number_format($request->money) . ' vào tài khoản khách hàng ' . $user->name . '.');
            return redirect(route('customer.info', ['id' => $id]));
        }
        $mess = $this->send($user->token_device, $dataMessage);
        Session::put('success', 'Nạp thành công ' . number_format($request->money) . ' vào tài khoản khách hàng ' . $user->name . '.');
        return redirect(route('customer.info', ['id' => $id]));
    }

    public function send($to = "", $data = array())
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = "AIzaSyB7ZLUj7bIfiAWHStCpKVDfguewapUloX0";
        $fields = array('to' => $to, 'data' => $data);
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $serverKey
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: '  .  curl_error($ch));
        }
        curl_close($ch);
        return json_decode($result, true);
    }

    public function searchUser($request){
        if ($request->ajax()) {
            $output = '';
            $users = User::where('phone', 'LIKE', '%' . $request->search . '%')->get();

            foreach ($users as $key => $item) {
                $key = $key + 1;
                $output .= '
                            <tr>
                                <th scope="row">' . $item->id . '</th>
                                <td>' . $item->name . '</th>
                                <td>' . $item->phone . '</td>

                                <td>'
                                    .  $item->point .  ' Point
                                </td>
                                <td>' . number_format($item->wallet) . ' VNĐ' . '</td>
                                <td>
                                <a class="btn btn-info btn-circle" href="' .  route('customer.info', ['id' => $item->id])  . '"
                                title="Xem thông tin khách hàng">
                                <i class="fas fa-info-circle"></i>
                            </a>
                                </td>
                        </tr>
                            ';
            }
            return Response($output);
        }
    }

    public function infoUser($id){
        $user = User::find($id);
        $rechages = Rechage::where('customer_id', $id)->orderby('created_at', 'desc')->get();

        return view('backend.customer.info', compact('user', 'rechages'));
    }

    public function tong($x, $y){
        return $x + $y;
    }
}
