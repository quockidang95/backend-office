<?php

namespace App\Http\Controllers\Api;

use App\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function get($code){

        $promotion = Promotion::where('promotion_code', $code)->first();

        if( $promotion ) {

            $check = $this->checkStatus($promotion);
            
            if (!$check){
                $promotion->update([ 'status' => 'expired']);
            } else {
                $promotion->update([ 'status' => 'still']);
            }
            
            return response()->json($promotion, 200);
        }

        return response()->json(['error' => 'Promotion not found'], 400);
    }



    // repositories for Promotion API
    public function checkStatus(Promotion $promotion){

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        if ( $promotion->start_date < $now && $promotion->end_date > $now ) {
            return true;
        }

        return false;

    }
}
