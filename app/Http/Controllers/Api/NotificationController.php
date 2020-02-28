<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notification;
class NotificationController extends Controller
{
    public function index(){
        $data = Notification::where('customer_id', auth('api')->id())->orderBy('created_at', 'desc')->get();
        return response()->json($data, 200);
    }
    public function check_read(Request $request){
        $notifi = Notification::find($request->id);
        $notifi->update(['check_read' => 1]);
        return response()->json(['success' => 'true'], 200);
    }
}
