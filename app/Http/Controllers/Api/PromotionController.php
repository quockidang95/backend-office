<?php

namespace App\Http\Controllers\Api;

use App\Promotion;
use Carbon\Carbon;
use App\PromotionProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function get($code)
    {
        $promotion = Promotion::where('promotion_code', $code)->first();

        if ($promotion) {
            $check = $this->checkStatus($promotion);
            
            if (!$check) {
                $promotion->update([ 'status' => 'expired']);
            } else {
                $promotion->update([ 'status' => 'still']);
            }

            $product_ids = PromotionProduct::select('id')->where('promotion_id', $promotion->id)->get();
            $promotion['product_ids'] = $product_ids;
            return response()->json($promotion, 200);
        }

        return response()->json(['error' => 'Promotion not found'], 400);
    }



    // repositories for Promotion API
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
}
