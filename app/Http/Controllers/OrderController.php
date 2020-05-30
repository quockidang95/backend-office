<?php

namespace App\Http\Controllers;

use Cart;
use App\Tag;
use App\User;
use App\Order;
use Exception;
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

    public function __construct(OrderRepositoryInterface $orderRepository, UserRepositoryInterface $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $user = auth('web')->user();
        $date = date('Y-m-d');
        $tags = Tag::where('status', 'not_using')->get();

        $orders = Order::where('created_at', 'LIKE', '%' . $date . '%')
                    ->whereIn('status', [1, 2])
                    ->where('store_code', $user->store_code)
                    ->orderby('created_at', 'desc')->get();
        return view('backend.order.index', compact('orders', 'tags'));
    }

    public function surplus(Request $request)
    {
        auth()->user()->update(['is_surplus_box' => 1,
                                'surplus_box' => $request->surplus_box,
                                'login_at' => Carbon::now('Asia/Ho_Chi_Minh')
                                ]);
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

    public function revenue()
    {
        return $this->orderRepository->revenue();
    }

    public function success($order_id)
    {
        $check_status_order = $this->orderRepository->checkOrder($order_id);
        if (!$check_status_order) {
            session(['error' => 'Hãy tiếp nhận đơn hàng trước khi nào gì khác...!']);
            return redirect(route('order.details', ['id' => $order_id]));
        }

        $order = $this->orderRepository->find($order_id);
        $user = $this->userRepository->find($order->customer_id);
        $this->orderRepository->changeStatusAndCheckOutOrder($order, $user);

        $data_message = $this->orderRepository->createdNotificationSuccessOrder($order, $user);
        $mess = $this->orderRepository->send($user->token_device, $data_message);
        session(['success' => 'Đơn hàng này đã được xử lí thành công!']);

        if (session('tag')) {
            $tag =  Tag::where('number_tag', session('tag'))->first();
            $tag->update(['status' => 'not_using']);
        }
        
        return redirect(route('order.byday'));
    }

    public function next($id)
    {
        $check_status_order = $this->orderRepository->checkOrder($id);
        if ($check_status_order) {
            session(['error' => 'Đơn hàng này đã được tiếp nhận rồi...!']);
            return redirect(route('order.details', ['id' => $id]));
        }
        $this->orderRepository->changeStatusNextOrder($id);
        session(['success' => 'Tiếp nhận đơn hàng thành công...!']);
        return redirect(route('order.details', ['id' => $id]));
    }

    public function error($order_id)
    {
        $check_status_order = $this->orderRepository->checkOrder($order_id);
        if (!$check_status_order) {
            session(['error' => 'Hãy tiếp nhận đơn hàng trước khi nào gì khác...!']);
            return redirect(route('order.details', ['id' => $order_id]));
        }
        
        $order = $this->orderRepository->find($order_id);
        $user = $this->userRepository->find($order->customer_id);
        $this->orderRepository->changeStatusAndCancelOrder($order, $user);

        $data_message = $this->orderRepository->createdNotificationCancelOrder($order, $user);
        $mess = $this->orderRepository->send($user->token_device, $data_message);
        session(['success' => 'Đơn hàng này đã được xử lí thành công!']);
        if (session('tag')) {
            $tag =  Tag::where('number_tag', session('tag'))->first();
            $tag->update(['status' => 'not_using']);
        }
        return redirect(route('order.byday'));
    }

    public function vieworder($id)
    {
        return $this->orderRepository->vieworder($id);
    }

    public function indexRechage()
    {
        return $this->orderRepository->indexRechage();
    }

    public function tesst($id)
    {
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


    public function printproduct($id)
    {
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

    public function shiftwork(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();
        $input['created_at'] =  Carbon::now('Asia/Ho_Chi_Minh');
        $input['store_code'] = $user->store_code;
        $input['shift_open'] = $user->login_at;
        $input['shift_close'] = Carbon::now('Asia/Ho_Chi_Minh');

        $totalRevenueCollection = Order::where('store_code', $user->store_code)
                                        ->whereBetween('order_date', [$input['shift_open'], $input['shift_close']])
                                        ->where('status', 3)->get();
        $input['total_revenue'] = $totalRevenueCollection->sum('price');
        $input['revenue_online'] = $totalRevenueCollection->where('payment_method', 1)->sum('price');
        $input['revenue_cash'] = $totalRevenueCollection->where('payment_method', 2)->sum('price');
        $input['end_balance_shift'] = $input['revenue_cash'] + auth()->user()->surplus_box;
        $shift =   ShiftWork::create($input);
       
        auth()->user()->update(['is_surplus_box' => 0, 'surplus_box' => 0]);
        auth()->logout();
        return view('backend.order.printshift', compact('shift'));
    }

    //create order for admin
    public function createorderadmin($tag)
    {
        session(['tag' => $tag]);
        $categories = Category::all();
        $product = Product::all();

        $data_array = [];
        foreach ($categories as $key => $value) {
            $object['category'] = $value;
            $object['list_product'] = $value->products;
            array_push($data_array, $object);
        }
        return view('backend.order.createorder', compact('data_array', 'categories'));
    }

    public function getproductbycategory(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::where('status', 1)->get();
            return json_encode($products);
        }
    }

    public function productdetails($id)
    {
        $product = Product::find($id);
        $recipe_ids = ProductRecipe::where('product_id', $id)->get('recipe_id');
        $recipes = Recipe::whereIn('id', $recipe_ids)->get();
        if ($recipes) {
            return view('backend.order.productdetails', compact('product', 'recipes'));
        }
        return view('backend.order.productdetails', compact('product'));
    }

    public function admincartadd(Request $request, $id)
    {
        $price = $request->input('price_' . $id);
        $qty = $request->input('quantity_' . $id);
        $product = Product::find($id);
        $name = $product->name;
        $size = '';
        if ($product->price == $price) {
            $size = 'M';
        } else {
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
        return redirect(route('order.admin', ['tag' => session('tag')]));
    }

    public function admincartshow()
    {
        return view('backend.order.showcart');
    }

    public function admincartdelete($rowID)
    {
        Cart::update($rowID, 0);
        if (Cart::count() === 0) {
            session(['success' => 'Bạn vừa xoá một sản phẩm']);
            return redirect(route('order.admin', ['tag' => session('tag')]));
        }
        session(['success' => 'Bạn vừa xoá một sản phẩm']);
        return redirect(route('order.admin', ['tag' => session('tag')]));
    }

    public function admincartcheckout(Request $request)
    {
        $discount = $request->discount ?? 0;
        if ($request->is_delivery === null) {
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
                'created_by' => auth()->user()->name,
                'payment_method' => 2,
                'status' => 2,
                'is_pay' => $request->is_pay,
                'price' => $total_price - ($total_price * $discount/100),
                'order_code' => '#' . auth()->user()->store_code . time() . auth()->id(),
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
            $tag =  Tag::where('number_tag', session('tag'))->first();
            if ($tag) {
                $tag->update(['status' => 'using']);
            }
            return redirect(route('order.details', ['id' => $order->id ]));
        } else {
            $cart_contents = Cart::content();
            $price = 0;
            foreach ($cart_contents as $key => $value) {
                $product = Product::find($value->id);
                if (!$product->price_delivery) {
                    $price = $product->price * $value->qty;
                } else {
                    $price += $product->price_delivery * $value->qty;
                }
            }
    
            
            $order = Order::create([
                'store_code' => auth()->user()->store_code,
                'total_price' => $price,
                'customer_id' => auth()->id(),
                'table' => $request->table ? : 1,
                'order_here' => 2,
                'order_date' => Carbon::now('Asia/Ho_Chi_Minh'),
                'created_by' => auth()->user()->name,
                'payment_method' => 2,
                'status' => 2,
                'is_pay' => $request->is_pay,
                'price' => $price - ($price * $discount/100),
                'order_code' => '#' . auth()->user()->store_code . time() . auth()->id()
            ]);

            foreach ($cart_contents as $key => $value) {
                $product = Product::find($value->id);
                
                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $value->id,
                    'price' => $product->price_delivery ? : $product->price,
                    'quantity' => $value->qty,
                ]);
            }

            Cart::destroy();
            $tag =  Tag::where('number_tag', session('tag'))->first();
            if ($tag) {
                $tag->update(['status' => 'using']);
            }
            return redirect(route('order.details', ['id' => $order->id ]));
        }
    }
}
