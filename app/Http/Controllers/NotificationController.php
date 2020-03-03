<?php

namespace App\Http\Controllers;

use App\User;
use App\Notification;
use Carbon;
use Illuminate\Http\Request;
use App\Repositories\Order\OrderRepositoryInterface;
class NotificationController extends Controller
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository){
        $this->orderRepository = $orderRepository;
    }

    public function sendAll(){
        return view('backend.notification.sendalldevice');
    }

    public function postSendAll(Request $request){
        $token_devices = User::select('token_device')->where('token_device', '!=', null)->get()->toArray();
        $message = [
            'title' => $request->title,
            'body' => $request->body
        ];

        $notification = $this->sendMultipleNotifications($token_devices, $message);
     
        if($notification){
            Notification::create([
                'title' => $request->title,
                'body' => $request->body,
                'type_notifi' => 'common',
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh')  
            ]);
            session(['success' => 'Gửi thông báo thành công']);
            return redirect(route('notification.sendall'));
        }
        if($notification == null){
            session(['error' => 'Gửi thất bại']);
            return redirect(route('notification.sendall'));
        }
        
    }

    public function sendMultipleNotifications($to = "", $data = array()){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = "AIzaSyB7ZLUj7bIfiAWHStCpKVDfguewapUloX0";

        $fields = array('registration_ids' => $to, 'data' => $data);
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
            return null;
        }
        curl_close($ch);
        return json_decode($result, true);
    }
}
