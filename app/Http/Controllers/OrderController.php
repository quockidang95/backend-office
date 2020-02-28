<?php

namespace App\Http\Controllers;
use App\User;
use App\Order;
use Exception;
use App\Product;
use App\Rechage;
use App\Setting;
use App\OrderItem;
use App\ShiftWork;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;


class OrderController extends Controller
{
    protected $orderRepository;
    protected $userRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, UserRepositoryInterface $userRepository) {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        if(!session('price_box')){
            session(['price_box' => 0]);
        }
        $user = auth('web')->user();
        $date = date('Y-m-d');
        $orders = Order::where('created_at', 'LIKE', '%' . $date . '%')->whereIn('status', [1, 2])->where('store_code', $user->store_code)->orderby('created_at', 'desc')->get();
        return view('backend.order.index', compact('orders'));
    }
    public function orderdetails($id)
    {
        $order = $this->orderRepository->getOrderById($id);
        $orderItems = $this->orderRepository->getAllOrderItemByOrderId($id);
        $this->orderRepository->getDetailOrder($orderItems);

        return view('backend.order.detail', compact('order', 'orderItems'));
    }

    public function printBill($id)
    {
        $order = $this->orderRepository->getOrderById($id);
        $orderItems = $this->orderRepository->getAllOrderItemByOrderId($id);
        $setting = Setting::find(1);
        $this->orderRepository->printOrder($order, $orderItems);

        return view('backend.order.printbill', compact('order', 'orderItems', 'setting'));

    }

    public function revenue(){
       return $this->orderRepository->revenue();
    }

    public function success($order_id){
        $check_status_order = $this->orderRepository->checkOrder($order_id);
        if(!$check_status_order){
            session(['error' => 'Hãy tiếp nhận đơn hàng trước khi nào gì khác...!']);
            return redirect(route('order.details', ['id' => $order_id]));
        }

        $order = $this->orderRepository->find($order_id);
        $user = $this->userRepository->find($order->customer_id);
        $this->orderRepository->changeStatusAndCheckOutOrder($order, $user);

        $data_message = $this->orderRepository->createdNotificationSuccessOrder($order, $user);
        $mess = $this->orderRepository->send($user->token_device, $data_message);
        session(['success' => 'Đơn hàng này đã được xử lí thành công!']);
        return redirect(route('order.byday'));
    }

    public function next($id){
        $check_status_order = $this->orderRepository->checkOrder($id);
        if ($check_status_order){
            session(['error' => 'Đơn hàng này đã được tiếp nhận rồi...!']);
            return redirect(route('order.details', ['id' => $id]));
        }
        $this->orderRepository->changeStatusNextOrder($id);
        session(['success' => 'Tiếp nhận đơn hàng thành công...!']);
        return redirect(route('order.details', ['id' => $id]));
    }

    public function error($order_id){
        $check_status_order = $this->orderRepository->checkOrder($order_id);
        if(!$check_status_order){
            session(['error' => 'Hãy tiếp nhận đơn hàng trước khi nào gì khác...!']);
            return redirect(route('order.details', ['id' => $order_id]));
        }

        $order = $this->orderRepository->find($order_id);
        $user = $this->userRepository->find($order->customer_id);
        $this->orderRepository->changeStatusAndCancelOrder($order, $user);

        $data_message = $this->orderRepository->createdNotificationCancelOrder($order, $user);
        $mess = $this->orderRepository->send($user->token_device, $data_message);
        session(['success' => 'Đơn hàng này đã được xử lí thành công!']);
        return redirect(route('order.byday'));
    }

    public function vieworder($id){
       return $this->orderRepository->vieworder($id);
    }

    public function indexRechage(){
       return $this->orderRepository->indexRechage();
    }

    public function tesst($id){
        $order = Order::find($id);
        $setting = Setting::find(1);
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        switch ($order->store_code) {
            case 'CH53MT':
                $order->store_code = '53 Man Thiện 2, P. Hiệp Phú, Quận 9, TP HCM';
                break;
            case 'CH34TL2':
                $order->store_code = '34 Tân Lập 2, Hiệp Phú, Q9, TP.HCM';
                break;
            case 'CH102TH':
                $order->store_code = '102 Tân Hòa, Hiệp Phú, Q9, TP.HCM';
                break;
        }
        foreach ($orderItems as $item) {
            $product = Product::find($item->product_id);
            $item->product_id = $product;
        }
        return view('backend.order.test', compact('order', 'orderItems', 'setting'));
    }


    public function printproduct($id){
        $order = Order::find($id);
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        $user = User::where('id', $order->customer_id)->first();
        $name = $user->name;
        foreach ($orderItems as $item) {
            $product = Product::find($item->product_id);
            $item->product_id = $product;

            $recipe_arr = json_decode($item->recipe);
            $output = '';
            if($recipe_arr){
                foreach ($recipe_arr as $recipe) {
                    $output .= $recipe->name . ': ' . $recipe->value . '% , ';
                }
            }
            $item->recipe = $output;
        }
        return view('backend.order.product', compact('order', 'orderItems', 'name'));
    }

    public function shiftwork(Request $request){
       
        $input = $request->all();
        $input['created_at'] =  Carbon::now('Asia/Ho_Chi_Minh');
        $input['store_code'] = auth()->user()->store_code;
        ShiftWork::create($input);
        session(['price_box' => 0]);
        return redirect('order/index');
    }

}
