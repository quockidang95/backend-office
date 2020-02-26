<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notification;
class NotificationController extends Controller
{
    public function index(){
        $user = auth('api')->id();

        $data = Notification::where('customer_id', $user)->orderBy('created_at', 'desc')->get();
        return response()->json($data, 200);
    }
    public function check_read(Request $request){
        $notifi = Notification::find($request->id);
        $notifi->check_read = 1;
        $notifi->save();

        return response()->json(['success' => 'true'], 200);
    }
}
