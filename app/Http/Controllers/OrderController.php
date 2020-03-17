<?php

namespace App\Http\Controllers;
use App\User;
use App\Order;
use Exception;
use Cart;
use App\Recipe;
use App\Product;
use App\Rechage;
use App\Setting;
use App\Category;
use App\OrderItem;
use App\ShiftWork;
use App\ProductRecipe;
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
        /*
        if(!session('price_box')){
            session(['price_box' => 0]);
        }*/

        if(!session('total_revenue')){
            session(['total_revenue' => 0]);
        }
        if(!session('revenue_cash')){
            session(['revenue_cash' => 0]);
        }
        if(!session('revenue_online')){
            session(['revenue_online' => 0]);
        }

        if(!session('end_balance_shift')){
            session(['end_balance_shift' => 0]);
        }
        $user = auth('web')->user();
        $date = date('Y-m-d');
        $orders = Order::where('created_at', 'LIKE', '%' . $date . '%')->whereIn('status', [1, 2])->where('store_code', $user->store_code)->orderby('created_at', 'desc')->get();
        return view('backend.order.index', compact('orders'));
    }

    public function surplus (Request $request){
        session(['surplus_box' => $request->surplus_box]);
        session(['shift_open' => Carbon::now('Asia/Ho_Chi_Minh')]);
        return redirect(route('order.byday'));
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
        }
        return view('backend.order.product', compact('order', 'orderItems', 'name'));
    }

    public function shiftwork(Request $request){
       
        $input = $request->all();
        $input['created_at'] =  Carbon::now('Asia/Ho_Chi_Minh');
        $input['store_code'] = auth()->user()->store_code;
        $input['shift_open'] = session('shift_open');
        $input['shift_close'] = Carbon::now('Asia/Ho_Chi_Minh');
        $shift =   ShiftWork::create($input);
        session(['total_revenue' => 0, 'revenue_cash' => 0, 'revenue_online' => 0, 'surplus_box' => null]);
        auth()->logout();
        return view('backend.order.printshift', compact('shift'));
    }

    //create order for admin
    public function createorderadmin (){
        $categories = Category::all();
        return view('backend.order.createorder', compact('categories'));
    }

    public function getproductbycategory(Request $request){
        if($request->ajax()){
            $products = Product::where('category_id', $request->category_id)->get();
            return json_encode($products);
        }
    }

    public function productdetails($id){
        $product = Product::find($id);
        $recipe_ids = ProductRecipe::where('product_id', $id)->get('recipe_id');
        $recipes = Recipe::whereIn('id', $recipe_ids)->get();
        if($recipes){
            return view('backend.order.productdetails', compact('product', 'recipes'));
        }
        return view('backend.order.productdetails', compact('product'));
    }

    public function admincartadd(Request $request, $id){
        
            $price = $request->input('price_' . $id);
            $qty = $request->input('quantity_' . $id);
            $product = Product::find($id);
            $name = $product->name;
          
            $size = '';
            if ($product->price == $price) {
                $size = 'M';
            }else{
                $size = 'L';
            }

            Cart::add([
                'id' => $id,
                'name' => $name,
                'qty' => $qty,
                'price' => (int) $price,
                'weight' => 12,
                'options' => [
                    'size' => $size,
                ],
            ]);
        session(['success' => 'Thêm thành công']);
        return redirect(route('order.admin'));
    }

    public function admincartshow(){
        return view('backend.order.showcart');
    }

    public function admincartdelete($rowID){
        Cart::update($rowID, 0);
        if (Cart::count() === 0) {
            session(['success' => 'Bạn vừa xoá một sản phẩm']);
            return redirect(route('order.admin'));
        }
        session(['success' => 'Bạn vừa xoá một sản phẩm']);
        return redirect(route('order.admin'));
    }

    public function admincartcheckout(Request $request){
        
        $cart_subtotal = Cart::subtotal();
        $temp = explode(".", $cart_subtotal);
        $temp1 = explode(",", $temp[0]);
        $price = $temp1[0] . $temp1[1];
        $total_price = intval($price);
        $order = Order::create([
            'store_code' => auth()->user()->store_code,
            'total_price' => $total_price,
            'customer_id' => auth()->id(),
            'table' => $request->table ? : 1,
            'order_here' => 1,
            'order_date' => Carbon::now('Asia/Ho_Chi_Minh'),
            'note' => $request->note,
            'created_by' => auth()->user()->name,
            'payment_method' => 2, 
            'price' => $total_price,
            'order_code' => '#' . auth()->user()->store_code . time() . auth()->id()
        ]);

        $contents = Cart::content();

        foreach ($contents as $key => $value) {
           
                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $value->id,
                    'price' => $value->price,
                    'quantity' => $value->qty,
                    'size' => $value->options->size
                ]);
        }

        Cart::destroy();
        return redirect(route('order.details', ['id' => $order->id ]));
    } 
}
