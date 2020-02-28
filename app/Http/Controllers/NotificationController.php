<?php

namespace App\Http\Controllers;

use App\User;
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
       
        $message = array(
            'title' => $request->title,
            'body' => $request->body
        );
        $notification = $this->orderRepository->send($token_devices, $message);
       // dd($notification);
        if($notification){
            session(['success' => 'Gửi thông báo thành công']);
            return redirect(route('notification.sendall'));
        }
        if($notification == null){
            session(['error' => 'Gửi thất bại']);
            return redirect(route('notification.sendall'));
        }
        
    }
}
