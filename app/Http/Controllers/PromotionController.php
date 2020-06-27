<?php

namespace App\Http\Controllers;

use Exception;
use App\Product;
use App\Category;
use App\Promotion;
use Carbon\Carbon;
use App\PromotionProduct;
use Illuminate\Http\Request;
use App\Http\Requests\StorePromotionRequest;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::simplePaginate(10);
        foreach ($promotions as $promotion) {
            $check = $this->checkStatus($promotion);

            if (!$check) {
                $promotion->update([ 'status' => 'expired']);
            } else {
                $promotion->update([ 'status' => 'still']);
            }
        }
       
        return view('backend.promotion.index', compact('promotions'));
    }

    public function viewstore()
    {
        $categories = Category::all();
        
        return view('backend.promotion.store', compact('categories'));
    }

    public function store(StorePromotionRequest $request)
    {
        try {
            $data = $request->all();
            $data['created_at'] = Carbon::now('Asia/Ho_Chi_Minh');

            $promotion = Promotion::create($data);
            $idProducts = $request->arrID;
            foreach ($idProducts as $idProduct) {
                PromotionProduct::create([
                    'product_id' => $idProduct,
                    'promotion_id'=> $promotion->id
                ]);
            }
            session()->put('success', 'Thêm thành công');
            return redirect()->route('promotion.index');
        } catch (Exception $ex) {
            session()->put('error', $ex->getMessage());
            return redirect()->route('promotion.index');
        }
    }

    public function viewupdate($id)
    {
        $promotion = Promotion::find($id);
        $ids = PromotionProduct::select('product_id')->where('promotion_id', $id)->get();
        $products = Product::whereIn('id', $ids)->get();
        $categories = Category::all();
        return view('backend.promotion.update', compact('promotion', 'products', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::find($id)->update($request->all());
        $idProducts = $request->arrID;
        if ($idProducts) {
            PromotionProduct::where('promotion_id', $id)->delete();
        
       
            foreach ($idProducts as $idProduct) {
                PromotionProduct::create([
                    'product_id' => $idProduct,
                    'promotion_id'=> $id
                ]);
            }
        }
        session()->put('success', 'Cập nhật thành công');
        return redirect()->route('promotion.index');
    }

    public function delete($id)
    {
        $checkDeletePromotion = Promotion::find($id)->delete();
        // dd($checkDeletePromotion);
        if ($checkDeletePromotion) {
            session()->put('success', 'Thêm thành công');
            return redirect()->route('promotion.index');
        }

        session()->put('error', 'Xoá thất bại! Vui lòng thử lại sau');
        return redirect()->route('promotion.index');
    }

    // ajax
    public function confirmcode(Request $request)
    {
        if ($request->ajax()) {
            $promotion = Promotion::where('promotion_code', $request->promotionCode)->first();
            
            if (!$promotion) {
                return Response(['error' => 'Mã giảm giá không hợp lệ']);
            }

            $check = $this->checkStatus($promotion);

            if (!$check) {
                return Response(['error' => 'Mã giảm giá này không còn trong thời gian áp dụng']);
            }

            return Response($promotion);

            return Response($request->all());
        }
    }



    // repositories in promotion controller

    public function checkStatus(Promotion $promotion)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        if ($promotion->start_hour > $now->hour || $promotion->end_hour < $now->hour) {
            return false;
        }
        if ($promotion->start_date <= $now->toDateString() && $promotion->end_date >= $now->toDateString()) {
            return true;
        }

        return false;
    }

    // check Promotion Code
    public function checkPromotionCode($promotion_code)
    {
        $promotion = Promotion::where('promotion_code', $promotion_code)->first();

        if ($promotion) {
            return false;
        }

        return true;
    }
}
